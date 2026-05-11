<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCertificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(string $courseSlug, Lesson $lesson): View
    {
        $course = Course::where('slug', $courseSlug)
            ->with(['sections.lessons'])
            ->firstOrFail();

        $user = auth()->user();

        // Verify enrollment
        if (! $user->isEnrolledIn($course)) {
            abort(403, 'You must be enrolled in this course to access lessons.');
        }

        // Get user's progress for all lessons in this course
        $lessonIds = $course->lessons()->pluck('lessons.id');
        $completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->pluck('lesson_id');

        $totalLessons = $lessonIds->count();
        $completedCount = $completedLessonIds->count();
        $progressPercent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        // Get current lesson progress
        $currentProgress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        // Find next/previous lessons
        $allLessons = $course->lessons()->orderBy('course_sections.sort_order')->orderBy('lessons.sort_order')->get();
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        return view('learn.show', compact(
            'course',
            'lesson',
            'completedLessonIds',
            'progressPercent',
            'completedCount',
            'totalLessons',
            'currentProgress',
            'prevLesson',
            'nextLesson',
        ));
    }

    public function complete(string $courseSlug, Lesson $lesson): RedirectResponse
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $user = auth()->user();

        if (! $user->isEnrolledIn($course)) {
            abort(403);
        }

        LessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['is_completed' => true, 'completed_at' => now()],
        );

        // Check if all lessons are complete
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
            ->where('is_completed', true)
            ->count();

        if ($completedLessons >= $totalLessons) {
            $user->enrollments()->where('course_id', $course->id)->update([
                'completed_at' => now(),
            ]);

            GenerateCertificate::dispatch($user, $course);
        }

        // Navigate to next lesson or stay
        $allLessons = $course->lessons()->orderBy('course_sections.sort_order')->orderBy('lessons.sort_order')->get();
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $lesson->id);
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        if ($nextLesson) {
            return redirect()->route('learn.show', [$course->slug, $nextLesson->id])
                ->with('success', 'Lesson completed!');
        }

        return redirect()->route('learn.show', [$course->slug, $lesson->id])
            ->with('success', 'Congratulations! You have completed this course!');
    }
}
