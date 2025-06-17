@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-gray-900 min-h-screen text-gray-100 p-6">
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h1 class="text-2xl font-semibold text-gray-100">Create New Topic</h1>
            <p class="text-gray-400">Start a new discussion in the forum</p>
        </div>

        <form method="POST" action="{{ route('topic.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                    Topic Title
                </label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') }}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter a descriptive title for your topic"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-300 mb-2">
                    Content
                </label>
                <textarea id="content"
                          name="content"
                          rows="8"
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Write your topic content here..."
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload Section - GitHub Dark Style -->
            <div class="mb-6">
                <label for="images" class="block text-sm font-medium text-gray-300 mb-2">
                    Images (Optional)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-md hover:border-gray-500 transition-colors bg-gray-750">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-400">
                            <label for="images" class="relative cursor-pointer bg-gray-750 rounded-md font-medium text-blue-400 hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload images</span>
                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each</p>
                    </div>
                </div>
                @error('images.*')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror

                <!-- Image Preview - GitHub Dark Style -->
                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4" style="display: none;"></div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('forum.index') }}" class="text-gray-400 hover:text-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Forum
                </a>

                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 border border-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Topic
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        preview.style.display = 'grid';

        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-600">
                    <button type="button" onclick="removeImage(${index})" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-500 border border-red-500">
                        ×
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        preview.style.display = 'none';
    }
}

function removeImage(index) {
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

<style>
.bg-gray-750 {
    background-color: #374151;
}
</style>
@endsection
