@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Topics</h1>
                    <p class="text-gray-600">Topics you've created</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('topic.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>New Topic
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($topics as $topic)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($topic->is_pinned)
                                    <i class="fas fa-thumbtack text-green-600"></i>
                                @endif
                                @if($topic->is_locked)
                                    <i class="fas fa-lock text-red-600"></i>
                                @endif
                                <a href="{{ route('topic.show', $topic) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                    {{ $topic->title }}
                                </a>
                            </div>

                            <div class="text-sm text-gray-600 mb-3">
                                <span>Created {{ $topic->created_at->format('M j, Y \a\t g:i A') }}</span>
                                @if($topic->created_at != $topic->updated_at)
                                    <span class="mx-2">•</span>
                                    <span>Last updated {{ $topic->updated_at->diffForHumans() }}</span>
                                @endif
                            </div>

                            <div class="text-gray-700 mb-3">
                                {{ Str::limit($topic->content, 200) }}
                            </div>

                            <div class="flex items-center space-x-4 text-sm">
                                <span class="text-gray-500">
                                    <i class="fas fa-eye mr-1"></i>{{ $topic->views }} views
                                </span>
                                <span class="text-gray-500">
                                    <i class="fas fa-reply mr-1"></i>{{ $topic->replies_count }} replies
                                </span>
                                <a href="{{ route('topic.show', $topic) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                @if(Auth::user()->can('update', $topic))
                                    <a href="{{ route('topic.edit', $topic) }}" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                @endif
                                @if(Auth::user()->can('delete', $topic))
                                    <form method="POST" action="{{ route('topic.destroy', $topic) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this topic?')">
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
                    <i class="fas fa-comments text-4xl mb-4"></i>
                    <p class="text-lg mb-2">No topics created yet</p>
                    <p>Start a new discussion by creating your first topic.</p>
                    <a href="{{ route('topic.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Create First Topic
                    </a>
                </div>
            @endforelse
        </div>

        @if($topics->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $topics->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
