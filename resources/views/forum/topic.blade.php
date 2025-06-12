@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Topic Header -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        @if($topic->is_pinned)
                            <i class="fas fa-thumbtack text-green-600"></i>
                        @endif
                        @if($topic->is_locked)
                            <i class="fas fa-lock text-red-600"></i>
                        @endif
                        <h1 class="text-2xl font-bold text-gray-900">{{ $topic->title }}</h1>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span>by <strong>{{ $topic->user->name }}</strong></span>
                        <span class="mx-2">•</span>
                        <span>{{ $topic->created_at->format('M j, Y \a\t g:i A') }}</span>
                        <span class="mx-2">•</span>
                        <span><i class="fas fa-eye mr-1"></i>{{ $topic->views }} views</span>
                    </div>
                </div>

                @auth
                    @if(Auth::user()->can('update', $topic) || Auth::user()->isAdmin())
                        <div class="flex space-x-2">
                            @if(Auth::user()->can('update', $topic))
                                <a href="{{ route('topic.edit', $topic) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif

                            @if(Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('admin.topics.toggle-pin', $topic) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="{{ $topic->is_pinned ? 'Unpin' : 'Pin' }} Topic">
                                        <i class="fas fa-thumbtack"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.topics.toggle-lock', $topic) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="{{ $topic->is_locked ? 'Unlock' : 'Lock' }} Topic">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="prose max-w-none">
                {!! nl2br(e($topic->content)) !!}
            </div>
        </div>
    </div>

    <!-- Replies -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                Replies ({{ $replies->total() }})
            </h2>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($replies as $reply)
                <div class="px-6 py-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm">
                                    <strong class="text-gray-900">{{ $reply->user->name }}</strong>
                                    @if($reply->user->isAdmin())
                                        <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Admin</span>
                                    @endif
                                    <span class="text-gray-500 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>

                                @auth
                                    @if(Auth::user()->can('update', $reply))
                                        <div class="flex space-x-2">
                                            <a href="{{ route('reply.edit', $reply) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('reply.destroy', $reply) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>

                            <div class="prose max-w-none">
                                {!! nl2br(e($reply->content)) !!}
                            </div>
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
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $replies->links() }}
            </div>
        @endif
    </div>

    <!-- Reply Form -->
    @auth
        @if(!$topic->is_locked || Auth::user()->isAdmin())
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Post a Reply</h3>
                </div>

                <form method="POST" action="{{ route('reply.store', $topic) }}" class="px-6 py-4">
                    @csrf
                    <div class="mb-4">
                        <textarea name="content" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write your reply..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-reply mr-2"></i>Post Reply
                    </button>
                </form>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-yellow-800">
                    <i class="fas fa-lock mr-2"></i>This topic is locked. No new replies can be posted.
                </p>
            </div>
        @endif
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>Please <a href="{{ route('login') }}" class="underline">login</a> to post a reply.
            </p>
        </div>
    @endauth
</div>
@endsection
