<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isEnrolledIn($course)) {
            abort(403, 'You must be enrolled in this course to leave a review.');
        }

        if (Review::where('user_id', $user->id)->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You have already reviewed this course.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
