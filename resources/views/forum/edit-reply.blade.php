@extends('layouts.app')

@section('content')
<div class="space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <!-- Breadcrumb Navigation -->
    <div class="flex items-center space-x-2 text-sm text-gray-400">
        <a href="{{ route('forum.index') }}" class="hover:text-blue-400 transition-colors">
            <i class="fas fa-home mr-1"></i>Forum
        </a>
        <i class="fas fa-chevron-right text-gray-600"></i>
        <a href="{{ route('topic.show', $reply->topic) }}" class="hover:text-blue-400 transition-colors truncate max-w-xs">
            {{ Str::limit($reply->topic->title, 30) }}
        </a>
        <i class="fas fa-chevron-right text-gray-600"></i>
        <span class="text-gray-300">Edit Reply</span>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-700 bg-gray-750">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-100 flex items-center">
                            <i class="fas fa-edit text-yellow-400 mr-3"></i>Edit Reply
                        </h1>
                        <p class="text-gray-400 mt-2">
                            Update your reply to:
                            <a href="{{ route('topic.show', $reply->topic) }}" class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                {{ $reply->topic->title }}
                            </a>
                        </p>
                        <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                Originally posted {{ $reply->created_at->diffForHumans() }}
                            </span>
                            @if($reply->updated_at != $reply->created_at)
                                <span class="flex items-center">
                                    <i class="fas fa-history mr-1"></i>
                                    Last edited {{ $reply->updated_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                            <i class="fas fa-edit mr-1"></i>Editing
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('reply.update', $reply) }}" class="p-6">
                @csrf
                @method('PUT')

                <!-- Content Field -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-300 mb-3">
                        <i class="fas fa-comment-dots text-blue-400 mr-2"></i>Reply Content
                    </label>

                    <!-- Formatting Toolbar -->
                    <div class="bg-gray-750 border border-gray-600 rounded-t-lg px-4 py-2 flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="formatText('bold')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('code')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Code">
                                <i class="fas fa-code"></i>
                            </button>
                        </div>
                        <div class="w-px h-6 bg-gray-600"></div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="formatText('link')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="formatText('quote')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Quote">
                                <i class="fas fa-quote-left"></i>
                            </button>
                        </div>
                        <div class="ml-auto text-xs text-gray-500">
                            <span id="char-count">{{ strlen(old('content', $reply->content)) }}</span> characters
                        </div>
                    </div>

                    <textarea id="content"
                              name="content"
                              rows="8"
                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 border-t-0 rounded-b-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical"
                              placeholder="Write your reply here... You can use markdown formatting."
                              required
                              onkeyup="updateCharCount()"
                              onpaste="updateCharCount()">{{ old('content', $reply->content) }}</textarea>

                    @error('content')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror

                    <!-- Formatting Help -->
                    <div class="mt-3 text-xs text-gray-500">
                        <details class="cursor-pointer">
                            <summary class="hover:text-gray-400 transition-colors">
                                <i class="fas fa-question-circle mr-1"></i>Formatting help
                            </summary>
                            <div class="mt-2 p-3 bg-gray-750 border border-gray-600 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <strong class="text-gray-300">Basic formatting:</strong>
                                        <ul class="mt-1 space-y-1">
                                            <li><code class="bg-gray-600 px-1 rounded">**bold**</code> → <strong>bold</strong></li>
                                            <li><code class="bg-gray-600 px-1 rounded">*italic*</code> → <em>italic</em></li>
                                            <li><code class="bg-gray-600 px-1 rounded">`code`</code> → <code class="bg-gray-600 px-1 rounded">code</code></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong class="text-gray-300">Advanced:</strong>
                                        <ul class="mt-1 space-y-1">
                                            <li><code class="bg-gray-600 px-1 rounded">[link](url)</code> → link</li>
                                            <li><code class="bg-gray-600 px-1 rounded">&gt; quote</code> → blockquote</li>
                                            <li><code class="bg-gray-600 px-1 rounded">- list item</code> → bullet list</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </details>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-300">
                            <i class="fas fa-eye text-green-400 mr-2"></i>Preview
                        </label>
                        <button type="button" onclick="togglePreview()" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                            <i class="fas fa-sync-alt mr-1"></i>Update Preview
                        </button>
                    </div>
                    <div id="preview" class="bg-gray-750 border border-gray-600 rounded-lg p-4 min-h-[100px] text-gray-300">
                        <div class="text-gray-500 italic">Preview will appear here...</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('topic.show', $reply->topic) }}" class="inline-flex items-center px-4 py-2 text-gray-400 hover:text-gray-200 hover:bg-gray-700 rounded-lg transition-colors border border-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>

                        <button type="button" onclick="saveDraft()" class="inline-flex items-center px-4 py-2 text-yellow-400 hover:text-yellow-300 hover:bg-gray-700 rounded-lg transition-colors border border-gray-600">
                            <i class="fas fa-save mr-2"></i>Save Draft
                        </button>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Changes will be marked as edited
                        </div>

                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-blue-500">
                            <i class="fas fa-check mr-2"></i>Update Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Original Reply Reference -->
        <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-gray-100">
                    <i class="fas fa-history text-purple-400 mr-2"></i>Original Reply
                </h3>
                <p class="text-gray-400 text-sm">For reference - this is what you originally posted</p>
            </div>
            <div class="p-6">
                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <div class="flex items-center space-x-3 mb-3">
                        @if($reply->user->profile_picture)
                            <img src="{{ Storage::url($reply->user->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-500">
                        @else
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm border border-gray-500">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-200">{{ $reply->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $reply->created_at->format('M j, Y g:i A') }}</div>
                        </div>
                    </div>
                    <div class="text-gray-300 whitespace-pre-wrap">{{ $reply->getOriginal('content') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gray-750 {
    background-color: #374151;
}
</style>

<script>
function updateCharCount() {
    const content = document.getElementById('content').value;
    document.getElementById('char-count').textContent = content.length;
}

function formatText(type) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    let replacement = '';

    switch(type) {
        case 'bold':
            replacement = `**${selectedText}**`;
            break;
        case 'italic':
            replacement = `*${selectedText}*`;
            break;
        case 'code':
            replacement = `\`${selectedText}\``;
            break;
        case 'link':
            replacement = `[${selectedText}](url)`;
            break;
        case 'quote':
            replacement = `> ${selectedText}`;
            break;
    }

    textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
    textarea.focus();
    updateCharCount();
}

function togglePreview() {
    const content = document.getElementById('content').value;
    const preview = document.getElementById('preview');

    if (content.trim()) {
        // Simple markdown-like preview (you can enhance this)
        let html = content
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code class="bg-gray-600 px-1 rounded">$1</code>')
            .replace(/^> (.*$)/gim, '<blockquote class="border-l-4 border-blue-500 pl-4 italic">$1</blockquote>')
            .replace(/\n/g, '<br>');

        preview.innerHTML = html || '<div class="text-gray-500 italic">Preview will appear here...</div>';
    } else {
        preview.innerHTML = '<div class="text-gray-500 italic">Preview will appear here...</div>';
    }
}

function saveDraft() {
    // You can implement draft saving functionality here
    alert('Draft saving functionality can be implemented here');
}

// Auto-update preview as user types (debounced)
let previewTimeout;
document.getElementById('content').addEventListener('input', function() {
    clearTimeout(previewTimeout);
    previewTimeout = setTimeout(togglePreview, 500);
});

// Initialize character count
updateCharCount();
</script>
@endsection
