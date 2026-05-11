<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Notifications\InstructorRepliedToThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ForumReplyController extends Controller
{
    public function store(Request $request, Course $course, string $threadSlug): RedirectResponse
    {
        $user = auth()->user();

        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        if ($thread->is_locked && ! $this->canModerate($course)) {
            return back()->with('error', 'This thread is locked.');
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'exists:forum_replies,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            $parentBelongs = ForumReply::where('id', $validated['parent_id'])
                ->where('thread_id', $thread->id)
                ->exists();
            if (! $parentBelongs) {
                $validated['parent_id'] = null;
            }

            // Enforce single-level nesting
            $parentReply = ForumReply::find($validated['parent_id']);
            if ($parentReply && $parentReply->parent_id !== null) {
                $validated['parent_id'] = $parentReply->parent_id;
            }
        }

        $validated['thread_id'] = $thread->id;
        $validated['user_id'] = $user->id;

        $reply = ForumReply::create($validated);

        // Notify thread author when course instructor replies
        if (
            $user->id !== $thread->user_id
            && $user->hasRole('instructor')
            && $course->instructor_id === $user->id
        ) {
            $thread->user->notify(new InstructorRepliedToThread($thread, $reply));
        }

        return redirect()
            ->route('forum.show', [$course->slug, $thread->slug])
            ->with('success', 'Reply posted.');
    }

    public function destroy(Course $course, string $threadSlug, ForumReply $reply): RedirectResponse
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        if ($reply->thread_id !== $thread->id) {
            abort(404);
        }

        if ($reply->user_id !== auth()->id() && ! $this->canModerate($course)) {
            abort(403);
        }

        $reply->delete();

        return back()->with('success', 'Reply deleted.');
    }

    private function authorizeCourseAccess(Course $course): void
    {
        $user = auth()->user();

        if ($user->hasRole(['admin', 'manager'])) {
            return;
        }

        if ($user->hasRole('instructor') && $course->instructor_id === $user->id) {
            return;
        }

        if ($user->isEnrolledIn($course)) {
            return;
        }

        abort(403, 'You must be enrolled in this course to access its forum.');
    }

    private function canModerate(Course $course): bool
    {
        $user = auth()->user();

        if ($user->hasRole(['admin', 'manager'])) {
            return true;
        }

        if ($user->hasRole('instructor') && $course->instructor_id === $user->id) {
            return true;
        }

        return false;
    }
}
