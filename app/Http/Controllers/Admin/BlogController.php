<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::with(['author', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = PostCategory::orderBy('name')->get();
        $tags = PostTag::orderBy('name')->get();

        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:100000'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'read_time_minutes' => ['nullable', 'integer', 'min:1', 'max:120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:post_tags,id'],
        ]);

        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        } else {
            unset($validated['featured_image']);
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Auto-calculate read time if body provided and read_time not set
        if (!empty($validated['body']) && empty($validated['read_time_minutes'])) {
            $wordCount = str_word_count(strip_tags($validated['body']));
            $validated['read_time_minutes'] = max(1, (int) ceil($wordCount / 200));
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post = Post::create($validated);
        $post->tags()->sync($tags);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post): View
    {
        $categories = PostCategory::orderBy('name')->get();
        $tags = PostTag::orderBy('name')->get();
        $post->load('tags');

        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:100000'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'read_time_minutes' => ['nullable', 'integer', 'min:1', 'max:120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:post_tags,id'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        } else {
            unset($validated['featured_image']);
        }

        if ($request->boolean('remove_featured_image') && !$request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = null;
        }

        // Handle published_at on status transitions
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        // Auto-calculate read time
        if (!empty($validated['body']) && empty($validated['read_time_minutes'])) {
            $wordCount = str_word_count(strip_tags($validated['body']));
            $validated['read_time_minutes'] = max(1, (int) ceil($wordCount / 200));
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post->update($validated);
        $post->tags()->sync($tags);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Post deleted.');
    }
}
