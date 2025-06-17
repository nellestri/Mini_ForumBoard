@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <!-- Profile Header - GitHub Dark Style -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-8">
            <div class="flex items-center space-x-6">
                <!-- Profile Picture -->
                <div class="flex-shrink-0">
                    @if($user->profile_picture_url)
                        <img src="{{ $user->profile_picture_url }}"
                             alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full border-4 border-gray-600 shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center border-4 border-gray-600 shadow-lg">
                            <span class="text-3xl font-bold text-white">{{ $user->initials }}</span>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-2">
                        <h1 class="text-3xl font-semibold text-gray-100">{{ $user->name }}</h1>
                        @if($user->isAdmin())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                                <i class="fas fa-star mr-1"></i>Administrator
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                <i class="fas fa-user mr-1"></i>Member
                            </span>
                        @endif
                    </div>

                    @if($user->bio)
                        <p class="text-gray-300 mb-3">{{ $user->bio }}</p>
                    @endif

                    <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Joined {{ $stats['member_since']->format('M Y') }}</span>
                        </div>
                        @if($user->location)
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $user->location }}</span>
                            </div>
                        @endif
                        @if($user->website)
                            <div class="flex items-center">
                                <i class="fas fa-globe mr-2"></i>
                                <a href="{{ $user->website }}" target="_blank" class="hover:text-blue-400 transition-colors">
                                    {{ parse_url($user->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                        @endif
                        @if($stats['last_active'])
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                <span>Last active {{ $stats['last_active']->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>


        </div>
    </div>

    <!-- Stats Cards - GitHub Dark Style -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 text-center hover:bg-gray-750 transition-colors">
            <div class="text-3xl font-semibold text-blue-400 mb-2">{{ $stats['topics_count'] }}</div>
            <div class="text-sm text-gray-400">Topics Created</div>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 text-center hover:bg-gray-750 transition-colors">
            <div class="text-3xl font-semibold text-green-400 mb-2">{{ $stats['replies_count'] }}</div>
            <div class="text-sm text-gray-400">Replies Posted</div>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 text-center hover:bg-gray-750 transition-colors">
            <div class="text-3xl font-semibold text-purple-400 mb-2">{{ $stats['total_views'] }}</div>
            <div class="text-sm text-gray-400">Total Views</div>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 text-center hover:bg-gray-750 transition-colors">
            <div class="text-3xl font-semibold text-yellow-400 mb-2">
                @php
                    $totalActivity = $stats['topics_count'] + $stats['replies_count'];
                    if ($totalActivity >= 20) echo '🏆';
                    elseif ($totalActivity >= 10) echo '⭐';
                    elseif ($totalActivity >= 5) echo '📈';
                    elseif ($totalActivity >= 1) echo '🌱';
                    else echo '👋';
                @endphp
            </div>
            <div class="text-sm text-gray-400">Forum Rank</div>
        </div>
    </div>

    <!-- Content Grid - GitHub Dark Style -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Topics -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-gray-100">
                    <i class="fas fa-comments text-blue-400 mr-2"></i>Recent Topics
                </h3>
            </div>
            <div class="divide-y divide-gray-700">
                @forelse($recent_topics as $topic)
                    <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    @if($topic->is_pinned)
                                        <i class="fas fa-thumbtack text-yellow-500 text-xs"></i>
                                    @endif
                                    @if($topic->is_locked)
                                        <i class="fas fa-lock text-red-500 text-xs"></i>
                                    @endif
                                    <a href="{{ route('topic.show', $topic) }}"
                                       class="font-semibold text-gray-100 hover:text-blue-400 transition-colors">
                                        {{ Str::limit($topic->title, 50) }}
                                    </a>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span><i class="fas fa-reply mr-1"></i>{{ $topic->replies_count }}</span>
                                    <span><i class="fas fa-eye mr-1"></i>{{ $topic->views }}</span>
                                    <span>{{ $topic->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-comments text-4xl mb-3 text-gray-600"></i>
                        <p class="text-gray-400">No topics created yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Replies -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-gray-100">
                    <i class="fas fa-reply text-green-400 mr-2"></i>Recent Replies
                </h3>
            </div>
            <div class="divide-y divide-gray-700">
                @forelse($recent_replies as $reply)
                    <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center border border-green-600">
                                    <i class="fas fa-reply text-green-200 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-1">
                                    <a href="{{ route('topic.show', $reply->topic) }}"
                                       class="font-semibold text-gray-100 hover:text-blue-400 transition-colors">
                                        Re: {{ Str::limit($reply->topic->title, 40) }}
                                    </a>
                                </div>
                                <p class="text-sm text-gray-400 mb-2">
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
                        <i class="fas fa-reply text-4xl mb-3 text-gray-600"></i>
                        <p class="text-gray-400">No replies posted yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Achievements Section - GitHub Dark Style -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-trophy text-yellow-400 mr-2"></i>Achievements
            </h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @if($stats['topics_count'] >= 1)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-900 text-blue-200 border border-blue-700">
                        <i class="fas fa-pen mr-2"></i>First Topic
                    </span>
                @endif
                @if($stats['replies_count'] >= 1)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-900 text-green-200 border border-green-700">
                        <i class="fas fa-comment mr-2"></i>First Reply
                    </span>
                @endif
                @if($stats['topics_count'] >= 5)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-900 text-purple-200 border border-purple-700">
                        <i class="fas fa-fire mr-2"></i>Topic Creator
                    </span>
                @endif
                @if($stats['total_views'] >= 100)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-900 text-yellow-200 border border-yellow-700">
                        <i class="fas fa-eye mr-2"></i>Popular
                    </span>
                @endif
                @if($stats['topics_count'] + $stats['replies_count'] >= 20)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-900 text-red-200 border border-red-700">
                        <i class="fas fa-crown mr-2"></i>Expert
                    </span>
                @endif
                @if($user->isAdmin())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-900 text-indigo-200 border border-indigo-700">
                        <i class="fas fa-shield-alt mr-2"></i>Administrator
                    </span>
                @endif
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
</style>
@endsection
