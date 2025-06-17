@extends('layouts.app')

@section('content')
<div class="space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">My Topics</h1>
                    <p class="text-gray-400">Topics you've created</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('topic.create') }}" class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors border border-green-600">
                        <i class="fas fa-plus mr-2"></i>New Topic
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-700">
            @forelse($topics as $topic)
                <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($topic->is_pinned)
                                    <i class="fas fa-thumbtack text-yellow-500"></i>
                                @endif
                                @if($topic->is_locked)
                                    <i class="fas fa-lock text-red-500"></i>
                                @endif
                                <a href="{{ route('topic.show', $topic) }}" class="text-lg font-semibold text-gray-100 hover:text-blue-400 transition-colors">
                                    {{ $topic->title }}
                                </a>

                                <!-- Topic Status Badges -->
                                <div class="flex items-center space-x-2">
                                    @if($topic->replies_count > 10)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200 border border-green-700">
                                            <i class="fas fa-fire mr-1"></i>Hot
                                        </span>
                                    @elseif($topic->created_at->diffInHours() < 24)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200 border border-blue-700">
                                            <i class="fas fa-clock mr-1"></i>New
                                        </span>
                                    @endif

                                    @if($topic->replies_count == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                                            <i class="fas fa-question mr-1"></i>No replies
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-sm text-gray-400 mb-3">
                                <span>Created {{ $topic->created_at->format('M j, Y \a\t g:i A') }}</span>
                                @if($topic->created_at != $topic->updated_at)
                                    <span class="mx-2 text-gray-600">•</span>
                                    <span>Last updated {{ $topic->updated_at->diffForHumans() }}</span>
                                @endif
                                @if($topic->latestReply)
                                    <span class="mx-2 text-gray-600">•</span>
                                    <span>Latest reply {{ $topic->latestReply->created_at->diffForHumans() }}</span>
                                @endif
                            </div>

                            <div class="text-gray-300 mb-3 line-clamp-2">
                                {{ Str::limit($topic->content, 200) }}
                            </div>

                            <!-- Topic Stats -->
                            <div class="flex items-center space-x-6 text-sm mb-3">
                                <div class="flex items-center space-x-4">
                                    <span class="text-gray-500 flex items-center">
                                        <i class="fas fa-eye mr-1 text-blue-400"></i>{{ $topic->views }} views
                                    </span>
                                    <span class="text-gray-500 flex items-center">
                                        <i class="fas fa-reply mr-1 text-green-400"></i>{{ $topic->replies_count }} replies
                                    </span>
                                    @if($topic->hasImages())
                                        <span class="text-gray-500 flex items-center">
                                            <i class="fas fa-image mr-1 text-purple-400"></i>{{ count($topic->images) }} images
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('topic.show', $topic) }}" class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>View Topic
                                </a>
                                @if(Auth::user()->can('update', $topic))
                                    <a href="{{ route('topic.edit', $topic) }}" class="inline-flex items-center text-green-400 hover:text-green-300 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                @endif
                                @if(Auth::user()->can('delete', $topic))
                                    <form method="POST" action="{{ route('topic.destroy', $topic) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this topic? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-red-400 hover:text-red-300 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                @endif

                                <!-- Toggle Pin/Lock (Admin only) -->
                                @if(Auth::user()->isAdmin())
                                    <div class="flex items-center space-x-2 ml-4 pl-4 border-l border-gray-600">
                                        @if($topic->is_pinned)
                                            <form method="POST" action="{{ route('topic.unpin', $topic) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center text-yellow-400 hover:text-yellow-300 transition-colors text-xs">
                                                    <i class="fas fa-thumbtack mr-1"></i>Unpin
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('topic.pin', $topic) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center text-gray-400 hover:text-yellow-400 transition-colors text-xs">
                                                    <i class="fas fa-thumbtack mr-1"></i>Pin
                                                </button>
                                            </form>
                                        @endif

                                        @if($topic->is_locked)
                                            <form method="POST" action="{{ route('topic.unlock', $topic) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center text-red-400 hover:text-red-300 transition-colors text-xs">
                                                    <i class="fas fa-unlock mr-1"></i>Unlock
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('topic.lock', $topic) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center text-gray-400 hover:text-red-400 transition-colors text-xs">
                                                    <i class="fas fa-lock mr-1"></i>Lock
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-comments text-6xl mb-4 text-gray-600"></i>
                    <p class="text-lg mb-2 text-gray-400 font-medium">No topics created yet</p>
                    <p class="text-gray-500 mb-6">Start a new discussion by creating your first topic.</p>
                    <a href="{{ route('topic.create') }}" class="inline-flex items-center bg-green-700 text-white px-6 py-3 rounded-md hover:bg-green-600 transition-colors border border-green-600 font-medium">
                        <i class="fas fa-plus mr-2"></i>Create First Topic
                    </a>
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

    <!-- Quick Stats Summary -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-chart-bar text-blue-400 mr-2"></i>Your Topic Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-blue-400">{{ $topics->total() }}</div>
                    <div class="text-sm text-gray-400">Total Topics</div>
                </div>
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-green-400">{{ $topics->sum('replies_count') }}</div>
                    <div class="text-sm text-gray-400">Total Replies</div>
                </div>
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-purple-400">{{ $topics->sum('views') }}</div>
                    <div class="text-sm text-gray-400">Total Views</div>
                </div>
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-yellow-400">
                        @php
                            $avgViews = $topics->count() > 0 ? round($topics->sum('views') / $topics->count()) : 0;
                        @endphp
                        {{ $avgViews }}
                    </div>
                    <div class="text-sm text-gray-400">Avg Views</div>
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
