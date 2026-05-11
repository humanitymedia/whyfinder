<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InstructorEarning;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PaymentController extends Controller
{
    /**
     * Initiate Stripe Checkout for a paid course.
     */
    public function checkout(Course $course): RedirectResponse
    {
        $user = auth()->user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You are already enrolled in this course.');
        }

        if ($course->is_free || $course->price <= 0) {
            return redirect()->route('enroll', $course);
        }

        $checkout = $user->checkout([
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $course->title,
                        'description' => $course->short_description ?? 'Course on WhyFinder',
                        'metadata' => [
                            'course_id' => $course->id,
                        ],
                    ],
                    'unit_amount' => (int) ($course->price * 100),
                ],
                'quantity' => 1,
            ],
        ], [
            'success_url' => route('payment.success', $course) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('courses.show', $course->slug),
            'metadata' => [
                'course_id' => $course->id,
                'user_id' => $user->id,
            ],
        ]);

        return $checkout->redirect();
    }

    /**
     * Handle successful payment return from Stripe Checkout.
     */
    public function success(Request $request, Course $course): RedirectResponse
    {
        $sessionId = $request->get('session_id');
        $user = auth()->user();

        if (! $sessionId) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Payment session not found.');
        }

        // Check if already enrolled (webhook may have already processed)
        if ($user->isEnrolledIn($course)) {
            $firstLesson = $course->lessons()->orderBy('course_sections.sort_order')->orderBy('lessons.sort_order')->first();

            if ($firstLesson) {
                return redirect()->route('learn.show', [$course->slug, $firstLesson->id])
                    ->with('success', 'Payment successful! Start learning.');
            }

            return redirect()->route('courses.show', $course->slug)
                ->with('success', 'Payment successful! You are now enrolled.');
        }

        // Process the payment if webhook hasn't already
        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $this->processSuccessfulPayment($user, $course, $session->payment_intent, $course->price);
            }
        } catch (\Exception $e) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'There was an issue verifying your payment. Please contact support.');
        }

        $firstLesson = $course->lessons()->orderBy('course_sections.sort_order')->orderBy('lessons.sort_order')->first();

        if ($firstLesson) {
            return redirect()->route('learn.show', [$course->slug, $firstLesson->id])
                ->with('success', 'Payment successful! Start learning.');
        }

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'Payment successful! You are now enrolled.');
    }

    /**
     * Display student payment history.
     */
    public function history(): View
    {
        $payments = auth()->user()->payments()
            ->with('course')
            ->latest()
            ->paginate(20);

        return view('payments.history', compact('payments'));
    }

    /**
     * Process a successful payment: create payment record, enrollment, and instructor earnings.
     */
    public static function processSuccessfulPayment($user, Course $course, string $stripePaymentId, float $amount): void
    {
        // Avoid duplicate processing
        if (Payment::where('stripe_payment_id', $stripePaymentId)->exists()) {
            return;
        }

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'stripe_payment_id' => $stripePaymentId,
            'amount' => $amount,
            'currency' => 'usd',
            'status' => 'completed',
            'payment_type' => 'one_time',
        ]);

        // Enroll the student
        Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['enrolled_at' => now(), 'payment_id' => $payment->id],
        );

        // Calculate and record instructor earnings using dynamic fee
        $instructor = User::find($course->instructor_id);
        $feePercent = $instructor ? $instructor->getPlatformFeePercent() : 30;
        $platformFee = round($amount * ($feePercent / 100), 2);
        $netAmount = round($amount - $platformFee, 2);

        InstructorEarning::create([
            'instructor_id' => $course->instructor_id,
            'payment_id' => $payment->id,
            'course_id' => $course->id,
            'gross_amount' => $amount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'status' => 'pending',
        ]);
    }
}
