<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ForumThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumThreadController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorizeCourseAccess($course);

        $threads = $course->forumThreads()
            ->with(['user', 'lesson'])
            ->withCount('approvedReplies')
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(15);

        return view('forum.index', compact('course', 'threads'));
    }

    public function create(Course $course): View
    {
        $this->authorizeCourseAccess($course);

        $lessons = $course->lessons()->orderBy('sort_order')->get();

        return view('forum.create', compact('course', 'lessons'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourseAccess($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
        ]);

        if (! empty($validated['lesson_id'])) {
            $belongs = $course->lessons()->where('lessons.id', $validated['lesson_id'])->exists();
            if (! $belongs) {
                $validated['lesson_id'] = null;
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['course_id'] = $course->id;

        $thread = ForumThread::create($validated);

        return redirect()
            ->route('forum.show', [$course->slug, $thread->slug])
            ->with('success', 'Thread created successfully.');
    }

    public function show(Course $course, string $threadSlug): View
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->with(['user', 'lesson', 'course'])
            ->firstOrFail();

        $replies = $thread->approvedReplies()
            ->whereNull('parent_id')
            ->with([
                'user',
                'children' => fn ($q) => $q->approved()->with('user')->oldest(),
            ])
            ->oldest()
            ->get();

        $canModerate = $this->canModerate($course);

        return view('forum.show', compact('course', 'thread', 'replies', 'canModerate'));
    }

    public function edit(Course $course, string $threadSlug): View
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        $this->authorizeThreadEdit($thread, $course);

        $lessons = $course->lessons()->orderBy('sort_order')->get();

        return view('forum.edit', compact('course', 'thread', 'lessons'));
    }

    public function update(Request $request, Course $course, string $threadSlug): RedirectResponse
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        $this->authorizeThreadEdit($thread, $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
        ]);

        if (! empty($validated['lesson_id'])) {
            $belongs = $course->lessons()->where('lessons.id', $validated['lesson_id'])->exists();
            if (! $belongs) {
                $validated['lesson_id'] = null;
            }
        }

        $thread->update($validated);

        return redirect()
            ->route('forum.show', [$course->slug, $thread->slug])
            ->with('success', 'Thread updated.');
    }

    public function togglePin(Course $course, string $threadSlug): RedirectResponse
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        if (! $this->canModerate($course)) {
            abort(403);
        }

        $thread->update(['is_pinned' => ! $thread->is_pinned]);

        return back()->with('success', $thread->is_pinned ? 'Thread pinned.' : 'Thread unpinned.');
    }

    public function toggleLock(Course $course, string $threadSlug): RedirectResponse
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        if (! $this->canModerate($course)) {
            abort(403);
        }

        $thread->update(['is_locked' => ! $thread->is_locked]);

        return back()->with('success', $thread->is_locked ? 'Thread locked.' : 'Thread unlocked.');
    }

    public function destroy(Course $course, string $threadSlug): RedirectResponse
    {
        $this->authorizeCourseAccess($course);

        $thread = ForumThread::where('course_id', $course->id)
            ->where('slug', $threadSlug)
            ->firstOrFail();

        if ($thread->user_id !== auth()->id() && ! $this->canModerate($course)) {
            abort(403);
        }

        $thread->delete();

        return redirect()
            ->route('forum.index', $course->slug)
            ->with('success', 'Thread deleted.');
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

    private function authorizeThreadEdit(ForumThread $thread, Course $course): void
    {
        if ($thread->user_id === auth()->id()) {
            return;
        }

        if ($this->canModerate($course)) {
            return;
        }

        abort(403);
    }
}
