@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 bg-gray-900 min-h-screen text-gray-100 p-6">
    <!-- Header -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-100">Account Settings</h1>
                    <p class="text-gray-400">Manage your account information and preferences</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Picture Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-user-circle text-blue-400 mr-2"></i>Profile Picture
            </h2>
        </div>
        <div class="p-6">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-gray-600 shadow-lg">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-gray-600 shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <form method="POST" action="{{ route('settings.profile-picture') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="profile_picture" class="block text-sm font-medium text-gray-300 mb-2">
                                Choose new profile picture
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                   class="block w-full text-sm text-gray-300 bg-gray-700 border border-gray-600 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-700 file:text-blue-100 hover:file:bg-blue-600 transition-colors">
                            @error('profile_picture')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors border border-blue-600">
                                <i class="fas fa-upload mr-2"></i>Upload Picture
                            </button>

                            @if($user->profile_picture)
                                <form method="POST" action="{{ route('settings.remove-profile-picture') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors border border-red-600" onclick="return confirm('Are you sure you want to remove your profile picture?')">
                                        <i class="fas fa-trash mr-2"></i>Remove Picture
                                    </button>
                                </form>
                            @endif
                        </div>
                    </form>

                    <p class="text-sm text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1 text-blue-400"></i>
                        Recommended: Square image, at least 200x200 pixels. Max file size: 2MB.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-user-edit text-green-400 mr-2"></i>Profile Information
            </h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('settings.profile') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Full Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-300 mb-2">
                        Bio
                    </label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."
                              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>Location
                        </label>
                        <input type="text" id="location" name="location" value="{{ old('location', $user->location) }}" placeholder="City, Country"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('location')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-globe text-purple-400 mr-1"></i>Website
                        </label>
                        <input type="url" id="website" name="website" value="{{ old('website', $user->website) }}" placeholder="https://example.com"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('website')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-md hover:bg-green-600 transition-colors border border-green-600">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-key text-yellow-400 mr-2"></i>Change Password
            </h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('settings.password') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">
                        Current Password
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            New Password
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-300 mb-2">Password Requirements:</h4>
                    <ul class="text-sm text-gray-400 space-y-1">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>At least 8 characters long</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Contains uppercase and lowercase letters</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Contains at least one number</li>
                    </ul>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-yellow-700 text-white px-6 py-2 rounded-md hover:bg-yellow-600 transition-colors border border-yellow-600">
                        <i class="fas fa-key mr-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold text-gray-100">
                <i class="fas fa-info-circle text-purple-400 mr-2"></i>Account Information
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-400 mb-1">
                        <i class="fas fa-calendar-alt text-blue-400 mr-1"></i>Member Since
                    </label>
                    <p class="text-gray-100 font-semibold">{{ $user->created_at->format('F j, Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                </div>

                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-400 mb-1">
                        <i class="fas fa-user-tag text-green-400 mr-1"></i>Account Type
                    </label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->isAdmin() ? 'bg-red-900 text-red-200 border border-red-700' : 'bg-blue-900 text-blue-200 border border-blue-700' }}">
                        @if($user->isAdmin())
                            <i class="fas fa-crown mr-1"></i>Administrator
                        @else
                            <i class="fas fa-user mr-1"></i>Member
                        @endif
                    </span>
                </div>

                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-400 mb-1">
                        <i class="fas fa-comments text-purple-400 mr-1"></i>Topics Created
                    </label>
                    <p class="text-2xl font-bold text-purple-400">{{ $user->topics()->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total discussions started</p>
                </div>

                <div class="bg-gray-750 border border-gray-600 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-400 mb-1">
                        <i class="fas fa-reply text-green-400 mr-1"></i>Replies Posted
                    </label>
                    <p class="text-2xl font-bold text-green-400">{{ $user->replies()->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total responses given</p>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="mt-6 bg-gray-750 border border-gray-600 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-300 mb-3">
                    <i class="fas fa-chart-line text-yellow-400 mr-1"></i>Activity Summary
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-lg font-semibold text-blue-400">{{ $user->topics()->where('created_at', '>=', now()->subMonth())->count() }}</div>
                        <div class="text-xs text-gray-500">Topics this month</div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-green-400">{{ $user->replies()->where('created_at', '>=', now()->subMonth())->count() }}</div>
                        <div class="text-xs text-gray-500">Replies this month</div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-purple-400">{{ $user->topics()->sum('views') ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Total topic views</div>
                    </div>
                    <div>
                        @php
                            $totalRepliesReceived = 0;
                            foreach($user->topics as $topic) {
                                $totalRepliesReceived += $topic->replies()->count();
                            }
                        @endphp
                        <div class="text-lg font-semibold text-yellow-400">{{ $totalRepliesReceived }}</div>
                        <div class="text-xs text-gray-500">Replies received</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-gray-800 border border-red-700 rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-red-700">
            <h2 class="text-lg font-semibold text-red-400">
                <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-red-900 bg-opacity-20 border border-red-700 rounded-lg p-4">
                <h4 class="text-red-400 font-medium mb-2">Delete Account</h4>
                <p class="text-gray-300 text-sm mb-4">
                    Once you delete your account, there is no going back. Please be certain.
                    All your topics, replies, and profile information will be permanently removed.
                </p>
                <button type="button" class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors border border-red-600" onclick="alert('Account deletion feature coming soon!')">
                    <i class="fas fa-trash mr-2"></i>Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gray-750 {
    background-color: #374151;
}
</style>
@endsection
