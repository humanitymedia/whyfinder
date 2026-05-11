<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('instructor_id', auth()->id())
            ->withCount('lessons')
            ->latest()
            ->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('instructor.courses.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'difficulty_level' => ['required', 'in:beginner,intermediate,advanced'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_free' => ['boolean'],
        ]);

        $validated['instructor_id'] = auth()->id();
        $validated['is_free'] = $request->boolean('is_free');

        if ($validated['is_free']) {
            $validated['price'] = 0;
        }

        $course = Course::create($validated);

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('success', 'Course created! Now add your curriculum.');
    }

    public function edit(Course $course): View
    {
        $this->authorizeCourse($course);

        $course->load(['sections.lessons']);
        $categories = Category::orderBy('name')->get();

        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'difficulty_level' => ['required', 'in:beginner,intermediate,advanced'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_free' => ['boolean'],
            'preview_video_url' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['is_free'] = $request->boolean('is_free');

        if ($validated['is_free']) {
            $validated['price'] = 0;
        }

        $course->update($validated);

        return back()->with('success', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $course->delete();

        return redirect()
            ->route('instructor.courses.index')
            ->with('success', 'Course deleted.');
    }

    /**
     * Add a section to a course.
     */
    public function addSection(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $maxOrder = $course->sections()->max('sort_order') ?? -1;

        $course->sections()->create([
            'title' => $validated['title'],
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Section added.');
    }

    /**
     * Update a section title.
     */
    public function updateSection(Request $request, Course $course, CourseSection $section): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $section->update($validated);

        return back()->with('success', 'Section updated.');
    }

    /**
     * Delete a section and its lessons.
     */
    public function deleteSection(Course $course, CourseSection $section): RedirectResponse
    {
        $this->authorizeCourse($course);

        $section->delete();

        return back()->with('success', 'Section deleted.');
    }

    /**
     * Add a lesson to a section.
     */
    public function addLesson(Request $request, Course $course, CourseSection $section): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:video,text,download'],
        ]);

        $maxOrder = $section->lessons()->max('sort_order') ?? -1;

        $section->lessons()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Lesson added.');
    }

    /**
     * Update a lesson.
     */
    public function updateLesson(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:video,text,download'],
            'content' => ['nullable', 'string', 'max:50000'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'is_free_preview' => ['boolean'],
        ]);

        $validated['is_free_preview'] = $request->boolean('is_free_preview');

        $lesson->update($validated);

        return back()->with('success', 'Lesson updated.');
    }

    /**
     * Delete a lesson.
     */
    public function deleteLesson(Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorizeCourse($course);

        $lesson->delete();

        return back()->with('success', 'Lesson deleted.');
    }

    /**
     * Submit course for review / publish.
     */
    public function publish(Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        if ($course->sections()->count() === 0 || $course->lessons()->count() === 0) {
            return back()->with('error', 'Add at least one section with a lesson before publishing.');
        }

        $course->update([
            'status' => 'review',
            'is_published' => false,
        ]);

        return back()->with('success', 'Course submitted for review.');
    }

    /**
     * Reorder sections via AJAX.
     */
    public function reorderSections(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:course_sections,id'],
        ]);

        foreach ($validated['order'] as $index => $sectionId) {
            CourseSection::where('id', $sectionId)
                ->where('course_id', $course->id)
                ->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Sections reordered.');
    }

    /**
     * Reorder lessons within a section via AJAX.
     */
    public function reorderLessons(Request $request, Course $course, CourseSection $section): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:lessons,id'],
        ]);

        foreach ($validated['order'] as $index => $lessonId) {
            Lesson::where('id', $lessonId)
                ->where('section_id', $section->id)
                ->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Lessons reordered.');
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== auth()->id()) {
            abort(403);
        }
    }
}
