@extends('layouts.app')

@section('content')
<div class="space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">My Replies</h1>
                    <p class="text-gray-400">All your replies across different topics</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('forum.index') }}" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors border border-blue-600">
                        <i class="fas fa-comments mr-2"></i>Browse Topics
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-700">
            @forelse($replies as $reply)
                <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- Reply Icon -->
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center border border-green-600">
                                <i class="fas fa-reply text-green-200"></i>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="mb-2">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('topic.show', $reply->topic) }}" class="text-lg font-semibold text-gray-100 hover:text-blue-400 transition-colors">
                                        Re: {{ $reply->topic->title }}
                                    </a>

                                    <!-- Topic Status Indicators -->
                                    @if($reply->topic->is_pinned)
                                        <i class="fas fa-thumbtack text-yellow-500 text-sm"></i>
                                    @endif
                                    @if($reply->topic->is_locked)
                                        <i class="fas fa-lock text-red-500 text-sm"></i>
                                    @endif
                                </div>

                                <!-- Topic Author Info -->
                                <div class="text-sm text-gray-500 mt-1">
                                    Original topic by
                                    <a href="{{ route('user.profile', $reply->topic->user) }}" class="text-gray-400 hover:text-blue-400 transition-colors">
                                        {{ $reply->topic->user->name }}
                                    </a>
                                    @if($reply->topic->user->isAdmin())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700 ml-1">
                                            <i class="fas fa-star mr-1"></i>Admin
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-sm text-gray-400 mb-3">
                                <span>Replied {{ $reply->created_at->format('M j, Y \a\t g:i A') }}</span>
                                @if($reply->created_at != $reply->updated_at)
                                    <span class="mx-2 text-gray-600">•</span>
                                    <span>Edited {{ $reply->updated_at->diffForHumans() }}</span>
                                @endif
                                <span class="mx-2 text-gray-600">•</span>
                                <span class="text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Reply Content -->
                            <div class="text-gray-300 mb-4 p-3 bg-gray-750 rounded-lg border border-gray-600">
                                <div class="line-clamp-3">
                                    {{ Str::limit($reply->content, 250) }}
                                </div>
                                @if(strlen($reply->content) > 250)
                                    <div class="mt-2">
                                        <a href="{{ route('topic.show', $reply->topic) }}#reply-{{ $reply->id }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                            Read full reply →
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Reply Images -->
                            @if($reply->hasImages())
                                <div class="mb-3">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <i class="fas fa-image text-purple-400"></i>
                                        <span class="text-sm text-gray-400">{{ count($reply->images) }} image(s) attached</span>
                                    </div>
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($reply->image_urls as $imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                 alt="Reply image"
                                                 class="w-full h-16 object-cover rounded border border-gray-600 cursor-pointer hover:opacity-80 transition-opacity"
                                                 onclick="openImageModal('{{ $imageUrl }}')"
                                                 onerror="this.style.display='none';">
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Topic Stats -->
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-eye mr-1 text-blue-400"></i>{{ $reply->topic->views }} views
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-reply mr-1 text-green-400"></i>{{ $reply->topic->replies_count }} replies
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('topic.show', $reply->topic) }}#reply-{{ $reply->id }}" class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>View in Topic
                                </a>
                                @if(Auth::user()->can('update', $reply))
                                    <a href="{{ route('reply.edit', $reply) }}" class="inline-flex items-center text-green-400 hover:text-green-300 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit Reply
                                    </a>
                                @endif
                                @if(Auth::user()->can('delete', $reply))
                                    <form method="POST" action="{{ route('reply.destroy', $reply) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this reply? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-red-400 hover:text-red-300 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                @endif

                                <!-- Quick Reply Button -->
                                <a href="{{ route('topic.show', $reply->topic) }}#reply-form" class="inline-flex items-center text-gray-400 hover:text-gray-200 transition-colors">
                                    <i class="fas fa-reply mr-1"></i>Reply Again
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-reply text-6xl mb-4 text-gray-600"></i>
                    <p class="text-lg mb-2 text-gray-400 font-medium">No replies yet</p>
                    <p class="text-gray-500 mb-6">Start participating in discussions to see your replies here.</p>
                    <div class="flex items-center justify-center space-x-4">
                        <a href="{{ route('forum.index') }}" class="inline-flex items-center bg-blue-700 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition-colors border border-blue-600 font-medium">
                            <i class="fas fa-comments mr-2"></i>Browse Topics
                        </a>
                        <a href="{{ route('topic.create') }}" class="inline-flex items-center bg-green-700 text-white px-6 py-3 rounded-md hover:bg-green-600 transition-colors border border-green-600 font-medium">
                            <i class="fas fa-plus mr-2"></i>Create Topic
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($replies->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                <div class="pagination-dark">
                    {{ $replies->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Reply Statistics -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-chart-line text-green-400 mr-2"></i>Your Reply Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-green-400">{{ $replies->total() }}</div>
                    <div class="text-sm text-gray-400">Total Replies</div>
                </div>
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-blue-400">{{ $replies->unique('topic_id')->count() }}</div>
                    <div class="text-sm text-gray-400">Topics Participated</div>
                </div>
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-purple-400">
                        @php
                            $avgLength = $replies->count() > 0 ? round($replies->avg(function($reply) { return strlen($reply->content); })) : 0;
                        @endphp
                        {{ $avgLength }}
                    </div>
                    <div class="text-sm text-gray-400">Avg Reply Length</div>
                </div>
                <div class="text-center p-4 bg-gray-750 rounded-lg border border-gray-600">
                    <div class="text-2xl font-semibold text-yellow-400">{{ $replies->where('created_at', '>=', now()->subWeek())->count() }}</div>
                    <div class="text-sm text-gray-400">This Week</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full">
        <img id="modalImage" src="/placeholder.svg" alt="Full size image" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
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
