@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <!-- Topic Display - GitHub Dark Style -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">{{ $topic->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-400 mt-2">
                        <div class="flex items-center space-x-2">
                            <span>By</span>
                            <x-user-avatar :user="$topic->user" size="sm" :showName="true" />
                        </div>
                        <span>{{ $topic->created_at->format('M j, Y \a\t g:i A') }}</span>
                        <span><i class="fas fa-eye mr-1"></i>{{ $topic->views }} views</span>
                    </div>
                </div>
                @if(Auth::check() && Auth::user()->can('update', $topic))
                    <a href="{{ route('topic.edit', $topic) }}" class="text-blue-400 hover:text-blue-300">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                @endif
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="prose prose-invert max-w-none mb-4 text-gray-300">
                {!! nl2br(e($topic->content)) !!}
            </div>

            <!-- Topic Images -->
            @if($topic->hasImages())
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-300 mb-2">Images:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($topic->image_urls as $imageUrl)
                            <div class="relative group">
                                <img src="{{ $imageUrl }}"
                                     alt="Topic image"
                                     class="w-full h-48 object-cover rounded-lg border border-gray-600 cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openImageModal('{{ $imageUrl }}')"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display: none;" class="w-full h-48 bg-gray-700 rounded-lg border border-gray-600 flex items-center justify-center">
                                    <span class="text-gray-400">Image not found</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Replies Section - GitHub Dark Style -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-gray-100">
                Replies ({{ $replies->total() }})
            </h2>
        </div>

        <div class="divide-y divide-gray-700">
            @forelse($replies as $reply)
                <div class="px-6 py-4 hover:bg-gray-750 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- User Avatar -->
                        <x-user-avatar :user="$reply->user" size="md" />

                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('user.profile', $reply->user) }}" class="font-semibold text-gray-100 hover:text-blue-400">
                                        {{ $reply->user->name }}
                                    </a>
                                    @if($reply->user->isAdmin())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                                            <i class="fas fa-star mr-1"></i>Admin
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500">•</span>
                                    <p class="text-sm text-gray-400">{{ $reply->created_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                                @if(Auth::check() && Auth::user()->can('update', $reply))
                                    <div class="flex space-x-2">
                                        <a href="{{ route('reply.edit', $reply) }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form method="POST" action="{{ route('reply.destroy', $reply) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2 prose prose-invert max-w-none text-gray-300">
                                {!! nl2br(e($reply->content)) !!}
                            </div>

                            <!-- Reply Images -->
                            @if($reply->hasImages())
                                <div class="mt-3">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        @foreach($reply->image_urls as $imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                 alt="Reply image"
                                                 class="w-full h-20 object-cover rounded border border-gray-600 cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal('{{ $imageUrl }}')"
                                                 onerror="this.style.display='none';">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <p>No replies yet. Be the first to reply!</p>
                </div>
            @endforelse
        </div>

        @if($replies->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $replies->links() }}
            </div>
        @endif
    </div>

    <!-- Reply Form - GitHub Dark Style -->
    @auth
        @if(!$topic->is_locked || Auth::user()->role === 'admin')
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <x-user-avatar :user="Auth::user()" size="sm" :linkToProfile="false" />
                        <h3 class="text-lg font-semibold text-gray-100">Post a Reply</h3>
                    </div>
                </div>

                <form method="POST" action="{{ route('reply.store', $topic) }}" class="p-6" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="reply-content" class="block text-sm font-medium text-gray-300 mb-2">
                            Your Reply
                        </label>
                        <textarea id="reply-content"
                                  name="content"
                                  rows="4"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Write your reply here..."
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reply Image Upload -->
                    <div class="mb-4">
                        <label for="reply-images" class="block text-sm font-medium text-gray-300 mb-2">
                            Images (Optional)
                        </label>
                        <input type="file"
                               id="reply-images"
                               name="images[]"
                               multiple
                               accept="image/*"
                               class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-700 file:text-blue-100 hover:file:bg-blue-600"
                               onchange="previewReplyImages(this)">
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-400">{{ $messages }}</p>
                        @enderror

                        <!-- Reply Image Preview -->
                        <div id="replyImagePreview" class="mt-2 grid grid-cols-4 gap-2 hidden"></div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 border border-green-600">
                            <i class="fas fa-reply mr-2"></i>Post Reply
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-yellow-900 border border-yellow-700 rounded-lg p-4">
                <p class="text-yellow-200">This topic is locked. No new replies can be posted.</p>
            </div>
        @endif
    @else
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center">
            <p class="text-gray-400">
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Login</a>
                to post a reply.
            </p>
        </div>
    @endauth
</div>

<!-- Image Modal - GitHub Dark Style -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full">
        <img id="modalImage" src="/placeholder.svg" alt="Full size image" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

<script>
function previewReplyImages(input) {
    const preview = document.getElementById('replyImagePreview');
    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');

        Array.from(input.files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-16 object-cover rounded border border-gray-600';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    } else {
        preview.classList.add('hidden');
    }
}

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
.bg-gray-750 {
    background-color: #374151;
}

.hover\:bg-gray-750:hover {
    background-color: #374151;
}

.prose-invert {
    color: #d1d5db;
}
</style>
@endsection
