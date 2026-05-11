<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Users --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">{{ number_format($stats['total_users']) }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Users</p>
        </div>

        {{-- Total Courses --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">{{ $stats['total_courses'] }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Courses</p>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['total_revenue'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Revenue</p>
        </div>

        {{-- Pending Applications --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">{{ $pendingApplications }}</p>
            <p class="text-sm text-brand-gray mt-1">Pending Applications</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- Users by Role --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-brand-dark mb-4">Users by Role</h3>
            <div class="space-y-3">
                @foreach(['admin', 'manager', 'instructor', 'student'] as $role)
                    @php $count = $roleCounts[$role] ?? 0; $total = max($stats['total_users'], 1); @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-brand-dark capitalize">{{ $role }}</span>
                            <span class="text-sm text-brand-gray">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full
                                @if($role === 'admin') bg-red-500
                                @elseif($role === 'manager') bg-purple-500
                                @elseif($role === 'instructor') bg-blue-500
                                @else bg-green-500
                                @endif"
                                style="width: {{ ($count / $total) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-brand-dark">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-brand-red hover:text-red-700 transition-colors">View all</a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-brand-blush flex items-center justify-center text-xs font-bold text-brand-brown shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-brand-dark truncate">{{ $user->name }}</p>
                            <p class="text-xs text-brand-gray truncate">{{ $user->email }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full shrink-0
                            @if($user->roles->first()?->name === 'admin') bg-red-100 text-red-700
                            @elseif($user->roles->first()?->name === 'manager') bg-purple-100 text-purple-700
                            @elseif($user->roles->first()?->name === 'instructor') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700
                            @endif">
                            {{ ucfirst($user->roles->first()?->name ?? 'none') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
