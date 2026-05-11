<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with(['instructor', 'category'])
            ->withCount('enrollments');

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($instructorId = $request->get('instructor')) {
            $query->where('instructor_id', $instructorId);
        }

        $courses = $query->latest()->paginate(15)->withQueryString();

        // Add revenue for each course
        $courses->getCollection()->transform(function ($course) {
            $course->total_revenue = $course->payments()->where('status', 'completed')->sum('amount');
            return $course;
        });

        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $totalStudents = Enrollment::count();

        $instructors = User::role('instructor')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.courses.index', compact(
            'courses', 'totalCourses', 'publishedCourses', 'totalStudents', 'instructors'
        ));
    }

    public function show(Course $course): View
    {
        $course->load(['instructor', 'category', 'sections.lessons']);

        $totalRevenue = $course->payments()->where('status', 'completed')->sum('amount');
        $totalLessons = $course->lessons()->count();
        $avgRating = $course->approvedReviews()->avg('rating');
        $reviewCount = $course->approvedReviews()->count();
        $enrollmentCount = $course->enrollments()->count();

        // Get enrolled students with progress
        $enrollments = $course->enrollments()
            ->with('user')
            ->latest('enrolled_at')
            ->paginate(20)
            ->withQueryString();

        $lessonIds = $course->lessons()->pluck('lessons.id');

        $enrollments->getCollection()->transform(function ($enrollment) use ($lessonIds, $totalLessons) {
            $completedLessons = LessonProgress::where('user_id', $enrollment->user_id)
                ->whereIn('lesson_id', $lessonIds)
                ->where('is_completed', true)
                ->count();

            $enrollment->completed_lessons = $completedLessons;
            $enrollment->total_lessons = $totalLessons;
            $enrollment->progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

            return $enrollment;
        });

        return view('admin.courses.show', compact(
            'course', 'totalRevenue', 'totalLessons', 'avgRating', 'reviewCount', 'enrollmentCount', 'enrollments'
        ));
    }
}
