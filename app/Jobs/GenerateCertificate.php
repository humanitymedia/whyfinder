<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class GenerateCertificate implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public Course $course,
    ) {}

    public function handle(): void
    {
        // Idempotent — skip if certificate already exists
        if (Certificate::where('user_id', $this->user->id)->where('course_id', $this->course->id)->exists()) {
            return;
        }

        // Generate unique certificate number
        do {
            $number = 'WF-' . strtoupper(Str::random(8));
        } while (Certificate::where('certificate_number', $number)->exists());

        Certificate::create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'certificate_number' => $number,
            'issued_at' => now(),
        ]);
    }
}
