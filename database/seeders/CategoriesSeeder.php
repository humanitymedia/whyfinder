<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Purpose & Self-Discovery', 'description' => 'Find your calling and understand your unique strengths'],
            ['name' => 'Business & Entrepreneurship', 'description' => 'Build and grow a purpose-driven business'],
            ['name' => 'Personal Development', 'description' => 'Develop habits, mindset, and skills for growth'],
            ['name' => 'Creativity & Expression', 'description' => 'Unlock your creative potential and share your voice'],
            ['name' => 'Health & Wellness', 'description' => 'Nurture your body, mind, and spirit'],
            ['name' => 'Leadership & Impact', 'description' => 'Lead with purpose and create lasting change'],
            ['name' => 'Finance & Wealth', 'description' => 'Build financial freedom aligned with your values'],
            ['name' => 'Relationships & Community', 'description' => 'Deepen connections and build meaningful relationships'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
