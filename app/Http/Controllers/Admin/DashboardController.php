<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'recent_enrollments' => Enrollment::count(),
        ];

        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->pluck('count', 'name');

        $recentUsers = User::latest()->take(5)->get();

        $pendingApplications = DB::table('instructor_applications')
            ->where('status', 'pending')
            ->count();

        return view('admin.dashboard', compact('stats', 'roleCounts', 'recentUsers', 'pendingApplications'));
    }
}
