@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Reply</h1>
            <p class="text-gray-600">Update your reply to: <strong>{{ $reply->topic->title }}</strong></p>
        </div>

        <form method="POST" action="{{ route('reply.update', $reply) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Reply Content
                </label>
                <textarea id="content"
                          name="content"
                          rows="6"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Write your reply here..."
                          required>{{ old('content', $reply->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('topic.show', $reply->topic) }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>Update Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection