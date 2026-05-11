<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CategoriesSeeder::class);

        // Create a test admin user
        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Create a test student user
        $student = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);
        $student->assignRole('student');

        // Create a test instructor user
        $instructor = User::factory()->create([
            'name' => 'Test Instructor',
            'email' => 'instructor@example.com',
        ]);
        $instructor->assignRole('instructor');

        // Create a test manager user
        $manager = User::factory()->create([
            'name' => 'Test Manager',
            'email' => 'manager@example.com',
        ]);
        $manager->assignRole('manager');
    }
}
