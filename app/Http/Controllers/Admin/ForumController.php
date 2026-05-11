<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $query = ForumThread::with(['user', 'course'])
            ->withCount('approvedReplies');

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($courseId = $request->get('course')) {
            $query->where('course_id', $courseId);
        }

        if ($status = $request->get('status')) {
            match ($status) {
                'pinned' => $query->where('is_pinned', true),
                'locked' => $query->where('is_locked', true),
                'active' => $query->where('is_locked', false),
                default => null,
            };
        }

        $threads = $query->latest()->paginate(20)->withQueryString();

        $courses = Course::where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title']);

        $totalThreads = ForumThread::count();
        $totalReplies = ForumReply::count();
        $unapprovedReplies = ForumReply::where('is_approved', false)->count();

        return view('admin.forums.index', compact(
            'threads', 'courses', 'totalThreads', 'totalReplies', 'unapprovedReplies'
        ));
    }

    public function togglePin(ForumThread $thread): RedirectResponse
    {
        $thread->update(['is_pinned' => ! $thread->is_pinned]);

        return back()->with('success', $thread->is_pinned ? 'Thread pinned.' : 'Thread unpinned.');
    }

    public function toggleLock(ForumThread $thread): RedirectResponse
    {
        $thread->update(['is_locked' => ! $thread->is_locked]);

        return back()->with('success', $thread->is_locked ? 'Thread locked.' : 'Thread unlocked.');
    }

    public function destroy(ForumThread $thread): RedirectResponse
    {
        $thread->delete();

        return back()->with('success', 'Thread deleted.');
    }

    public function toggleReplyApproval(ForumReply $reply): RedirectResponse
    {
        $reply->update(['is_approved' => ! $reply->is_approved]);

        return back()->with('success', $reply->is_approved ? 'Reply approved.' : 'Reply hidden.');
    }
}
