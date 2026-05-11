@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
@endpush

<x-admin-layout>
    <x-slot name="header">Edit Post</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">&larr; Back to Blog</a>
    </div>

    <form method="POST" action="{{ route('admin.blog.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main content (left 2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Content section --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-brand-dark">Content</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Title</label>
                            <input type="text" name="title" value="{{ old('title', $post->title) }}"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors"
                                   required>
                            @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Excerpt</label>
                            <textarea name="excerpt" rows="2"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors"
                                      placeholder="Brief summary for the blog listing...">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Body</label>
                            <textarea name="body" id="body-editor" rows="12"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">{{ old('body', $post->body) }}</textarea>
                            @error('body') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SEO section --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-brand-dark">SEO</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors"
                                   placeholder="Leave blank to use post title">
                            @error('meta_title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="2"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors"
                                      placeholder="Search engine description...">{{ old('meta_description', $post->meta_description) }}</textarea>
                            @error('meta_description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar (right 1/3) --}}
            <div class="space-y-6">
                {{-- Publishing --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-brand-dark">Publishing</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Status</label>
                            <select name="status"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" id="is_featured"
                                   {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                            <label for="is_featured" class="text-sm text-brand-dark">Featured post</label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Read Time (min)</label>
                            <input type="number" name="read_time_minutes" value="{{ old('read_time_minutes', $post->read_time_minutes) }}"
                                   min="1" max="120" placeholder="Auto-calculated"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
                            @error('read_time_minutes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            Update Post
                        </button>
                    </div>
                </div>

                {{-- Media --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden"
                     x-data="{ preview: null, remove: false, hasExisting: {{ $post->featured_image ? 'true' : 'false' }} }">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-brand-dark">Featured Image</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($post->featured_image)
                            <div x-show="hasExisting && !preview && !remove">
                                <img src="{{ Storage::url($post->featured_image) }}" class="w-full rounded-lg object-cover max-h-48">
                                <button type="button" @click="remove = true"
                                        class="mt-2 text-xs text-red-600 hover:text-red-700 transition-colors">Remove image</button>
                            </div>
                        @endif
                        <template x-if="preview">
                            <img :src="preview" class="w-full rounded-lg object-cover max-h-48">
                        </template>
                        <div x-show="remove && !preview" class="text-xs text-amber-600 bg-amber-50 px-3 py-2 rounded-lg">
                            Image will be removed on save.
                            <button type="button" @click="remove = false" class="underline ml-1">Undo</button>
                        </div>
                        <input type="hidden" name="remove_featured_image" :value="remove && !preview ? '1' : '0'">
                        <label class="flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:border-brand-red/40 transition-colors">
                            <svg class="w-5 h-5 text-brand-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            <span class="text-sm text-brand-gray" x-text="preview || (hasExisting && !remove) ? 'Change image' : 'Upload image'">{{ $post->featured_image ? 'Change image' : 'Upload image' }}</span>
                            <input type="file" name="featured_image" accept="image/*" class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0]); remove = false">
                        </label>
                        <p class="text-xs text-brand-gray">Max 2 MB. JPG, PNG, GIF, or WebP.</p>
                        @error('featured_image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Categorization --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-brand-dark">Categorization</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Category</label>
                            <select name="post_category_id"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
                                <option value="">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (int) old('post_category_id', $post->post_category_id) === $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('post_category_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if($tags->count())
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-2">Tags</label>
                                <div class="space-y-2">
                                    @php $selectedTags = old('tags', $post->tags->pluck('id')->toArray()); @endphp
                                    @foreach($tags as $tag)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                   {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                                            <span class="text-sm text-brand-dark">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Danger zone --}}
                <div class="bg-white rounded-xl border border-red-200 overflow-hidden">
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.blog.destroy', $post) }}"
                              onsubmit="return confirm('Are you sure you want to delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full px-6 py-2.5 bg-white text-red-600 border border-red-200 text-sm font-semibold rounded-lg hover:bg-red-50 transition-colors">
                                Delete Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        new EasyMDE({
            element: document.getElementById('body-editor'),
            spellChecker: false,
            status: false,
            minHeight: '300px',
            placeholder: 'Write your post content in Markdown...',
        });
    </script>
@endpush
