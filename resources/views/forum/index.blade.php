@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900">Forum Topics</h1>
        <p class="text-gray-600">Discuss and share ideas with the community</p>
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

                        <div class="text-sm text-gray-600 mb-2">
                            <span>by <strong>{{ $topic->user->name }}</strong></span>
                            <span class="mx-2">•</span>
                            <span>{{ $topic->created_at->diffForHumans() }}</span>
                            @if($topic->latestReply)
                                <span class="mx-2">•</span>
                                <span>Last reply by <strong>{{ $topic->latestReply->user->name }}</strong> {{ $topic->latestReply->created_at->diffForHumans() }}</span>
                            @endif
                        </div>

                        <p class="text-gray-700 line-clamp-2">{{ Str::limit($topic->content, 150) }}</p>
                    </div>

                    <div class="ml-4 text-center">
                        <div class="text-sm text-gray-500">
                            <div><i class="fas fa-eye mr-1"></i>{{ $topic->views }}</div>
                            <div><i class="fas fa-reply mr-1"></i>{{ $topic->replies_count }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-comments text-4xl mb-4"></i>
                <p>No topics yet. Be the first to start a discussion!</p>
                @auth
                    <a href="{{ route('topic.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Create First Topic
                    </a>
                @endauth
            </div>
        @endforelse
    </div>

    @if($topics->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $topics->links() }}
        </div>
    @endif
</div>
@endsection
