<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $featuredPost = Post::published()
            ->featured()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->first();

        $posts = Post::published()
            ->with(['author', 'category'])
            ->when($featuredPost, fn ($q) => $q->where('id', '!=', $featuredPost->id))
            ->latest('published_at')
            ->get();

        return view('blog.index', compact('featuredPost', 'posts'));
    }

    public function show(string $slug): View
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
