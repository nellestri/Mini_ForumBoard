@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-gray-100">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('forum.index') }}" class="hover:text-blue-400 transition-colors">
                <i class="fas fa-home mr-1"></i>Forum
            </a>
            <i class="fas fa-chevron-right text-gray-600"></i>
            <a href="{{ route('topic.show', $topic) }}" class="hover:text-blue-400 transition-colors truncate max-w-xs">
                {{ Str::limit($topic->title, 30) }}
            </a>
            <i class="fas fa-chevron-right text-gray-600"></i>
            <span class="text-gray-300">Edit Topic</span>
        </div>

        <!-- Main Container -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-100 flex items-center">
                            <i class="fas fa-edit text-orange-400 mr-3"></i>Edit Topic
                        </h1>
                        <p class="text-gray-400 mt-2">Update your topic information</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($topic->user->profile_picture)
                            <img src="{{ Storage::url($topic->user->profile_picture) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-gray-500">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold border border-gray-500">
                                {{ strtoupper(substr($topic->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-200">{{ $topic->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $topic->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('topic.update', $topic) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title Field -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-heading text-blue-400 mr-2"></i>Topic Title
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $topic->title) }}"
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Enter a descriptive title for your topic"
                           required
                           maxlength="255">
                    @error('title')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Content Field -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-align-left text-green-400 mr-2"></i>Content
                    </label>

                    <!-- Simple Formatting Toolbar -->
                    <div class="bg-gray-750 border border-gray-600 rounded-t-lg px-4 py-2 flex items-center space-x-2">
                        <button type="button" onclick="formatText('bold')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Bold">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" onclick="formatText('italic')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Italic">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" onclick="formatText('code')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Code">
                            <i class="fas fa-code"></i>
                        </button>
                        <div class="w-px h-6 bg-gray-600"></div>
                        <button type="button" onclick="formatText('link')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Link">
                            <i class="fas fa-link"></i>
                        </button>
                        <button type="button" onclick="formatText('quote')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="Quote">
                            <i class="fas fa-quote-left"></i>
                        </button>
                        <button type="button" onclick="formatText('list')" class="p-2 text-gray-400 hover:text-gray-200 hover:bg-gray-600 rounded transition-colors" title="List">
                            <i class="fas fa-list-ul"></i>
                        </button>
                    </div>

                    <textarea id="content"
                              name="content"
                              rows="10"
                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 border-t-0 rounded-b-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-vertical"
                              placeholder="Write your topic content here... You can use markdown formatting."
                              required>{{ old('content', $topic->content) }}</textarea>

                    @error('content')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-images text-purple-400 mr-2"></i>Images
                    </label>

                    <!-- Current Images -->
                    @if($topic->images && count($topic->images) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-400 mb-2">Current Images:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($topic->images as $index => $image)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($image) }}" alt="Topic image" class="w-full h-24 object-cover rounded-lg border border-gray-600">
                                        <button type="button" onclick="removeImage({{ $index }})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Image Upload -->
                    <div class="border-2 border-dashed border-gray-600 rounded-lg p-6 text-center hover:border-gray-500 transition-colors">
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                        <label for="images" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-400">Click to upload new images or drag and drop</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB each (max 5 images)</p>
                        </label>
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>

                    @error('images')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('topic.show', $topic) }}" class="inline-flex items-center px-4 py-2 text-gray-400 hover:text-gray-200 hover:bg-gray-700 rounded-lg transition-colors border border-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>

                        <div class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>Changes will be marked as edited
                        </div>
                    </div>

                    <!-- UPDATE TOPIC BUTTON -->
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-orange-500/50">
                        <i class="fas fa-save mr-2"></i>
                        Update Topic
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Topic Stats -->
        <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-gray-100">
                    <i class="fas fa-chart-bar text-blue-400 mr-2"></i>Topic Statistics
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-750 border border-blue-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $topic->views ?? 0 }}</div>
                        <div class="text-sm text-gray-400">Views</div>
                    </div>
                    <div class="bg-gray-750 border border-green-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-400">{{ $topic->replies()->count() }}</div>
                        <div class="text-sm text-gray-400">Replies</div>
                    </div>
                    <div class="bg-gray-750 border border-purple-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-400">{{ $topic->created_at->diffInDays() }}</div>
                        <div class="text-sm text-gray-400">Days Old</div>
                    </div>
                    <div class="bg-gray-750 border border-yellow-600/50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-400">
                            @if($topic->replies()->count() > 0)
                                {{ $topic->replies()->latest()->first()->created_at->diffInHours() }}h
                            @else
                                0h
                            @endif
                        </div>
                        <div class="text-sm text-gray-400">Last Activity</div>
                    </div>
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
        case 'list':
            replacement = `- ${selectedText}`;
            break;
    }

    textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
    textarea.focus();
}

function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');

        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="w-full h-24 object-cover rounded-lg border border-gray-600">
                        <button type="button" onclick="removePreviewImage(${index})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.classList.add('hidden');
    }
}

function removeImage(index) {
    if (confirm('Are you sure you want to remove this image?')) {
        const imageInputs = document.querySelectorAll('input[name="existing_images[]"]');
        if (imageInputs[index]) {
            imageInputs[index].remove();
        }
        location.reload();
    }
}

function removePreviewImage(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();

    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });

    input.files = dt.files;
    previewImages(input);
}
</script>
@endsection
