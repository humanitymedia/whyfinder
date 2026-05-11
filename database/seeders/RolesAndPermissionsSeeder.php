<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions grouped by domain
        $permissions = [
            // User management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.assign-roles',

            // Course management
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.delete',
            'courses.publish',
            'courses.review',
            'courses.manage-all',

            // Lesson management
            'lessons.create',
            'lessons.edit',
            'lessons.delete',

            // Enrollment
            'enrollments.view',
            'enrollments.manage',

            // Reviews
            'reviews.create',
            'reviews.edit',
            'reviews.delete',
            'reviews.approve',

            // Blog
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
            'blog.publish',
            'blog.manage-all',

            // Forum
            'forums.participate',
            'forums.create-thread',
            'forums.moderate',
            'forums.moderate-all',
            'forums.pin-thread',
            'forums.lock-thread',

            // Payments & earnings
            'payments.view',
            'payments.manage',
            'earnings.view',
            'earnings.manage',

            // Instructor applications
            'applications.submit',
            'applications.review',

            // Site settings
            'settings.view',
            'settings.manage',

            // Dashboard access
            'dashboard.admin',
            'dashboard.instructor',
            'dashboard.student',

            // Certificates
            'certificates.view',
            'certificates.generate',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Admin — full platform control
        $admin = Role::findOrCreate('admin');
        $admin->givePermissionTo(Permission::all());

        // Manager/Moderator — content and community oversight
        $manager = Role::findOrCreate('manager');
        $manager->givePermissionTo([
            'courses.view',
            'courses.review',
            'courses.manage-all',
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
            'blog.publish',
            'blog.manage-all',
            'forums.participate',
            'forums.create-thread',
            'forums.moderate',
            'forums.moderate-all',
            'forums.pin-thread',
            'forums.lock-thread',
            'reviews.approve',
            'reviews.delete',
            'applications.review',
            'users.view',
            'enrollments.view',
            'certificates.view',
        ]);

        // Instructor — course creator and seller
        $instructor = Role::findOrCreate('instructor');
        $instructor->givePermissionTo([
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.publish',
            'lessons.create',
            'lessons.edit',
            'lessons.delete',
            'enrollments.view',
            'earnings.view',
            'forums.participate',
            'forums.create-thread',
            'forums.moderate',
            'forums.pin-thread',
            'forums.lock-thread',
            'blog.view',
            'blog.create',
            'blog.edit',
            'dashboard.instructor',
            'certificates.view',
        ]);

        // Student — course consumer
        $student = Role::findOrCreate('student');
        $student->givePermissionTo([
            'courses.view',
            'enrollments.view',
            'reviews.create',
            'forums.participate',
            'forums.create-thread',
            'blog.view',
            'applications.submit',
            'dashboard.student',
            'certificates.view',
            'certificates.generate',
        ]);
    }
}
