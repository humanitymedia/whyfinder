<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::role('student');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->withCount('enrollments')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Calculate total spent for each student
        $students->getCollection()->transform(function ($student) {
            $student->total_spent = $student->payments()->where('status', 'completed')->sum('amount');

            return $student;
        });

        return view('admin.students.index', compact('students'));
    }

    public function show(User $user): View
    {
        // Payment history
        $payments = $user->payments()->with('course')->latest()->get();

        // Enrolled courses with progress
        $enrollments = $user->enrollments()->with('course.lessons', 'course.sections')->latest()->get();

        $coursesWithProgress = $enrollments->map(function ($enrollment) use ($user) {
            $course = $enrollment->course;
            $totalLessons = $course->lessons()->count();
            $completedLessons = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
                ->where('is_completed', true)
                ->count();

            return (object) [
                'course' => $course,
                'enrolled_at' => $enrollment->enrolled_at ?? $enrollment->created_at,
                'completed_at' => $enrollment->completed_at,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
            ];
        });

        return view('admin.students.show', compact('user', 'payments', 'coursesWithProgress'));
    }
}
