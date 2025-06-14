@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Replies</h1>
                    <p class="text-gray-600">All your replies across different topics</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($replies as $reply)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="mb-2">
                                <a href="{{ route('topic.show', $reply->topic) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                    Re: {{ $reply->topic->title }}
                                </a>
                            </div>

                            <div class="text-sm text-gray-600 mb-3">
                                <span>{{ $reply->created_at->format('M j, Y \a\t g:i A') }}</span>
                                @if($reply->created_at != $reply->updated_at)
                                    <span class="mx-2">•</span>
                                    <span>Edited {{ $reply->updated_at->diffForHumans() }}</span>
                                @endif
                            </div>

                            <div class="text-gray-700 mb-3">
                                {{ Str::limit($reply->content, 200) }}
                            </div>

                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('topic.show', $reply->topic) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye mr-1"></i>View Topic
                                </a>
                                @if(Auth::user()->can('update', $reply))
                                    <a href="{{ route('reply.edit', $reply) }}" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                @endif
                                @if(Auth::user()->can('delete', $reply))
                                    <form method="POST" action="{{ route('reply.destroy', $reply) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-reply text-4xl mb-4"></i>
                    <p class="text-lg mb-2">No replies yet</p>
                    <p>Start participating in discussions to see your replies here.</p>
                    <a href="{{ route('forum.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Browse Topics
                    </a>
                </div>
            @endforelse
        </div>

        @if($replies->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $replies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
