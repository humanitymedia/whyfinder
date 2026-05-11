<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::where('is_published', true)->first();

        if (! $course) {
            $this->command->warn('No published course found — skipping ReviewSeeder.');
            return;
        }

        // Use the admin user as reviewer (they exist in every environment)
        $admin = User::where('email', 'bear@humanitymedia.net')->first()
              ?? User::role('admin')->first();

        if (! $admin) {
            $this->command->warn('No admin user found — skipping ReviewSeeder.');
            return;
        }

        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Excellent course! The lessons were well-structured and easy to follow. I learned so much about the subject.',
                'is_approved' => true,
            ],
            [
                'rating' => 4,
                'comment' => 'Really enjoyed this course. Great content and the instructor explained concepts clearly.',
                'is_approved' => true,
            ],
            [
                'rating' => 5,
                'comment' => null,
                'is_approved' => true,
            ],
            [
                'rating' => 3,
                'comment' => 'Good course overall, but some sections could use more detail. Would still recommend it.',
                'is_approved' => true,
            ],
        ];

        foreach ($reviews as $data) {
            Review::firstOrCreate(
                [
                    'user_id' => $admin->id,
                    'course_id' => $course->id,
                    'rating' => $data['rating'],
                ],
                [
                    'comment' => $data['comment'],
                    'is_approved' => $data['is_approved'],
                ]
            );
        }

        $this->command->info("Seeded reviews for course: {$course->title}");
    }
}
