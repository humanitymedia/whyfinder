<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InstructorApplicationController extends Controller
{
    public function create(): View
    {
        return view('teach');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'area_of_expertise' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:5000'],
            'website_url' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        DB::table('instructor_applications')->insert(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return back()->with('success', 'Your application has been submitted! We\'ll review it and get back to you soon.');
    }
}
