<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorEarning;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $earnings = InstructorEarning::where('instructor_id', $user->id)
            ->with(['course', 'payment.user'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_earnings' => InstructorEarning::where('instructor_id', $user->id)->sum('net_amount'),
            'pending_earnings' => InstructorEarning::where('instructor_id', $user->id)->where('status', 'pending')->sum('net_amount'),
            'paid_earnings' => InstructorEarning::where('instructor_id', $user->id)->where('status', 'paid')->sum('net_amount'),
            'total_sales' => InstructorEarning::where('instructor_id', $user->id)->count(),
            'this_month' => InstructorEarning::where('instructor_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('net_amount'),
        ];

        return view('instructor.earnings', compact('earnings', 'stats'));
    }
}
