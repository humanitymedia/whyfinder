<?php

use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\Admin\PaymentReportsController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\ForumThreadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\EarningsController;
use App\Http\Controllers\InstructorApplicationController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', HomeController::class)->name('home');

// Course catalog (public)
Route::get('/courses', [CourseCatalogController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseCatalogController::class, 'show'])->name('courses.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Certificate verification (public)
Route::get('/verify/{certificateNumber}', [CertificateController::class, 'verify'])->name('certificates.verify');

// Teach / Instructor signup
Route::get('/teach', [InstructorApplicationController::class, 'create'])->name('teach');
Route::post('/teach', [InstructorApplicationController::class, 'store'])->name('teach.apply');

// Authenticated routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Enrollment
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enroll');

    // Payments (Stripe Checkout)
    Route::post('/courses/{course}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/courses/{course}/payment-success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/dashboard/payments', [PaymentController::class, 'history'])->name('payment.history');

    // Lesson player
    Route::get('/learn/{courseSlug}/{lesson}', [LessonController::class, 'show'])->name('learn.show');
    Route::post('/learn/{courseSlug}/{lesson}/complete', [LessonController::class, 'complete'])->name('learn.complete');

    // Student dashboard
    Route::get('/my-learning', StudentDashboardController::class)->name('student.dashboard');

    // Certificates
    Route::get('/my-certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // Reviews
    Route::post('/courses/{course:slug}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // Forum (per-course)
    Route::get('/courses/{course:slug}/forum', [ForumThreadController::class, 'index'])->name('forum.index');
    Route::get('/courses/{course:slug}/forum/create', [ForumThreadController::class, 'create'])->name('forum.create');
    Route::post('/courses/{course:slug}/forum', [ForumThreadController::class, 'store'])->name('forum.store');
    Route::get('/courses/{course:slug}/forum/{thread}', [ForumThreadController::class, 'show'])->name('forum.show');
    Route::get('/courses/{course:slug}/forum/{thread}/edit', [ForumThreadController::class, 'edit'])->name('forum.edit');
    Route::put('/courses/{course:slug}/forum/{thread}', [ForumThreadController::class, 'update'])->name('forum.update');
    Route::post('/courses/{course:slug}/forum/{thread}/pin', [ForumThreadController::class, 'togglePin'])->name('forum.togglePin');
    Route::post('/courses/{course:slug}/forum/{thread}/lock', [ForumThreadController::class, 'toggleLock'])->name('forum.toggleLock');
    Route::delete('/courses/{course:slug}/forum/{thread}', [ForumThreadController::class, 'destroy'])->name('forum.destroy');
    Route::post('/courses/{course:slug}/forum/{thread}/replies', [ForumReplyController::class, 'store'])->name('forum.replies.store');
    Route::delete('/courses/{course:slug}/forum/{thread}/replies/{reply}', [ForumReplyController::class, 'destroy'])->name('forum.replies.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Payment reports
    Route::get('/payments', PaymentReportsController::class)->name('payments');

    // Instructor management
    Route::get('/instructors', [AdminInstructorController::class, 'index'])->name('instructors.index');
    Route::get('/instructors/applications', [AdminInstructorController::class, 'applications'])->name('instructors.applications');
    Route::post('/instructors/applications/{application}/approve', [AdminInstructorController::class, 'approve'])->name('instructors.approve');
    Route::post('/instructors/applications/{application}/reject', [AdminInstructorController::class, 'reject'])->name('instructors.reject');
    Route::get('/instructors/{user}/edit', [AdminInstructorController::class, 'edit'])->name('instructors.edit');
    Route::put('/instructors/{user}', [AdminInstructorController::class, 'update'])->name('instructors.update');

    // Student management
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{user}', [AdminStudentController::class, 'show'])->name('students.show');

    // Course management
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [AdminCourseController::class, 'show'])->name('courses.show');

    // Content (home page hero, How It Works, branding)
    Route::get('/content', [AdminContentController::class, 'index'])->name('content.index');
    Route::post('/content/branding', [AdminContentController::class, 'updateBranding'])->name('content.branding.update');
    Route::post('/content/hero', [AdminContentController::class, 'updateHero'])->name('content.hero.update');
    Route::put('/content/how-it-works', [AdminContentController::class, 'updateHowItWorks'])->name('content.how-it-works.update');

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/stripe', [AdminSettingsController::class, 'updateStripe'])->name('settings.stripe.update');
    Route::put('/settings/payments', [AdminSettingsController::class, 'updatePayments'])->name('settings.payments.update');

    // Blog management
    Route::resource('blog', AdminBlogController::class)->except(['show'])->parameters(['blog' => 'post']);
    Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

    // Review management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggleApproval');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Forum moderation
    Route::get('/forums', [AdminForumController::class, 'index'])->name('forums.index');
    Route::post('/forums/{thread}/pin', [AdminForumController::class, 'togglePin'])->name('forums.togglePin');
    Route::post('/forums/{thread}/lock', [AdminForumController::class, 'toggleLock'])->name('forums.toggleLock');
    Route::delete('/forums/{thread}', [AdminForumController::class, 'destroy'])->name('forums.destroy');
    Route::post('/forums/replies/{reply}/approve', [AdminForumController::class, 'toggleReplyApproval'])->name('forums.replies.toggleApproval');
});

// Manager routes (admin OR manager)
Route::middleware(['auth', 'verified', 'role:admin|manager'])->prefix('admin')->name('manager.')->group(function () {
    // Manager controllers will be registered here in later modules
});

// Instructor routes
Route::middleware(['auth', 'verified', 'role:admin|instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/', InstructorDashboardController::class)->name('dashboard');
    Route::get('/earnings', EarningsController::class)->name('earnings');

    // Course CRUD
    Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');

    // Course publish
    Route::post('/courses/{course}/publish', [InstructorCourseController::class, 'publish'])->name('courses.publish');

    // Sections
    Route::post('/courses/{course}/sections', [InstructorCourseController::class, 'addSection'])->name('courses.sections.store');
    Route::put('/courses/{course}/sections/{section}', [InstructorCourseController::class, 'updateSection'])->name('courses.sections.update');
    Route::delete('/courses/{course}/sections/{section}', [InstructorCourseController::class, 'deleteSection'])->name('courses.sections.destroy');

    // Lessons
    Route::post('/courses/{course}/sections/{section}/lessons', [InstructorCourseController::class, 'addLesson'])->name('courses.sections.lessons.store');
    Route::put('/courses/{course}/lessons/{lesson}', [InstructorCourseController::class, 'updateLesson'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [InstructorCourseController::class, 'deleteLesson'])->name('courses.lessons.destroy');

    // Reordering
    Route::post('/courses/{course}/sections/reorder', [InstructorCourseController::class, 'reorderSections'])->name('courses.sections.reorder');
    Route::post('/courses/{course}/sections/{section}/lessons/reorder', [InstructorCourseController::class, 'reorderLessons'])->name('courses.sections.lessons.reorder');
});

// Stripe webhook (CSRF excluded in bootstrap/app.php)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

require __DIR__.'/auth.php';
