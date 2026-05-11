<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorApplication;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::role('instructor')->with('roles');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $instructors = $query->withCount('courses')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Calculate earnings for each instructor
        $instructors->getCollection()->transform(function ($instructor) {
            $instructor->total_earnings = $instructor->instructorEarnings()->sum('net_amount');
            $instructor->pending_earnings = $instructor->instructorEarnings()->where('status', 'pending')->sum('net_amount');

            return $instructor;
        });

        return view('admin.instructors.index', compact('instructors'));
    }

    public function applications(): View
    {
        $applications = InstructorApplication::with('user', 'reviewer')
            ->latest()
            ->paginate(15);

        return view('admin.instructors.applications', compact('applications'));
    }

    public function approve(InstructorApplication $application): RedirectResponse
    {
        $application->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Assign instructor role to the user
        if ($application->user) {
            $application->user->syncRoles(['instructor']);
        }

        return back()->with('success', "Application from {$application->first_name} {$application->last_name} has been approved.");
    }

    public function reject(InstructorApplication $application): RedirectResponse
    {
        $application->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', "Application from {$application->first_name} {$application->last_name} has been rejected.");
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $user->total_earnings = $user->instructorEarnings()->sum('net_amount');
        $user->courses_count = $user->courses()->count();
        $globalFee = \App\Models\Setting::get('platform_fee_percent', 30);

        return view('admin.instructors.edit', compact('user', 'globalFee'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'platform_fee_override' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'platform_fee_override' => $validated['platform_fee_override'] !== null && $validated['platform_fee_override'] !== ''
                ? $validated['platform_fee_override']
                : null,
        ]);

        return redirect()->route('admin.instructors.index')
            ->with('success', "Instructor {$user->name} updated successfully.");
    }
}
