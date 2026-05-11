<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::where('status', 'published')->first();

        if (! $course) {
            $this->command->warn('No published course found — skipping ForumSeeder.');

            return;
        }

        $admin = User::role('admin')->first();
        $instructor = $course->instructor;

        // Use the admin as fallback author
        $author = $admin ?? $instructor ?? User::first();

        if (! $author) {
            $this->command->warn('No users found — skipping ForumSeeder.');

            return;
        }

        $firstLesson = $course->lessons()->first();

        // Thread 1 — pinned welcome thread
        $thread1 = ForumThread::firstOrCreate(
            ['course_id' => $course->id, 'slug' => 'welcome-introduce-yourself'],
            [
                'user_id' => $instructor->id ?? $author->id,
                'title' => 'Welcome! Introduce yourself here',
                'body' => "Welcome to the discussion forum for **{$course->title}**!\n\nThis is a space to ask questions, share insights, and connect with fellow learners. Feel free to introduce yourself and let us know what brought you here.\n\nDon't hesitate to start new threads for specific questions about the course material.",
                'is_pinned' => true,
                'is_locked' => false,
            ]
        );

        // Thread 2 — general question
        $thread2 = ForumThread::firstOrCreate(
            ['course_id' => $course->id, 'slug' => 'how-do-you-stay-motivated'],
            [
                'user_id' => $author->id,
                'title' => 'How do you stay motivated when progress feels slow?',
                'body' => "I've been working through the course and finding it incredibly valuable. But some days it's hard to stay motivated when it feels like progress is slow.\n\nWhat strategies do you use to keep going when things get tough? Would love to hear from others in the community.",
                'is_pinned' => false,
                'is_locked' => false,
            ]
        );

        // Thread 3 — lesson-specific, locked
        $thread3 = ForumThread::firstOrCreate(
            ['course_id' => $course->id, 'slug' => 'great-resources-for-further-reading'],
            [
                'user_id' => $author->id,
                'lesson_id' => $firstLesson?->id,
                'title' => 'Great resources for further reading',
                'body' => "I wanted to share some additional resources that complement the course material.\n\nIf you've come across any books, articles, or podcasts that relate to this topic, share them below! This thread is now locked as a curated resource list.",
                'is_pinned' => false,
                'is_locked' => true,
            ]
        );

        // Thread 4 — regular thread
        $thread4 = ForumThread::firstOrCreate(
            ['course_id' => $course->id, 'slug' => 'applying-concepts-to-real-life'],
            [
                'user_id' => $author->id,
                'title' => 'Applying these concepts to real life — share your wins!',
                'body' => "One thing I love about this course is how practical the content is. I've already started applying some of the ideas in my daily routine.\n\nAnyone else had some early wins they'd like to share? Let's celebrate progress, no matter how small!",
                'is_pinned' => false,
                'is_locked' => false,
            ]
        );

        // Replies on thread 1 (welcome)
        ForumReply::firstOrCreate(
            ['thread_id' => $thread1->id, 'user_id' => $author->id, 'body' => "Hi everyone! I'm excited to be here. I've been looking for a course like this for a while and I'm already learning so much. Looking forward to connecting with all of you!"],
            ['is_approved' => true]
        );

        // Replies on thread 2 (motivation)
        $reply2a = ForumReply::firstOrCreate(
            ['thread_id' => $thread2->id, 'user_id' => $author->id, 'body' => "Great question! I find that setting small daily goals helps a lot. Instead of focusing on the big picture, I break things down into tiny steps. Even 15 minutes of practice makes a difference."],
            ['is_approved' => true]
        );

        if ($instructor && $instructor->id !== $author->id) {
            ForumReply::firstOrCreate(
                ['thread_id' => $thread2->id, 'user_id' => $instructor->id, 'body' => "Love this discussion! As someone who's been teaching this material for years, I can say that consistency beats intensity every time. Show up every day, even if it's just for a few minutes. The compound effect is real.\n\nAlso, don't compare your chapter 1 to someone else's chapter 20."],
                ['is_approved' => true]
            );
        }

        // Nested reply (child of reply2a)
        ForumReply::firstOrCreate(
            ['thread_id' => $thread2->id, 'user_id' => $author->id, 'parent_id' => $reply2a->id, 'body' => "Totally agree with the small goals approach! I started a habit tracker and it's been a game changer for staying consistent."],
            ['is_approved' => true]
        );

        // Reply on thread 4
        ForumReply::firstOrCreate(
            ['thread_id' => $thread4->id, 'user_id' => $author->id, 'body' => "Just had my first small win! I used the framework from Module 1 in a conversation at work and it went really well. Baby steps, but it felt great to see it work in practice."],
            ['is_approved' => true]
        );

        $this->command->info('ForumSeeder: Created threads and replies for "' . $course->title . '"');
    }
}
