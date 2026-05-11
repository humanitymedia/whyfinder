<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredCourses = Course::where('status', 'published')
            ->where(function ($q) {
                $q->where('is_featured', true)
                  ->orWhere('is_free', true);
            })
            ->with(['instructor', 'category'])
            ->withCount(['enrollments', 'lessons'])
            ->limit(3)
            ->latest()
            ->get();

        return view('home', compact('featuredCourses'));
    }
}
