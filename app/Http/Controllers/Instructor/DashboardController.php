<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InstructorEarning;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $courseIds = Course::where('instructor_id', $user->id)->pluck('id');

        $courses = Course::where('instructor_id', $user->id)
            ->withCount(['lessons', 'enrollments'])
            ->latest()
            ->get();

        $stats = [
            'total_courses' => $courses->count(),
            'total_students' => Enrollment::whereIn('course_id', $courseIds)->count(),
            'total_earnings' => InstructorEarning::where('instructor_id', $user->id)->sum('net_amount'),
            'monthly_earnings' => InstructorEarning::where('instructor_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('net_amount'),
        ];

        return view('instructor.dashboard', compact('courses', 'stats'));
    }
}
