@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-gray-900 min-h-screen text-gray-100 p-6">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">Forum</h1>
                    <p class="text-gray-400">Community discussions and topics</p>
                </div>
                @auth
                    <a href="{{ route('topic.create') }}" class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors border border-green-600">
                        <i class="fas fa-plus mr-2"></i>New Topic
                    </a>
                @endauth
            </div>
        </div>

        <div class="divide-y divide-gray-700">
            @forelse($topics as $topic)
                <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- Topic Author Avatar -->
                        <x-user-avatar :user="$topic->user" size="md" />

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($topic->is_pinned)
                                    <i class="fas fa-thumbtack text-yellow-500"></i>
                                @endif
                                @if($topic->is_locked)
                                    <i class="fas fa-lock text-red-500"></i>
                                @endif
                                <a href="{{ route('topic.show', $topic) }}" class="text-lg font-semibold text-gray-100 hover:text-blue-400 truncate transition-colors">
                                    {{ $topic->title }}
                                </a>
                            </div>

                            <div class="flex items-center space-x-4 text-sm text-gray-400 mb-3">
                                <div class="flex items-center space-x-1">
                                    <span>By</span>
                                    <a href="{{ route('user.profile', $topic->user) }}" class="font-semibold text-gray-200 hover:text-blue-400 transition-colors">
                                        {{ $topic->user->name }}
                                    </a>
                                    @if($topic->user->isAdmin())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                                            <i class="fas fa-star mr-1"></i>Admin
                                        </span>
                                    @endif
                                </div>
                                <span class="text-gray-600">•</span>
                                <span>{{ $topic->created_at->format('M j, Y') }}</span>
                                @if($topic->latestReply)
                                    <span class="text-gray-600">•</span>
                                    <div class="flex items-center space-x-1">
                                        <span>Last reply by</span>
                                        <x-user-avatar :user="$topic->latestReply->user" size="xs" />
                                        <a href="{{ route('user.profile', $topic->latestReply->user) }}" class="font-semibold text-gray-200 hover:text-blue-400 transition-colors">
                                            {{ $topic->latestReply->user->name }}
                                        </a>
                                        <span class="text-gray-500">{{ $topic->latestReply->created_at->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="text-gray-300 mb-3 line-clamp-2">
                                {{ Str::limit($topic->content, 200) }}
                            </div>

                            <div class="flex items-center space-x-4 text-sm">
                                <span class="text-gray-500 flex items-center">
                                    <i class="fas fa-eye mr-1"></i>{{ $topic->views }} views
                                </span>
                                <span class="text-gray-500 flex items-center">
                                    <i class="fas fa-reply mr-1"></i>{{ $topic->replies_count }} replies
                                </span>
                                @if($topic->hasImages())
                                    <span class="text-gray-500 flex items-center">
                                        <i class="fas fa-image mr-1"></i>{{ count($topic->images) }} images
                                    </span>
                                @endif

                                <!-- Topic Status Badges -->
                                <div class="flex items-center space-x-2 ml-auto">
                                    @if($topic->replies_count > 10)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-900 text-green-200 border border-green-700">
                                            <i class="fas fa-fire mr-1"></i>Hot
                                        </span>
                                    @elseif($topic->created_at->diffInHours() < 24)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-900 text-blue-200 border border-blue-700">
                                            <i class="fas fa-clock mr-1"></i>New
                                        </span>
                                    @endif

                                    @if($topic->replies_count == 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                                            <i class="fas fa-question mr-1"></i>No replies
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-comments text-6xl mb-4 text-gray-600"></i>
                    <p class="text-lg mb-2 text-gray-400 font-medium">No topics yet</p>
                    <p class="text-gray-500 mb-6">Be the first to start a discussion!</p>
                    @auth
                        <a href="{{ route('topic.create') }}" class="inline-flex items-center bg-green-700 text-white px-6 py-3 rounded-md hover:bg-green-600 transition-colors border border-green-600 font-medium">
                            <i class="fas fa-plus mr-2"></i>Create First Topic
                        </a>
                    @else
                        <div class="space-y-3">
                            <p class="text-gray-500">Join our community to start discussions</p>
                            <div class="flex items-center justify-center space-x-4">
                                <a href="{{ route('login') }}" class="inline-flex items-center bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors border border-blue-600">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center bg-gray-700 text-gray-200 px-4 py-2 rounded-md hover:bg-gray-600 transition-colors border border-gray-600">
                                    <i class="fas fa-user-plus mr-2"></i>Register
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            @endforelse
        </div>

        @if($topics->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                <div class="pagination-dark">
                    {{ $topics->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Forum Stats Sidebar (Optional) -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-chart-bar text-blue-400 mr-2"></i>Forum Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-xl font-semibold text-blue-400">{{ $topics->total() }}</div>
                    <div class="text-xs text-gray-400">Total Topics</div>
                </div>
                <div class="text-center p-3 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-xl font-semibold text-green-400">{{ $topics->sum('replies_count') }}</div>
                    <div class="text-xs text-gray-400">Total Replies</div>
                </div>
                <div class="text-center p-3 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-xl font-semibold text-purple-400">{{ $topics->sum('views') }}</div>
                    <div class="text-xs text-gray-400">Total Views</div>
                </div>
                <div class="text-center p-3 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-xl font-semibold text-yellow-400">{{ $topics->where('created_at', '>=', now()->subDay())->count() }}</div>
                    <div class="text-xs text-gray-400">Today's Topics</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

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
