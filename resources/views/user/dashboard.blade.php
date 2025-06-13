@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg text-white">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-blue-100">Here's what's happening in your forum activity</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">{{ $stats['topics_count'] }}</div>
                    <div class="text-sm text-blue-100">Topics Created</div>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">{{ $stats['replies_count'] }}</div>
                    <div class="text-sm text-blue-100">Replies Posted</div>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">{{ $stats['total_views'] }}</div>
                    <div class="text-sm text-blue-100">Total Views</div>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">{{ Auth::user()->created_at->diffInDays(now()) }}</div>
                    <div class="text-sm text-blue-100">Days Active</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Quick Actions</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('topic.create') }}" class="group bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg hover:from-green-600 hover:to-green-700 transition duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-plus-circle text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">Create New Topic</div>
                            <div class="text-sm text-green-100">Start a new discussion</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('forum.index') }}" class="group bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-comments text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">Browse Forum</div>
                            <div class="text-sm text-blue-100">Explore all topics</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('user.topics') }}" class="group bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg hover:from-purple-600 hover:to-purple-700 transition duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-list text-2xl mr-4"></i>
                        <div>
                            <div class="font-semibold">My Topics</div>
                            <div class="text-sm text-purple-100">Manage your topics</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Topics -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-fire text-orange-500 mr-2"></i>My Recent Topics
                    </h3>
                    <a href="{{ route('user.topics') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($recent_topics as $topic)
                    <div class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    @if($topic->is_pinned)
                                        <i class="fas fa-thumbtack text-green-500 text-xs"></i>
                                    @endif
                                    @if($topic->is_locked)
                                        <i class="fas fa-lock text-red-500 text-xs"></i>
                                    @endif
                                    <a href="{{ route('topic.show', $topic) }}" class="font-medium text-gray-900 hover:text-blue-600 line-clamp-1">
                                        {{ Str::limit($topic->title, 50) }}
                                    </a>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span><i class="fas fa-reply mr-1"></i>{{ $topic->replies_count }}</span>
                                    <span><i class="fas fa-eye mr-1"></i>{{ $topic->views }}</span>
                                    <span>{{ $topic->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $topic->replies_count > 5 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $topic->replies_count > 5 ? 'Hot' : 'New' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-comments text-3xl mb-3 text-gray-300"></i>
                        <p class="mb-2">No topics created yet</p>
                        <a href="{{ route('topic.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Create your first topic
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Replies -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-reply text-blue-500 mr-2"></i>Recent Replies
                    </h3>
                    <a href="{{ route('user.replies') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($recent_replies as $reply)
                    <div class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-reply text-blue-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-1">
                                    <a href="{{ route('topic.show', $reply->topic) }}" class="font-medium text-gray-900 hover:text-blue-600 line-clamp-1">
                                        Re: {{ Str::limit($reply->topic->title, 40) }}
                                    </a>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">
                                    {{ Str::limit($reply->content, 80) }}
                                </p>
                                <div class="text-xs text-gray-500">
                                    {{ $reply->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-reply text-3xl mb-3 text-gray-300"></i>
                        <p class="mb-2">No replies posted yet</p>
                        <a href="{{ route('forum.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Join the conversation
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Activity Chart & Profile Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Activity Summary -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-chart-line text-green-500 mr-2"></i>Activity Overview
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['topics_count'] }}</div>
                        <div class="text-sm text-gray-600">Topics</div>
                        <div class="text-xs text-gray-500 mt-1">
                            @if($stats['topics_count'] > 0)
                                Avg {{ number_format($stats['total_views'] / $stats['topics_count'], 1) }} views/topic
                            @else
                                Start creating topics!
                            @endif
                        </div>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['replies_count'] }}</div>
                        <div class="text-sm text-gray-600">Replies</div>
                        <div class="text-xs text-gray-500 mt-1">
                            @if($stats['topics_count'] > 0)
                                Avg {{ number_format($stats['replies_count'] / max($stats['topics_count'], 1), 1) }} replies/topic
                            @else
                                Join discussions!
                            @endif
                        </div>
                    </div>

                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_views'] }}</div>
                        <div class="text-sm text-gray-600">Total Views</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Your content impact
                        </div>
                    </div>

                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">
                            @if(Auth::user()->last_active)
                                {{ Auth::user()->last_active->diffInDays(now()) }}
                            @else
                                0
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Days Active</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Keep it up!
                        </div>
                    </div>
                </div>

                <!-- Engagement Level -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Engagement Level</span>
                        <span class="text-sm text-gray-500">
                            @php
                                $totalActivity = $stats['topics_count'] + $stats['replies_count'];
                                if($totalActivity >= 20) echo 'Expert';
                                elseif($totalActivity >= 10) echo 'Active';
                                elseif($totalActivity >= 5) echo 'Regular';
                                elseif($totalActivity >= 1) echo 'Beginner';
                                else echo 'New Member';
                            @endphp
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: {{ min(($totalActivity / 20) * 100, 100) }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        @if($totalActivity < 20)
                            {{ 20 - $totalActivity }} more activities to reach Expert level
                        @else
                            You've reached Expert level! 🎉
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user-circle text-blue-500 mr-2"></i>Profile Info
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <h4 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h4>
                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    @if(Auth::user()->isAdmin())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-2">
                            <i class="fas fa-crown mr-1"></i>Administrator
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            <i class="fas fa-user mr-1"></i>Member
                        </span>
                    @endif
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Member since</span>
                        <span class="font-medium">{{ Auth::user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Last active</span>
                        <span class="font-medium">
                            @if(Auth::user()->last_active)
                                {{ Auth::user()->last_active->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Forum rank</span>
                        <span class="font-medium">
                            @php
                                $totalActivity = $stats['topics_count'] + $stats['replies_count'];
                                if($totalActivity >= 20) echo '🏆 Expert';
                                elseif($totalActivity >= 10) echo '⭐ Active';
                                elseif($totalActivity >= 5) echo '📈 Regular';
                                elseif($totalActivity >= 1) echo '🌱 Beginner';
                                else echo '👋 New Member';
                            @endphp
                        </span>
                    </div>
                </div>

                <!-- Achievement Badges -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="text-sm font-medium text-gray-900 mb-3">Achievements</h5>
                    <div class="flex flex-wrap gap-2">
                        @if($stats['topics_count'] >= 1)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                <i class="fas fa-pen mr-1"></i>First Topic
                            </span>
                        @endif
                        @if($stats['replies_count'] >= 1)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                <i class="fas fa-comment mr-1"></i>First Reply
                            </span>
                        @endif
                        @if($stats['topics_count'] >= 5)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                <i class="fas fa-fire mr-1"></i>Topic Creator
                            </span>
                        @endif
                        @if($stats['total_views'] >= 100)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                <i class="fas fa-eye mr-1"></i>Popular
                            </span>
                        @endif
                        @if($stats['topics_count'] + $stats['replies_count'] >= 20)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                <i class="fas fa-crown mr-1"></i>Expert
                            </span>
                        @endif
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

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
