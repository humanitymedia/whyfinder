<x-instructor-layout>
    <x-slot name="header">Edit Course</x-slot>

    <div class="max-w-4xl" x-data="{ activeTab: '{{ str_contains(session('success') ?? '', 'Section') || str_contains(session('success') ?? '', 'Lesson') ? 'curriculum' : 'details' }}' }">
        {{-- Back link --}}
        <a href="{{ route('instructor.courses.index') }}" class="inline-flex items-center gap-1 text-sm text-brand-gray hover:text-brand-dark transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Courses
        </a>

        {{-- Course title header --}}
        <div class="flex items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-brand-dark">{{ $course->title }}</h2>
                <div class="flex items-center gap-3 mt-1">
                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $course->status_badge_color }}">
                        {{ ucfirst($course->status ?? 'draft') }}
                    </span>
                    <span class="text-sm text-brand-gray">{{ $course->lessons()->count() }} {{ Str::plural('lesson', $course->lessons()->count()) }}</span>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-6">
                <button @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'border-brand-red text-brand-red' : 'border-transparent text-brand-gray hover:text-brand-dark hover:border-gray-300'"
                        class="pb-3 text-sm font-medium border-b-2 transition-colors">
                    Course Details
                </button>
                <button @click="activeTab = 'curriculum'"
                        :class="activeTab === 'curriculum' ? 'border-brand-red text-brand-red' : 'border-transparent text-brand-gray hover:text-brand-dark hover:border-gray-300'"
                        class="pb-3 text-sm font-medium border-b-2 transition-colors">
                    Curriculum
                </button>
                <button @click="activeTab = 'publish'"
                        :class="activeTab === 'publish' ? 'border-brand-red text-brand-red' : 'border-transparent text-brand-gray hover:text-brand-dark hover:border-gray-300'"
                        class="pb-3 text-sm font-medium border-b-2 transition-colors">
                    Publish
                </button>
            </nav>
        </div>

        {{-- Tab: Course Details --}}
        <div x-show="activeTab === 'details'" x-cloak>
            <form method="POST" action="{{ route('instructor.courses.update', $course) }}" class="space-y-6 max-w-2xl">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                    <h3 class="font-semibold text-brand-dark">Course Details</h3>

                    <div>
                        <label for="title" class="block text-sm font-medium text-brand-dark mb-1">Course Title</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $course->title) }}" required
                               class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-brand-dark mb-1">Short Description</label>
                        <input id="short_description" type="text" name="short_description" value="{{ old('short_description', $course->short_description) }}"
                               class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                        <x-input-error :messages="$errors->get('short_description')" class="mt-1" />
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-brand-dark mb-1">Full Description</label>
                        <textarea id="description" name="description" rows="5"
                                  class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">{{ old('description', $course->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div>
                        <label for="preview_video_url" class="block text-sm font-medium text-brand-dark mb-1">Preview Video URL</label>
                        <input id="preview_video_url" type="url" name="preview_video_url" value="{{ old('preview_video_url', $course->preview_video_url) }}"
                               placeholder="https://youtube.com/watch?v=..."
                               class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                        <x-input-error :messages="$errors->get('preview_video_url')" class="mt-1" />
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                    <h3 class="font-semibold text-brand-dark">Category & Level</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-brand-dark mb-1">Category</label>
                            <select id="category_id" name="category_id"
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="difficulty_level" class="block text-sm font-medium text-brand-dark mb-1">Difficulty Level</label>
                            <select id="difficulty_level" name="difficulty_level" required
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                                <option value="beginner" {{ old('difficulty_level', $course->difficulty_level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty_level', $course->difficulty_level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty_level', $course->difficulty_level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                    <h3 class="font-semibold text-brand-dark">Pricing</h3>

                    <div x-data="{ isFree: {{ old('is_free', $course->is_free) ? 'true' : 'false' }} }">
                        <label class="flex items-center gap-3 mb-4 cursor-pointer">
                            <input type="checkbox" name="is_free" value="1" x-model="isFree"
                                   class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                            <span class="text-sm font-medium text-brand-dark">This is a free course</span>
                        </label>

                        <div x-show="!isFree" x-cloak>
                            <label for="price" class="block text-sm font-medium text-brand-dark mb-1">Price (USD)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-gray text-sm">$</span>
                                <input id="price" type="number" name="price" value="{{ old('price', $course->price) }}" step="0.01" min="0"
                                       class="w-full rounded-lg border-gray-300 pl-8 pr-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                            </div>
                        </div>

                        <input x-show="isFree" type="hidden" name="price" value="0">
                    </div>
                </div>

                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Save Changes
                </button>
            </form>
        </div>

        {{-- Tab: Curriculum --}}
        <div x-show="activeTab === 'curriculum'" x-cloak>
            <div class="space-y-6">
                {{-- Existing sections --}}
                @foreach($course->sections as $section)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ editingSection: false, addingLesson: false }">
                        {{-- Section header --}}
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center gap-3">
                            <svg class="w-5 h-5 text-brand-gray shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>

                            <div class="flex-1 min-w-0" x-show="!editingSection">
                                <h4 class="font-semibold text-brand-dark text-sm">{{ $section->title }}</h4>
                                <p class="text-xs text-brand-gray">{{ $section->lessons->count() }} {{ Str::plural('lesson', $section->lessons->count()) }}</p>
                            </div>

                            {{-- Inline edit section title --}}
                            <form method="POST" action="{{ route('instructor.courses.sections.update', [$course, $section]) }}"
                                  class="flex-1" x-show="editingSection" x-cloak>
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-2">
                                    <input type="text" name="title" value="{{ $section->title }}" required
                                           class="flex-1 rounded-lg border-gray-300 px-3 py-1.5 text-sm focus:border-brand-red focus:ring-brand-red">
                                    <button type="submit" class="text-sm text-brand-red hover:text-red-700">Save</button>
                                    <button type="button" @click="editingSection = false" class="text-sm text-brand-gray">Cancel</button>
                                </div>
                            </form>

                            <div class="flex items-center gap-1 shrink-0" x-show="!editingSection">
                                <button @click="editingSection = true" class="p-1.5 text-brand-gray hover:text-brand-dark transition-colors" title="Rename">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                </button>
                                <form method="POST" action="{{ route('instructor.courses.sections.destroy', [$course, $section]) }}"
                                      onsubmit="return confirm('Delete this section and all its lessons?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-brand-gray hover:text-red-600 transition-colors" title="Delete section">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Lessons --}}
                        <div class="divide-y divide-gray-100">
                            @foreach($section->lessons as $lesson)
                                <div class="px-6 py-3 flex items-center gap-3" x-data="{ editingLesson: false }">
                                    {{-- Lesson type icon --}}
                                    <div class="shrink-0">
                                        @if($lesson->type === 'video')
                                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" /></svg>
                                        @elseif($lesson->type === 'text')
                                            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                        @else
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0" x-show="!editingLesson">
                                        <span class="text-sm text-brand-dark">{{ $lesson->title }}</span>
                                        <span class="text-xs text-brand-gray ml-2">{{ ucfirst($lesson->type) }}</span>
                                        @if($lesson->is_free_preview)
                                            <span class="text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded ml-2">Free Preview</span>
                                        @endif
                                    </div>

                                    {{-- Inline edit lesson --}}
                                    <form method="POST" action="{{ route('instructor.courses.lessons.update', [$course, $lesson]) }}"
                                          class="flex-1" x-show="editingLesson" x-cloak>
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center gap-2">
                                            <input type="text" name="title" value="{{ $lesson->title }}" required
                                                   class="flex-1 rounded-lg border-gray-300 px-3 py-1.5 text-sm focus:border-brand-red focus:ring-brand-red">
                                            <select name="type" class="rounded-lg border-gray-300 px-2 py-1.5 text-xs focus:border-brand-red focus:ring-brand-red">
                                                <option value="video" {{ $lesson->type === 'video' ? 'selected' : '' }}>Video</option>
                                                <option value="text" {{ $lesson->type === 'text' ? 'selected' : '' }}>Text</option>
                                                <option value="download" {{ $lesson->type === 'download' ? 'selected' : '' }}>Download</option>
                                            </select>
                                            <label class="flex items-center gap-1 text-xs text-brand-gray">
                                                <input type="checkbox" name="is_free_preview" value="1" {{ $lesson->is_free_preview ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                                                Free
                                            </label>
                                            <button type="submit" class="text-sm text-brand-red hover:text-red-700">Save</button>
                                            <button type="button" @click="editingLesson = false" class="text-sm text-brand-gray">Cancel</button>
                                        </div>
                                    </form>

                                    <div class="flex items-center gap-1 shrink-0" x-show="!editingLesson">
                                        <button @click="editingLesson = true" class="p-1 text-brand-gray hover:text-brand-dark transition-colors" title="Edit lesson">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                        </button>
                                        <form method="POST" action="{{ route('instructor.courses.lessons.destroy', [$course, $lesson]) }}"
                                              onsubmit="return confirm('Delete this lesson?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-brand-gray hover:text-red-600 transition-colors" title="Delete lesson">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Add lesson form --}}
                            <div class="px-6 py-3">
                                <button @click="addingLesson = !addingLesson" x-show="!addingLesson"
                                        class="text-sm text-brand-red hover:text-red-700 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    Add Lesson
                                </button>

                                <form method="POST" action="{{ route('instructor.courses.sections.lessons.store', [$course, $section]) }}"
                                      x-show="addingLesson" x-cloak class="flex items-center gap-2">
                                    @csrf
                                    <input type="text" name="title" placeholder="Lesson title" required
                                           class="flex-1 rounded-lg border-gray-300 px-3 py-1.5 text-sm focus:border-brand-red focus:ring-brand-red">
                                    <select name="type" class="rounded-lg border-gray-300 px-2 py-1.5 text-xs focus:border-brand-red focus:ring-brand-red">
                                        <option value="video">Video</option>
                                        <option value="text">Text</option>
                                        <option value="download">Download</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-brand-red text-white text-sm rounded-lg hover:bg-red-700 transition-colors">Add</button>
                                    <button type="button" @click="addingLesson = false" class="text-sm text-brand-gray">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Add new section --}}
                <div class="bg-white rounded-xl border border-gray-200 border-dashed p-6" x-data="{ adding: false }">
                    <button @click="adding = true" x-show="!adding"
                            class="w-full flex items-center justify-center gap-2 text-sm text-brand-gray hover:text-brand-dark transition-colors py-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Add Section
                    </button>

                    <form method="POST" action="{{ route('instructor.courses.sections.store', $course) }}"
                          x-show="adding" x-cloak class="flex items-center gap-3">
                        @csrf
                        <input type="text" name="title" placeholder="Section title (e.g. Introduction)" required
                               class="flex-1 rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                        <button type="submit" class="px-5 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">Add Section</button>
                        <button type="button" @click="adding = false" class="text-sm text-brand-gray">Cancel</button>
                    </form>
                </div>

                @if($course->sections->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-brand-gray text-sm">Start by adding a section to organize your course content.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab: Publish --}}
        <div x-show="activeTab === 'publish'" x-cloak>
            <div class="max-w-2xl space-y-6">
                {{-- Course readiness checklist --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-brand-dark mb-4">Pre-Publish Checklist</h3>

                    @php
                        $hasTitle = !empty($course->title);
                        $hasDescription = !empty($course->description);
                        $hasSections = $course->sections->count() > 0;
                        $hasLessons = $course->lessons()->count() > 0;
                        $isReady = $hasTitle && $hasDescription && $hasSections && $hasLessons;
                    @endphp

                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            @if($hasTitle)
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                            <span class="text-sm {{ $hasTitle ? 'text-brand-dark' : 'text-brand-gray' }}">Course title added</span>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($hasDescription)
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                            <span class="text-sm {{ $hasDescription ? 'text-brand-dark' : 'text-brand-gray' }}">Course description written</span>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($hasSections)
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                            <span class="text-sm {{ $hasSections ? 'text-brand-dark' : 'text-brand-gray' }}">At least one section created</span>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($hasLessons)
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                            <span class="text-sm {{ $hasLessons ? 'text-brand-dark' : 'text-brand-gray' }}">At least one lesson added</span>
                        </div>
                    </div>
                </div>

                {{-- Current status --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-brand-dark mb-2">Current Status</h3>
                    <p class="text-sm text-brand-gray mb-4">
                        @if($course->status === 'draft')
                            Your course is currently a <strong>draft</strong>. Submit it for review when you're ready.
                        @elseif($course->status === 'review')
                            Your course is <strong>under review</strong>. You'll be notified once it's approved.
                        @else
                            Your course is <strong>published</strong> and live on the platform.
                        @endif
                    </p>

                    @if($course->status === 'draft')
                        <form method="POST" action="{{ route('instructor.courses.publish', $course) }}">
                            @csrf
                            <button type="submit"
                                    @unless($isReady) disabled @endunless
                                    class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Submit for Review
                            </button>
                            @unless($isReady)
                                <p class="text-xs text-brand-gray mt-2">Complete all checklist items above before submitting.</p>
                            @endunless
                        </form>
                    @elseif($course->status === 'review')
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Pending review
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Published
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-instructor-layout>
