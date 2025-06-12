<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Forum') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .dropdown-menu {
            display: none;
            animation: fadeIn 0.2s ease-in-out;
        }

        .dropdown-menu.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .user-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .dropdown-arrow {
            transition: transform 0.2s ease-in-out;
        }

        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo Section -->
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center text-xl font-bold text-gray-800 hover:text-blue-600 transition duration-200">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-comments text-white text-sm"></i>
                        </div>
                        Mini Forum
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('forum.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('forum.index') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-home mr-2"></i>Forum
                    </a>

                    <a href="{{ route('topic.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>New Topic
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications (placeholder for future feature) -->
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900 p-2 rounded-full hover:bg-gray-100 transition duration-200">
                            <i class="fas fa-bell text-lg"></i>
                            <!-- Notification badge -->
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full notification-badge"></span>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="user-dropdown-button" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition duration-200">
                            <!-- User Avatar -->
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-8 h-8 user-avatar rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ Auth::user()->initials }}
                                </div>
                            @endif

                            <!-- User Info -->
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium">{{ Str::limit(Auth::user()->name, 15) }}</div>
                                <div class="text-xs text-gray-500">
                                    @if(Auth::user()->isAdmin())
                                        <i class="fas fa-crown text-yellow-500 mr-1"></i>Admin
                                    @else
                                        <i class="fas fa-user text-blue-500 mr-1"></i>Member
                                    @endif
                                </div>
                            </div>

                            <i id="dropdown-arrow" class="fas fa-chevron-down text-xs text-gray-400 dropdown-arrow"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown-menu" class="dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                            <!-- User Info Header -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    @if(Auth::user()->profile_picture)
                                        <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-10 h-10 user-avatar rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ Auth::user()->initials }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                        @if(Auth::user()->isAdmin())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                <i class="fas fa-crown mr-1"></i>Administrator
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-tachometer-alt w-5 text-center mr-3 text-blue-500"></i>
                                    <div>
                                        <div class="font-medium">Dashboard</div>
                                        <div class="text-xs text-gray-500">View your activity</div>
                                    </div>
                                </a>

                                <a href="{{ route('user.topics') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition duration-200 {{ request()->routeIs('user.topics') ? 'bg-green-50 text-green-700' : '' }}">
                                    <i class="fas fa-list w-5 text-center mr-3 text-green-500"></i>
                                    <div>
                                        <div class="font-medium">My Topics</div>
                                        <div class="text-xs text-gray-500">Manage your topics</div>
                                    </div>
                                </a>

                                <a href="{{ route('user.replies') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition duration-200 {{ request()->routeIs('user.replies') ? 'bg-purple-50 text-purple-700' : '' }}">
                                    <i class="fas fa-reply w-5 text-center mr-3 text-purple-500"></i>
                                    <div>
                                        <div class="font-medium">My Replies</div>
                                        <div class="text-xs text-gray-500">View your replies</div>
                                    </div>
                                </a>

                                @if(Auth::user()->isAdmin())
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition duration-200 {{ request()->routeIs('admin.*') ? 'bg-red-50 text-red-700' : '' }}">
                                        <i class="fas fa-cog w-5 text-center mr-3 text-red-500"></i>
                                        <div>
                                            <div class="font-medium">Admin Panel</div>
                                            <div class="text-xs text-gray-500">Manage forum</div>
                                        </div>
                                    </a>
                                @endif
                            </div>

                            <!-- Settings & Logout -->
                            <div class="border-t border-gray-100 py-1">
                                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-200">
                                    <i class="fas fa-cog w-5 text-center mr-3 text-gray-500"></i>
                                    <div>
                                        <div class="font-medium">Settings</div>
                                        <div class="text-xs text-gray-500">Account preferences</div>
                                    </div>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-200">
                                        <i class="fas fa-sign-out-alt w-5 text-center mr-3 text-red-500"></i>
                                        <div class="text-left">
                                            <div class="font-medium">Sign Out</div>
                                            <div class="text-xs text-red-400">End your session</div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button type="button" class="text-gray-600 hover:text-gray-900 p-2 rounded-md" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('forum.index') }}" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-home mr-2"></i>Forum
                </a>
                <a href="{{ route('topic.create') }}" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-plus mr-2"></i>New Topic
                </a>
                <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-cog mr-2"></i>Admin Panel
                    </a>
                @endif
                <button onclick="confirmLogout()" class="block w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
                </button>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-3"></i>
                    <div class="text-green-700">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <div class="text-red-700">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sign Out</h3>
                    <p class="text-gray-600 mb-6">Are you sure you want to sign out of your account?</p>

                    <div class="flex space-x-3">
                        <button onclick="hideLogoutModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200">
                            Cancel
                        </button>
                        <button onclick="document.getElementById('logout-form').submit()" class="flex-1 px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg transition duration-200">
                            Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // User dropdown functionality
        const dropdownButton = document.getElementById('user-dropdown-button');
        const dropdownMenu = document.getElementById('user-dropdown-menu');
        const dropdownArrow = document.getElementById('dropdown-arrow');

        function toggleDropdown() {
            const isOpen = dropdownMenu.classList.contains('show');

            if (isOpen) {
                // Close dropdown
                dropdownMenu.classList.remove('show');
                dropdownArrow.classList.remove('rotated');
            } else {
                // Open dropdown
                dropdownMenu.classList.add('show');
                dropdownArrow.classList.add('rotated');
            }
        }

        function closeDropdown() {
            dropdownMenu.classList.remove('show');
            dropdownArrow.classList.remove('rotated');
        }

        // Toggle dropdown on button click
        dropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                closeDropdown();
            }
        });

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        // Prevent dropdown from closing when clicking inside the menu
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Mobile menu functionality
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Logout modal functionality
        function confirmLogout() {
            closeDropdown(); // Close dropdown first
            document.getElementById('logout-modal').classList.remove('hidden');
        }

        function hideLogoutModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('logout-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideLogoutModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideLogoutModal();
            }
        });
    </script>
</body>
</html>
