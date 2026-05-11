<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $totalReviews = Review::count();
        $pendingReviews = Review::where('is_approved', false)->count();
        $averageRating = Review::where('is_approved', true)->avg('rating');

        $query = Review::with(['user', 'course'])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('course', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        if ($courseId = $request->input('course')) {
            $query->where('course_id', $courseId);
        }

        if ($status = $request->input('status')) {
            $query->where('is_approved', $status === 'approved');
        }

        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        $reviews = $query->paginate(20)->withQueryString();
        $courses = Course::orderBy('title')->get(['id', 'title']);

        return view('admin.reviews.index', compact(
            'reviews',
            'courses',
            'totalReviews',
            'pendingReviews',
            'averageRating',
        ));
    }

    public function toggleApproval(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('success', $review->is_approved ? 'Review approved.' : 'Review unapproved.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
