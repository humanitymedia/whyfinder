<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.instructor', 'course.sections.lessons'])
            ->latest('enrolled_at')
            ->get();

        // Calculate progress for each enrollment
        $coursesWithProgress = $enrollments->map(function ($enrollment) use ($user) {
            $course = $enrollment->course;
            $lessonIds = $course->lessons()->pluck('lessons.id');
            $completedCount = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->where('is_completed', true)
                ->count();
            $totalLessons = $lessonIds->count();

            return (object) [
                'course' => $course,
                'enrolled_at' => $enrollment->enrolled_at,
                'completed_at' => $enrollment->completed_at,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedCount,
                'progress_percent' => $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0,
            ];
        });

        $stats = [
            'enrolled_courses' => $enrollments->count(),
            'completed_courses' => $enrollments->whereNotNull('completed_at')->count(),
            'in_progress' => $enrollments->whereNull('completed_at')->count(),
        ];

        return view('student.dashboard', compact('coursesWithProgress', 'stats'));
    }
}
