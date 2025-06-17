@extends('layouts.app')

@section('content')
    <div class="space-y-6 bg-gray-900 min-h-screen text-gray-100">
        <!-- Welcome Header - GitHub Dark Style -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-8">
                <div class="flex items-center space-x-4">
                    <!-- User Avatar -->
                    <x-user-avatar :user="Auth::user()" size="2xl" :linkToProfile="false" />
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-100 mb-1">{{ Auth::user()->name }}</h1>
                        <p class="text-gray-400">Welcome to your forum dashboard</p>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                            <span><i class="fas fa-calendar-alt mr-1"></i>Joined {{ Auth::user()->created_at->format('M Y') }}</span>
                            @if(Auth::user()->isAdmin())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                                    <i class="fas fa-star mr-1"></i>Administrator
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats - GitHub Dark Style -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-4 text-center hover:bg-gray-700 transition-colors">
                        <div class="text-2xl font-semibold text-gray-100">{{ $stats['topics_count'] }}</div>
                        <div class="text-sm text-gray-400">Topics</div>
                    </div>
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-4 text-center hover:bg-gray-700 transition-colors">
                        <div class="text-2xl font-semibold text-gray-100">{{ $stats['replies_count'] }}</div>
                        <div class="text-sm text-gray-400">Replies</div>
                    </div>
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-4 text-center hover:bg-gray-700 transition-colors">
                        <div class="text-2xl font-semibold text-gray-100">{{ $stats['total_views'] }}</div>
                        <div class="text-sm text-gray-400">Views</div>
                    </div>
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-4 text-center hover:bg-gray-700 transition-colors">
                        <div class="text-2xl font-semibold text-gray-100">{{ Auth::user()->created_at->diffInDays(now()) }}</div>
                        <div class="text-sm text-gray-400">Days active</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions - GitHub Dark Style -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-gray-100">Quick actions</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('topic.create') }}"
                        class="group bg-green-700 text-white p-4 rounded-lg hover:bg-green-600 transition duration-200 border border-green-600">
                        <div class="flex items-center">
                            <i class="fas fa-plus text-lg mr-3"></i>
                            <div>
                                <div class="font-semibold">New topic</div>
                                <div class="text-sm text-green-200">Start a discussion</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('forum.index') }}"
                        class="group bg-blue-700 text-white p-4 rounded-lg hover:bg-blue-600 transition duration-200 border border-blue-600">
                        <div class="flex items-center">
                            <i class="fas fa-comments text-lg mr-3"></i>
                            <div>
                                <div class="font-semibold">Browse forum</div>
                                <div class="text-sm text-blue-200">Explore topics</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.topics') }}"
                        class="group bg-gray-700 text-white p-4 rounded-lg hover:bg-gray-600 transition duration-200 border border-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-list text-lg mr-3"></i>
                            <div>
                                <div class="font-semibold">My topics</div>
                                <div class="text-sm text-gray-300">Manage content</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Topics - GitHub Dark Style -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-100">Recent topics</h3>
                        <a href="{{ route('user.topics') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                            View all
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-gray-700">
                    @forelse($recent_topics as $topic)
                        <div class="px-6 py-4 hover:bg-gray-750 transition duration-150">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        @if($topic->is_pinned)
                                            <i class="fas fa-thumbtack text-yellow-500 text-xs"></i>
                                        @endif
                                        @if($topic->is_locked)
                                            <i class="fas fa-lock text-red-500 text-xs"></i>
                                        @endif
                                        <a href="{{ route('topic.show', $topic) }}"
                                            class="font-semibold text-gray-100 hover:text-blue-400 line-clamp-1">
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
                                    @if($topic->replies_count > 5)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-900 text-green-200 border border-green-700">
                                            Hot
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-900 text-blue-200 border border-blue-700">
                                            New
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-comments text-4xl mb-4 text-gray-600"></i>
                            <p class="mb-2 font-medium text-gray-400">No topics yet</p>
                            <p class="text-sm">Create your first topic to get started</p>
                            <a href="{{ route('topic.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors border border-green-600">
                                <i class="fas fa-plus mr-2"></i>Create topic
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Replies - GitHub Dark Style -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-100">Recent replies</h3>
                        <a href="{{ route('user.replies') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                            View all
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-gray-700">
                    @forelse($recent_replies as $reply)
                        <div class="px-6 py-4 hover:bg-gray-750 transition duration-150">
                            <div class="flex items-start space-x-3">
                                <!-- User Avatar -->
                                <div class="flex-shrink-0">
                                    <x-user-avatar :user="$reply->user" size="sm" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="mb-1">
                                        <a href="{{ route('topic.show', $reply->topic) }}"
                                            class="font-semibold text-gray-100 hover:text-blue-400 line-clamp-1">
                                            {{ Str::limit($reply->topic->title, 40) }}
                                        </a>
                                    </div>
                                    <p class="text-sm text-gray-400 line-clamp-2 mb-2">
                                        {{ Str::limit($reply->content, 80) }}
                                    </p>
                                    <div class="text-xs text-gray-500">
                                        {{ $reply->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-reply text-4xl mb-4 text-gray-600"></i>
                            <p class="mb-2 font-medium text-gray-400">No replies yet</p>
                            <p class="text-sm">Join conversations in the forum</p>
                            <a href="{{ route('forum.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors border border-blue-600">
                                <i class="fas fa-comments mr-2"></i>Browse forum
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Activity Overview - GitHub Dark Style -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Activity Summary -->
            <div class="lg:col-span-2 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-100">Activity overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-900 border border-blue-700 rounded-lg">
                            <div class="text-2xl font-semibold text-blue-200">{{ $stats['topics_count'] }}</div>
                            <div class="text-sm text-gray-400">Topics created</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($stats['topics_count'] > 0)
                                    {{ number_format($stats['total_views'] / $stats['topics_count'], 1) }} avg views
                                @else
                                    Start creating!
                                @endif
                            </div>
                        </div>

                        <div class="text-center p-4 bg-green-900 border border-green-700 rounded-lg">
                            <div class="text-2xl font-semibold text-green-200">{{ $stats['replies_count'] }}</div>
                            <div class="text-sm text-gray-400">Replies posted</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($stats['topics_count'] > 0)
                                    {{ number_format($stats['replies_count'] / max($stats['topics_count'], 1), 1) }} per topic
                                @else
                                    Join discussions!
                                @endif
                            </div>
                        </div>

                        <div class="text-center p-4 bg-yellow-900 border border-yellow-700 rounded-lg">
                            <div class="text-2xl font-semibold text-yellow-200">{{ $stats['total_views'] }}</div>
                            <div class="text-sm text-gray-400">Total views</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Content reach
                            </div>
                        </div>

                        <div class="text-center p-4 bg-purple-900 border border-purple-700 rounded-lg">
                            <div class="text-2xl font-semibold text-purple-200">
                                @if(Auth::user()->last_active)
                                    {{ Auth::user()->last_active->diffInDays(now()) }}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="text-sm text-gray-400">Days active</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Keep it up!
                            </div>
                        </div>
                    </div>

                    <!-- Engagement Level - GitHub Dark Style -->
                    <div class="mt-6 p-4 bg-gray-750 border border-gray-600 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-200">Forum engagement</span>
                            <span class="text-sm text-gray-400">
                                @php
                                    $totalActivity = $stats['topics_count'] + $stats['replies_count'];
                                    if ($totalActivity >= 20) echo 'Expert contributor';
                                    elseif ($totalActivity >= 10) echo 'Active member';
                                    elseif ($totalActivity >= 5) echo 'Regular user';
                                    elseif ($totalActivity >= 1) echo 'Getting started';
                                    else echo 'New member';
                                @endphp
                            </span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                style="width: {{ min(($totalActivity / 20) * 100, 100) }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">
                            @if($totalActivity < 20)
                                {{ 20 - $totalActivity }} more contributions to reach expert level
                            @else
                                🎉 You've reached expert contributor status!
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Summary - GitHub Dark Style -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-100">Profile</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <!-- User Avatar -->
                        <div class="flex justify-center mb-4">
                            <x-user-avatar :user="Auth::user()" size="xl" :linkToProfile="false" />
                        </div>
                        <h4 class="font-semibold text-gray-100 text-lg">{{ Auth::user()->name }}</h4>
                        <p class="text-sm text-gray-400">{{ Auth::user()->email }}</p>
                        @if(Auth::user()->isAdmin())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700 mt-2">
                                <i class="fas fa-star mr-1"></i>Administrator
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600 mt-2">
                                Member
                            </span>
                        @endif
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Joined</span>
                            <span class="font-medium text-gray-200">{{ Auth::user()->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Last active</span>
                            <span class="font-medium text-gray-200">
                                @if(Auth::user()->last_active)
                                    {{ Auth::user()->last_active->diffForHumans() }}
                                @else
                                    Today
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Forum rank</span>
                            <span class="font-medium text-gray-200">
                                @php
                                    $totalActivity = $stats['topics_count'] + $stats['replies_count'];
                                    if ($totalActivity >= 20) echo 'Expert';
                                    elseif ($totalActivity >= 10) echo 'Active';
                                    elseif ($totalActivity >= 5) echo 'Regular';
                                    elseif ($totalActivity >= 1) echo 'Beginner';
                                    else echo 'New';
                                @endphp
                            </span>
                        </div>
                    </div>

                    <!-- Achievement Badges - GitHub Dark Style -->
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <h5 class="text-sm font-semibold text-gray-200 mb-3">Achievements</h5>
                        <div class="flex flex-wrap gap-2">
                            @if($stats['topics_count'] >= 1)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-900 text-blue-200 border border-blue-700">
                                    <i class="fas fa-pen mr-1"></i>Author
                                </span>
                            @endif
                            @if($stats['replies_count'] >= 1)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-900 text-green-200 border border-green-700">
                                    <i class="fas fa-comment mr-1"></i>Contributor
                                </span>
                            @endif
                            @if($stats['topics_count'] >= 5)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-900 text-purple-200 border border-purple-700">
                                    <i class="fas fa-fire mr-1"></i>Creator
                                </span>
                            @endif
                            @if($stats['total_views'] >= 100)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-900 text-yellow-200 border border-yellow-700">
                                    <i class="fas fa-star mr-1"></i>Popular
                                </span>
                            @endif
                            @if($stats['topics_count'] + $stats['replies_count'] >= 20)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-900 text-red-200 border border-red-700">
                                    <i class="fas fa-crown mr-1"></i>Expert
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <a href="{{ route('user.profile', Auth::user()) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-700 text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors border border-gray-600">
                            <i class="fas fa-user mr-2"></i>View profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs - GitHub Dark Style -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-4">
                <div class="flex space-x-8 border-b border-gray-700 -mb-4">
                    <a href="{{ route('user.dashboard') }}"
                        class="pb-4 {{ request()->routeIs('user.dashboard') ? 'font-semibold border-b-2 border-blue-500 text-blue-400' : 'text-gray-400 hover:text-gray-200' }}">
                        User Dashboard
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="pb-4 {{ request()->routeIs('admin.dashboard') ? 'font-semibold border-b-2 border-blue-500 text-blue-400' : 'text-gray-400 hover:text-gray-200' }}">
                            Admin Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
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

        /* Custom gray-750 for better dark mode */
        .bg-gray-750 {
            background-color: #374151;
        }

        .hover\:bg-gray-750:hover {
            background-color: #374151;
        }
    </style>
@endsection
