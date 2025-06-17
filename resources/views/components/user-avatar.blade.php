@props(['user', 'size' => 'md', 'showName' => false, 'linkToProfile' => true])

@php
    $sizeClasses = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
        '2xl' => 'w-20 h-20 text-xl',
    ];

    $avatarClass = $sizeClasses[$size] ?? $sizeClasses['md'];

    // GitHub dark mode color palette for initials
    $colors = [
        'bg-blue-600',
        'bg-green-600',
        'bg-purple-600',
        'bg-pink-600',
        'bg-indigo-600',
        'bg-red-600',
        'bg-yellow-600',
        'bg-teal-600'
    ];

    $colorIndex = ord(strtoupper($user->name[0])) % count($colors);
    $bgColor = $colors[$colorIndex];
@endphp

<div class="flex items-center {{ $showName ? 'space-x-2' : '' }}">
    @if($linkToProfile)
        <a href="{{ route('user.profile', $user) }}" class="flex-shrink-0">
    @endif

    @if($user->profile_picture_url)
        <img src="{{ $user->profile_picture_url }}"
             alt="{{ $user->name }}"
             class="{{ $avatarClass }} rounded-full object-cover border border-gray-600 hover:border-gray-500 transition-colors">
    @else
        <div class="{{ $avatarClass }} {{ $bgColor }} rounded-full flex items-center justify-center text-white font-medium border border-gray-600 hover:border-gray-500 transition-colors">
            {{ $user->initials }}
        </div>
    @endif

    @if($linkToProfile)
        </a>
    @endif

    @if($showName)
        @if($linkToProfile)
            <a href="{{ route('user.profile', $user) }}" class="font-semibold text-gray-100 hover:text-blue-400 transition-colors">
                {{ $user->name }}
            </a>
        @else
            <span class="font-semibold text-gray-100">{{ $user->name }}</span>
        @endif

        @if($user->isAdmin())
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200 border border-yellow-700">
                <i class="fas fa-star mr-1"></i>Admin
            </span>
        @endif
    @endif
</div>
