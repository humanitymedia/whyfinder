<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::where('status', 'published')
            ->with(['instructor', 'category'])
            ->withCount(['enrollments', 'lessons']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($level = $request->input('level')) {
            $query->where('difficulty_level', $level);
        }

        $courses = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show(string $slug): View
    {
        $course = Course::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'instructor',
                'category',
                'sections.lessons',
                'approvedReviews.user',
            ])
            ->withCount(['enrollments', 'lessons'])
            ->firstOrFail();

        $isEnrolled = auth()->check() && auth()->user()->isEnrolledIn($course);

        $hasReviewed = false;
        $userCertificate = null;

        if (auth()->check()) {
            $hasReviewed = Review::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
            $userCertificate = Certificate::where('user_id', auth()->id())->where('course_id', $course->id)->first();
        }

        return view('courses.show', compact('course', 'isEnrolled', 'hasReviewed', 'userCertificate'));
    }
}
