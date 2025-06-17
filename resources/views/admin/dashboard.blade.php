@extends('layouts.app')

@section('content')
<div class="space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <!-- Dashboard Tabs -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4">
            <div class="flex space-x-6 border-b border-gray-700 pb-2 mb-6">
                <a href="{{ route('user.dashboard') }}"
                   class="{{ request()->routeIs('user.dashboard') ? 'font-bold border-b-2 border-blue-400 text-blue-400' : 'text-gray-400 hover:text-gray-200' }} transition-colors">
                    <i class="fas fa-user mr-2"></i>User Dashboard
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'font-bold border-b-2 border-red-400 text-red-400' : 'text-gray-400 hover:text-gray-200' }} transition-colors">
                        <i class="fas fa-crown mr-2"></i>Admin Dashboard
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Admin Dashboard -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">
                        <i class="fas fa-shield-alt text-red-400 mr-3"></i>Admin Dashboard
                    </h1>
                    <p class="text-gray-400">Manage and monitor your forum community</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-900 text-red-200 border border-red-700">
                        <i class="fas fa-crown mr-1"></i>Administrator
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-750 border border-blue-600 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-700 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-blue-200 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Total Users</p>
                            <p class="text-2xl font-bold text-blue-400">{{ $stats['total_users'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Registered members</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-750 border border-green-600 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-700 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-comments text-green-200 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Total Topics</p>
                            <p class="text-2xl font-bold text-green-400">{{ $stats['total_topics'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Discussions created</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-750 border border-yellow-600 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-700 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-reply text-yellow-200 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Total Replies</p>
                            <p class="text-2xl font-bold text-yellow-400">{{ $stats['total_replies'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Messages posted</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-750 border border-purple-600 rounded-lg p-4 hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-700 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-user-check text-purple-200 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Active Users (7d)</p>
                            <p class="text-2xl font-bold text-purple-400">{{ $stats['active_users'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Weekly activity</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-100">
                            <i class="fas fa-user-plus text-blue-400 mr-2"></i>Recent Users
                        </h3>
                        <a href="{{ route('admin.users') }}" class="text-blue-400 hover:text-blue-300 text-sm transition-colors">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recent_users as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg border border-gray-600 hover:bg-gray-650 transition-colors">
                                <div class="flex items-center space-x-3">
                                    @if($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-gray-500">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm border-2 border-gray-500">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-200">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $user->email }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full font-medium {{ $user->isAdmin() ? 'bg-red-900 text-red-200 border border-red-700' : 'bg-blue-900 text-blue-200 border border-blue-700' }}">
                                    @if($user->isAdmin())
                                        <i class="fas fa-crown mr-1"></i>Admin
                                    @else
                                        <i class="fas fa-user mr-1"></i>Member
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Topics -->
                <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-100">
                            <i class="fas fa-comment-dots text-green-400 mr-2"></i>Recent Topics
                        </h3>
                        <a href="{{ route('forum.index') }}" class="text-green-400 hover:text-green-300 text-sm transition-colors">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recent_topics as $topic)
                            <div class="p-3 bg-gray-700 rounded-lg border border-gray-600 hover:bg-gray-650 transition-colors">
                                <div class="flex items-start space-x-3">
                                    @if($topic->user->profile_picture)
                                        <img src="{{ Storage::url($topic->user->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-500 flex-shrink-0 mt-1">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs border border-gray-500 flex-shrink-0 mt-1">
                                            {{ strtoupper(substr($topic->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('topic.show', $topic) }}" class="font-medium text-gray-200 hover:text-blue-400 transition-colors block truncate">
                                            {{ Str::limit($topic->title, 40) }}
                                        </a>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-sm text-gray-400">by</span>
                                            <span class="text-sm text-gray-300 font-medium">{{ $topic->user->name }}</span>
                                            @if($topic->user->isAdmin())
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-200 border border-red-700">
                                                    <i class="fas fa-crown mr-1"></i>Admin
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                            <span>{{ $topic->created_at->diffForHumans() }}</span>
                                            <span class="flex items-center">
                                                <i class="fas fa-reply mr-1"></i>{{ $topic->replies()->count() }} replies
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye mr-1"></i>{{ $topic->views ?? 0 }} views
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-gray-750 border border-gray-600 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-100 mb-4">
                    <i class="fas fa-bolt text-yellow-400 mr-2"></i>Quick Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.users') }}" class="flex items-center justify-center p-4 bg-blue-700 hover:bg-blue-600 rounded-lg transition-colors border border-blue-600">
                        <div class="text-center">
                            <i class="fas fa-users text-blue-200 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-blue-100">Manage Users</div>
                        </div>
                    </a>
                    <a href="{{ route('forum.index') }}" class="flex items-center justify-center p-4 bg-green-700 hover:bg-green-600 rounded-lg transition-colors border border-green-600">
                        <div class="text-center">
                            <i class="fas fa-comments text-green-200 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-green-100">View Topics</div>
                        </div>
                    </a>
                    <a href="{{ route('topic.create') }}" class="flex items-center justify-center p-4 bg-purple-700 hover:bg-purple-600 rounded-lg transition-colors border border-purple-600">
                        <div class="text-center">
                            <i class="fas fa-plus text-purple-200 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-purple-100">Create Topic</div>
                        </div>
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center justify-center p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors border border-gray-600">
                        <div class="text-center">
                            <i class="fas fa-cog text-gray-200 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-gray-100">Settings</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gray-750 {
    background-color: #374151;
}

.hover\:bg-gray-650:hover {
    background-color: #4b5563;
}

.bg-gray-650 {
    background-color: #4b5563;
}
</style>
@endsection
