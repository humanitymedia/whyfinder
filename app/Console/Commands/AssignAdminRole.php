<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    protected $signature = 'whyfinder:assign-admin {email : The email address of the user}';

    protected $description = 'Assign the admin role to a user by email address';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found with email: {$email}");

            return Command::FAILURE;
        }

        if ($user->hasRole('admin')) {
            $this->info("{$user->name} already has the admin role.");

            return Command::SUCCESS;
        }

        $user->assignRole('admin');

        $this->info("Admin role assigned to {$user->name} ({$email}).");

        return Command::SUCCESS;
    }
}
