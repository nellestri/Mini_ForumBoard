@extends('layouts.app')

@section('content')
<div class="space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">
                        <i class="fas fa-users-cog text-red-400 mr-3"></i>User Management
                    </h1>
                    <p class="text-gray-400">Manage all registered users and their permissions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-400">
                        <span class="font-medium text-gray-300">{{ $users->total() }}</span> total users
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Admin Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="px-6 py-4 border-b border-gray-700 bg-gray-750">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-lg font-semibold text-blue-400">{{ $users->where('role', 'user')->count() }}</div>
                    <div class="text-xs text-gray-400">Members</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-red-400">{{ $users->where('role', 'admin')->count() }}</div>
                    <div class="text-xs text-gray-400">Admins</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-green-400">{{ $users->where('last_active', '>=', now()->subWeek())->count() }}</div>
                    <div class="text-xs text-gray-400">Active (7d)</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-purple-400">{{ $users->where('created_at', '>=', now()->subMonth())->count() }}</div>
                    <div class="text-xs text-gray-400">New (30d)</div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-750">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-shield-alt mr-2"></i>Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-chart-bar mr-2"></i>Activity
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-calendar-plus mr-2"></i>Joined
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-clock mr-2"></i>Last Active
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        @if($user->profile_picture)
                                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile" class="h-12 w-12 rounded-full object-cover border-2 border-gray-600">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold border-2 border-gray-600">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="text-sm font-medium text-gray-100">{{ $user->name }}</div>
                                            @if($user->id === Auth::id())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200 border border-blue-700">
                                                    <i class="fas fa-user-circle mr-1"></i>You
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-400">{{ $user->email }}</div>
                                        @if($user->bio)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($user->bio, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->isAdmin() ? 'bg-red-900 text-red-200 border border-red-700' : 'bg-blue-900 text-blue-200 border border-blue-700' }}">
                                    @if($user->isAdmin())
                                        <i class="fas fa-crown mr-1"></i>Admin
                                    @else
                                        <i class="fas fa-user mr-1"></i>Member
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                <div class="space-y-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-comments text-purple-400 mr-2 w-4"></i>
                                        <span class="text-purple-400 font-medium">{{ $user->topics_count ?? $user->topics()->count() }}</span>
                                        <span class="ml-1">topics</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-reply text-green-400 mr-2 w-4"></i>
                                        <span class="text-green-400 font-medium">{{ $user->replies_count ?? $user->replies()->count() }}</span>
                                        <span class="ml-1">replies</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                <div>{{ $user->created_at->format('M j, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->last_active)
                                    @php
                                        $lastActive = $user->last_active;
                                        $isRecent = $lastActive->diffInDays() < 7;
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-2 {{ $isRecent ? 'bg-green-400' : 'bg-gray-500' }}"></div>
                                        <div class="{{ $isRecent ? 'text-green-400' : 'text-gray-400' }}">
                                            {{ $lastActive->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $lastActive->format('M j, Y g:i A') }}</div>
                                @else
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-2 bg-red-500"></div>
                                        <div class="text-red-400">Never</div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($user->id !== Auth::id())
                                    <div class="flex items-center space-x-3">
                                        <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium transition-colors {{ $user->isAdmin() ? 'bg-red-700 text-red-100 hover:bg-red-600 border border-red-600' : 'bg-green-700 text-green-100 hover:bg-green-600 border border-green-600' }}" onclick="return confirm('Are you sure you want to {{ $user->isAdmin() ? 'remove admin privileges from' : 'grant admin privileges to' }} {{ $user->name }}?')">
                                                @if($user->isAdmin())
                                                    <i class="fas fa-user-minus mr-1"></i>Remove Admin
                                                @else
                                                    <i class="fas fa-user-shield mr-1"></i>Make Admin
                                                @endif
                                            </button>
                                        </form>

                                        <a href="{{ route('user.profile', $user) }}" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-blue-700 text-blue-100 hover:bg-blue-600 border border-blue-600 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>View Profile
                                        </a>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                        <i class="fas fa-user-circle mr-1"></i>Current User
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                <div class="pagination-dark">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- User Management Actions -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-tools text-yellow-400 mr-2"></i>Management Tools
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-750 border border-blue-600 rounded-lg p-4 text-center">
                    <i class="fas fa-user-plus text-blue-400 text-2xl mb-2"></i>
                    <h4 class="font-medium text-gray-200 mb-2">Invite Users</h4>
                    <p class="text-sm text-gray-400 mb-3">Send invitations to new users</p>
                    <button class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors border border-blue-600 text-sm" onclick="alert('Feature coming soon!')">
                        <i class="fas fa-envelope mr-1"></i>Send Invites
                    </button>
                </div>

                <div class="bg-gray-750 border border-green-600 rounded-lg p-4 text-center">
                    <i class="fas fa-download text-green-400 text-2xl mb-2"></i>
                    <h4 class="font-medium text-gray-200 mb-2">Export Users</h4>
                    <p class="text-sm text-gray-400 mb-3">Download user data as CSV</p>
                    <button class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors border border-green-600 text-sm" onclick="alert('Feature coming soon!')">
                        <i class="fas fa-file-csv mr-1"></i>Export CSV
                    </button>
                </div>

                <div class="bg-gray-750 border border-purple-600 rounded-lg p-4 text-center">
                    <i class="fas fa-chart-line text-purple-400 text-2xl mb-2"></i>
                    <h4 class="font-medium text-gray-200 mb-2">User Analytics</h4>
                    <p class="text-sm text-gray-400 mb-3">View detailed user statistics</p>
                    <button class="bg-purple-700 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition-colors border border-purple-600 text-sm" onclick="alert('Feature coming soon!')">
                        <i class="fas fa-analytics mr-1"></i>View Analytics
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gray-750 {
    background-color: #374151;
}

.hover\:bg-gray-750:hover {
    background-color: #374151;
}

/* Dark mode pagination styling */
.pagination-dark .pagination {
    background: transparent;
}

.pagination-dark .page-link {
    background-color: #374151;
    border-color: #4b5563;
    color: #d1d5db;
}

.pagination-dark .page-link:hover {
    background-color: #4b5563;
    border-color: #6b7280;
    color: #f3f4f6;
}

.pagination-dark .page-item.active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.pagination-dark .page-item.disabled .page-link {
    background-color: #1f2937;
    border-color: #374151;
    color: #6b7280;
}
</style>
@endsection
