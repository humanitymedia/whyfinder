<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::where('email', 'bear@humanitymedia.net')->first();

        if (! $instructor) {
            $this->command->warn('User bear@humanitymedia.net not found — skipping CourseSeeder.');
            return;
        }

        // Ensure "Marketing & Branding" category exists
        $marketingCategory = Category::firstOrCreate(
            ['slug' => 'marketing-branding'],
            ['name' => 'Marketing & Branding', 'description' => 'Master marketing, branding, and digital presence']
        );

        $purposeCategory = Category::where('slug', 'purpose-self-discovery')->first();
        $businessCategory = Category::where('slug', 'business-entrepreneurship')->first();

        if (! $purposeCategory || ! $businessCategory) {
            $this->command->warn('Required categories not found — run CategoriesSeeder first.');
            return;
        }

        $courses = [
            [
                'title' => 'Find Your Why',
                'short_description' => 'A guided journey to uncover your purpose, clarify your direction, and build a life that actually means something to you.',
                'description' => "Most people spend years chasing goals that don't fulfill them. This course helps you stop guessing and start living with intention.\n\nYou'll work through proven exercises to identify your core values, recognize your natural strengths, and define the unique contribution only you can make. By the end, you'll have a personal purpose statement and a clear direction for your next chapter.",
                'price' => 0,
                'is_free' => true,
                'difficulty_level' => 'beginner',
                'duration_hours' => 4.0,
                'category_id' => $purposeCategory->id,
                'is_featured' => true,
                'sections' => [
                    [
                        'title' => 'Getting Started',
                        'lessons' => [
                            ['title' => 'Why Most People Feel Lost', 'type' => 'video', 'video_duration' => 720, 'is_free_preview' => true],
                            ['title' => 'The Purpose Framework', 'type' => 'video', 'video_duration' => 900],
                        ],
                    ],
                    [
                        'title' => 'Self-Discovery',
                        'lessons' => [
                            ['title' => 'Identifying Your Core Values', 'type' => 'video', 'video_duration' => 1080],
                            ['title' => 'Mapping Your Strengths', 'type' => 'video', 'video_duration' => 960],
                        ],
                    ],
                    [
                        'title' => 'Finding Direction',
                        'lessons' => [
                            ['title' => 'From Values to Vision', 'type' => 'video', 'video_duration' => 840],
                            ['title' => 'Writing Your Purpose Statement', 'type' => 'video', 'video_duration' => 1020],
                        ],
                    ],
                    [
                        'title' => 'Taking Action',
                        'lessons' => [
                            ['title' => 'Building Your Action Plan', 'type' => 'video', 'video_duration' => 900],
                            ['title' => 'Staying on Track', 'type' => 'video', 'video_duration' => 780],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Build a Top Shelf Website for Under $500',
                'short_description' => 'Learn to create a professional, conversion-ready website without hiring a developer or breaking the bank.',
                'description' => "You don't need to spend thousands on a web developer or settle for a cookie-cutter template. This course teaches you how to build a polished, professional website that represents your brand and converts visitors into customers.\n\nFrom choosing the right platform to designing pages that work, you'll learn practical skills that save you money and give you full control over your online presence.",
                'price' => 79.00,
                'is_free' => false,
                'difficulty_level' => 'beginner',
                'duration_hours' => 6.0,
                'category_id' => $businessCategory->id,
                'is_featured' => false,
                'sections' => [
                    [
                        'title' => 'Planning Your Site',
                        'lessons' => [
                            ['title' => 'Choosing the Right Platform', 'type' => 'video', 'video_duration' => 1200, 'is_free_preview' => true],
                            ['title' => 'Planning Your Site Structure', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                    [
                        'title' => 'Design & Build',
                        'lessons' => [
                            ['title' => 'Design Principles That Convert', 'type' => 'video', 'video_duration' => 1320],
                            ['title' => 'Building Your Core Pages', 'type' => 'video', 'video_duration' => 1500],
                        ],
                    ],
                    [
                        'title' => 'Content & SEO',
                        'lessons' => [
                            ['title' => 'Writing Copy That Connects', 'type' => 'video', 'video_duration' => 1080],
                            ['title' => 'SEO Basics for Small Business', 'type' => 'video', 'video_duration' => 1200],
                        ],
                    ],
                    [
                        'title' => 'Launch & Optimize',
                        'lessons' => [
                            ['title' => 'Launch Checklist', 'type' => 'video', 'video_duration' => 900],
                            ['title' => 'Analytics and Optimization', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Brand Yourself Without Looking AI-Built',
                'short_description' => 'Build an authentic personal brand that feels human, stands out, and attracts the right audience in an AI-saturated world.',
                'description' => "Everyone is using AI to build their brand now, and everything is starting to look and sound the same. This course teaches you how to stand out by being authentically you.\n\nYou'll learn how to define your brand voice, create visual identity that feels human, and build a presence that attracts your ideal audience — without looking like every other AI-generated brand out there.",
                'price' => 89.00,
                'is_free' => false,
                'difficulty_level' => 'intermediate',
                'duration_hours' => 5.0,
                'category_id' => $marketingCategory->id,
                'is_featured' => false,
                'sections' => [
                    [
                        'title' => 'Brand Foundation',
                        'lessons' => [
                            ['title' => 'The Authenticity Advantage', 'type' => 'video', 'video_duration' => 1080, 'is_free_preview' => true],
                            ['title' => 'Defining Your Brand Voice', 'type' => 'video', 'video_duration' => 1200],
                        ],
                    ],
                    [
                        'title' => 'Visual Identity',
                        'lessons' => [
                            ['title' => 'Visual Identity That Feels Human', 'type' => 'video', 'video_duration' => 1320],
                            ['title' => 'Content That Connects', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                    [
                        'title' => 'Building Presence',
                        'lessons' => [
                            ['title' => 'Building Your Online Presence', 'type' => 'video', 'video_duration' => 1200],
                            ['title' => 'Standing Out in the AI Age', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Social Media That Actually Works',
                'short_description' => 'Stop posting into the void. Learn a practical social media strategy that builds real engagement and drives actual results.',
                'description' => "Tired of posting consistently and getting crickets? This course cuts through the noise and teaches you what actually works on social media in today's landscape.\n\nYou'll learn how to create content that resonates, build genuine engagement, and turn followers into customers — without spending all day on your phone or burning out.",
                'price' => 69.00,
                'is_free' => false,
                'difficulty_level' => 'beginner',
                'duration_hours' => 4.5,
                'category_id' => $marketingCategory->id,
                'is_featured' => false,
                'sections' => [
                    [
                        'title' => 'Strategy & Planning',
                        'lessons' => [
                            ['title' => 'Why Most Social Media Fails', 'type' => 'video', 'video_duration' => 960, 'is_free_preview' => true],
                            ['title' => 'Building Your Content Strategy', 'type' => 'video', 'video_duration' => 1200],
                        ],
                    ],
                    [
                        'title' => 'Content Creation',
                        'lessons' => [
                            ['title' => 'Creating Content That Resonates', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                    [
                        'title' => 'Growth & Engagement',
                        'lessons' => [
                            ['title' => 'Engagement That Builds Community', 'type' => 'video', 'video_duration' => 960],
                            ['title' => 'Converting Followers to Customers', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'From Side Hustle to Full-Time Freedom',
                'short_description' => 'A step-by-step roadmap to turn your side project into a sustainable full-time business you actually love.',
                'description' => "You've got the side hustle going, but making the leap to full-time feels terrifying. This course gives you the roadmap, the math, and the mindset to make the transition with confidence.\n\nFrom validating your business model to building financial runway, you'll learn exactly what it takes to go from side income to full-time freedom — without the reckless leap.",
                'price' => 99.00,
                'is_free' => false,
                'difficulty_level' => 'intermediate',
                'duration_hours' => 7.0,
                'category_id' => $businessCategory->id,
                'is_featured' => true,
                'sections' => [
                    [
                        'title' => 'Foundation',
                        'lessons' => [
                            ['title' => 'Assessing Your Side Hustle Potential', 'type' => 'video', 'video_duration' => 1200, 'is_free_preview' => true],
                            ['title' => 'Validating Your Business Model', 'type' => 'video', 'video_duration' => 1500],
                        ],
                    ],
                    [
                        'title' => 'Financial Planning',
                        'lessons' => [
                            ['title' => 'Building Your Financial Runway', 'type' => 'video', 'video_duration' => 1320],
                            ['title' => 'Pricing for Profitability', 'type' => 'video', 'video_duration' => 1200],
                        ],
                    ],
                    [
                        'title' => 'Making the Leap',
                        'lessons' => [
                            ['title' => 'Systems That Scale', 'type' => 'video', 'video_duration' => 1080],
                            ['title' => 'Making the Leap', 'type' => 'video', 'video_duration' => 1200],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Email Marketing for Real People',
                'short_description' => 'Learn how to build an email list and write emails people actually want to read — no sleazy tactics required.',
                'description' => "Email marketing has the highest ROI of any marketing channel, but most people do it wrong. This course teaches you how to build a genuine email list and write emails that people actually look forward to opening.\n\nFrom choosing your platform to crafting sequences that convert, you'll learn a human-first approach to email marketing that builds trust and drives sales.",
                'price' => 59.00,
                'is_free' => false,
                'difficulty_level' => 'beginner',
                'duration_hours' => 3.5,
                'category_id' => $marketingCategory->id,
                'is_featured' => false,
                'sections' => [
                    [
                        'title' => 'Getting Started',
                        'lessons' => [
                            ['title' => 'Why Email Still Wins', 'type' => 'video', 'video_duration' => 840, 'is_free_preview' => true],
                            ['title' => 'Building Your List From Scratch', 'type' => 'video', 'video_duration' => 1080],
                        ],
                    ],
                    [
                        'title' => 'Writing Great Emails',
                        'lessons' => [
                            ['title' => 'Writing Emails People Want to Read', 'type' => 'video', 'video_duration' => 1200],
                        ],
                    ],
                    [
                        'title' => 'Sequences & Automation',
                        'lessons' => [
                            ['title' => 'Email Sequences That Convert', 'type' => 'video', 'video_duration' => 1080],
                            ['title' => 'Automation and Growth', 'type' => 'video', 'video_duration' => 960],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $sections = $courseData['sections'];
            unset($courseData['sections']);

            // Skip if course already exists
            if (Course::where('title', $courseData['title'])->exists()) {
                $this->command->info("Skipping \"{$courseData['title']}\" — already exists.");
                continue;
            }

            $course = Course::create([
                ...$courseData,
                'instructor_id' => $instructor->id,
                'status' => 'published',
                'is_published' => true,
            ]);

            foreach ($sections as $sIdx => $sectionData) {
                $lessons = $sectionData['lessons'];

                $section = CourseSection::create([
                    'course_id' => $course->id,
                    'title' => $sectionData['title'],
                    'sort_order' => $sIdx + 1,
                ]);

                foreach ($lessons as $lIdx => $lessonData) {
                    Lesson::create([
                        'section_id' => $section->id,
                        'title' => $lessonData['title'],
                        'type' => $lessonData['type'],
                        'video_duration' => $lessonData['video_duration'] ?? null,
                        'is_free_preview' => $lessonData['is_free_preview'] ?? false,
                        'sort_order' => $lIdx + 1,
                    ]);
                }
            }

            $this->command->info("Created course: \"{$course->title}\" with " . collect($sections)->sum(fn ($s) => count($s['lessons'])) . ' lessons.');
        }
    }
}
