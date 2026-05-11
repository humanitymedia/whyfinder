<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('cashier.webhook.secret');

        if ($secret) {
            try {
                $event = Webhook::constructEvent($payload, $signature, $secret);
            } catch (SignatureVerificationException $e) {
                Log::warning('Stripe webhook signature verification failed.', ['error' => $e->getMessage()]);

                return response('Invalid signature.', 400);
            }
        } else {
            $event = json_decode($payload);
        }

        $method = 'handle' . str_replace('.', '', ucwords(str_replace('_', '.', $event->type ?? $event['type'] ?? ''), '.'));

        if (method_exists($this, $method)) {
            return $this->{$method}($event->data->object ?? $event['data']['object'] ?? null);
        }

        return response('Webhook received.', 200);
    }

    /**
     * Handle checkout.session.completed event.
     */
    protected function handleCheckoutSessionCompleted($session): Response
    {
        $metadata = $session->metadata ?? (object) ($session['metadata'] ?? []);
        $courseId = $metadata->course_id ?? $metadata['course_id'] ?? null;
        $userId = $metadata->user_id ?? $metadata['user_id'] ?? null;

        if (! $courseId || ! $userId) {
            Log::warning('Stripe webhook: missing course_id or user_id in metadata.', ['session' => $session]);

            return response('Missing metadata.', 200);
        }

        $user = User::find($userId);
        $course = Course::find($courseId);

        if (! $user || ! $course) {
            return response('User or course not found.', 200);
        }

        $paymentStatus = $session->payment_status ?? $session['payment_status'] ?? null;
        $paymentIntent = $session->payment_intent ?? $session['payment_intent'] ?? 'webhook_' . ($session->id ?? $session['id'] ?? uniqid());
        $amountTotal = ($session->amount_total ?? $session['amount_total'] ?? 0) / 100;

        if ($paymentStatus === 'paid') {
            PaymentController::processSuccessfulPayment($user, $course, $paymentIntent, $amountTotal ?: $course->price);
        }

        return response('Processed.', 200);
    }

    /**
     * Handle payment_intent.payment_failed event.
     */
    protected function handlePaymentIntentPaymentFailed($paymentIntent): Response
    {
        Log::info('Stripe payment failed.', [
            'payment_intent' => $paymentIntent->id ?? $paymentIntent['id'] ?? null,
        ]);

        return response('Noted.', 200);
    }
}
