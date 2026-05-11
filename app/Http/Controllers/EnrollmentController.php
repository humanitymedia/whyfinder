<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;

class EnrollmentController extends Controller
{
    public function store(Course $course): RedirectResponse
    {
        $user = auth()->user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You are already enrolled in this course.');
        }

        // Redirect paid courses to Stripe checkout
        if (! $course->is_free && $course->price > 0) {
            return redirect()->route('payment.checkout', $course);
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $firstLesson = $course->lessons()->orderBy('sort_order')->first();

        if ($firstLesson) {
            return redirect()->route('learn.show', [$course->slug, $firstLesson->id])
                ->with('success', 'You are now enrolled! Start learning.');
        }

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'You are now enrolled in this course.');
    }
}
