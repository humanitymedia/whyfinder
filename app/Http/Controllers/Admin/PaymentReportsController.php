<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorEarning;
use App\Models\Payment;
use Illuminate\View\View;

class PaymentReportsController extends Controller
{
    public function __invoke(): View
    {
        $payments = Payment::with(['user', 'course', 'instructorEarning'])
            ->latest()
            ->paginate(25);

        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_platform_fees' => InstructorEarning::sum('platform_fee'),
            'total_instructor_payouts' => InstructorEarning::where('status', 'paid')->sum('net_amount'),
            'pending_payouts' => InstructorEarning::where('status', 'pending')->sum('net_amount'),
            'total_transactions' => Payment::where('status', 'completed')->count(),
            'this_month_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return view('admin.payments', compact('payments', 'stats'));
    }
}
