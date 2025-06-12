@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-comments text-green-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600">Total Topics</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_topics'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-reply text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600">Total Replies</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_replies'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-user-check text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-600">Active Users (7d)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['active_users'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Recent Users</h3>
                    <div class="space-y-3">
                        @foreach($recent_users as $user)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded {{ $user->isAdmin() ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->role }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.users') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">View All Users →</a>
                </div>

                <!-- Recent Topics -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Recent Topics</h3>
                    <div class="space-y-3">
                        @foreach($recent_topics as $topic)
                            <div>
                                <a href="{{ route('topic.show', $topic) }}" class="font-medium text-blue-600 hover:text-blue-800">
                                    {{ Str::limit($topic->title, 40) }}
                                </a>
                                <p class="text-sm text-gray-600">by {{ $topic->user->name }} • {{ $topic->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="flex space-x-6 border-b pb-2 mb-6">
    <a href="{{ route('user.dashboard') }}"
       class="{{ request()->routeIs('user.dashboard') ? 'font-bold border-b-2 border-blue-600 text-blue-600' : 'text-gray-500' }}">
        User
    </a>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'font-bold border-b-2 border-blue-600 text-blue-600' : 'text-gray-500' }}">
            Admin
        </a>
    @endif
</div>

@endsection
