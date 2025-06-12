<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mini Forum') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        .dropdown:hover .dropdown-menu {
            display: block !important;
        }

        .dropdown-menu {
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo Section -->
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center text-xl font-bold text-gray-800 hover:text-blue-600 transition duration-200">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-comments text-white text-sm"></i>
                        </div>
                        Mini Forum
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('forum.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-list-alt mr-1"></i>Browse Forum
                        </a>

                        <!-- User Dropdown (same as in app.blade.php) -->
                        <div class="relative dropdown">
                            <button class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition duration-200">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
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
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>

                            <!-- Same dropdown menu as in app.blade.php -->
                            <div class="dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition duration-200">
                                        <i class="fas fa-tachometer-alt w-5 text-center mr-3 text-blue-500"></i>
                                        <div>
                                            <div class="font-medium">Dashboard</div>
                                            <div class="text-xs text-gray-500">View your activity</div>
                                        </div>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition duration-200">
                                            <i class="fas fa-cog w-5 text-center mr-3 text-red-500"></i>
                                            <div>
                                                <div class="font-medium">Admin Panel</div>
                                                <div class="text-xs text-gray-500">Manage forum</div>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-gray-100 py-1">
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form-welcome">
                                        @csrf
                                        <button type="button" onclick="confirmLogoutWelcome()" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-200">
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
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Mini Forum</h1>
                    <p class="text-xl mb-8">Join our community to discuss ideas, share knowledge, and connect with others.</p>

                    @guest
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 text-center">
                                Create Account
                            </a>
                            <a href="{{ route('login') }}" class="border border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-white hover:bg-opacity-10 text-center">
                                Sign In
                            </a>
                        </div>
                    @else
                        <a href="{{ route('forum.index') }}" class="bg-white text-indigo-700 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 inline-block">
                            Go to Forum
                        </a>
                    @endguest
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="floating text-center">
                        <i class="fas fa-comments text-9xl text-white opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Why Join Our Community?</h2>
                <p class="mt-4 text-xl text-gray-600">Discover the benefits of being part of our forum</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-md transition duration-300">
                    <div class="text-center mb-4">
                        <i class="fas fa-users text-4xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Active Community</h3>
                    <p class="text-gray-600 text-center">Connect with like-minded individuals and engage in meaningful discussions.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-md transition duration-300">
                    <div class="text-center mb-4">
                        <i class="fas fa-comments text-4xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Rich Discussions</h3>
                    <p class="text-gray-600 text-center">Share your thoughts and learn from diverse perspectives on various topics.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-md transition duration-300">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt text-4xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Safe Environment</h3>
                    <p class="text-gray-600 text-center">Enjoy a moderated platform where respectful communication is prioritized.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-indigo-600">{{ \App\Models\User::count() }}</div>
                    <div class="mt-2 text-lg text-gray-600">Users</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600">{{ \App\Models\Topic::count() }}</div>
                    <div class="mt-2 text-lg text-gray-600">Topics</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600">{{ \App\Models\Reply::count() }}</div>
                    <div class="mt-2 text-lg text-gray-600">Replies</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600">24/7</div>
                    <div class="mt-2 text-lg text-gray-600">Availability</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @guest
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Join the Conversation?</h2>
            <p class="text-xl text-gray-600 mb-8">Create an account today and become part of our growing community.</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700">
                    Create New Account
                </a>
                <a href="{{ route('login') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300">
                    Sign In
                </a>
            </div>
        </div>
    </div>
    @endguest

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Mini Forum</h3>
                    <p class="text-gray-400">A community-driven discussion platform for sharing ideas and connecting with others.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('forum.index') }}" class="text-gray-400 hover:text-white">Forum</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Register</a></li>
                        @else
                            <li><a href="{{ route('user.dashboard') }}" class="text-gray-400 hover:text-white">Dashboard</a></li>
                        @endguest
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Community</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Guidelines</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Mini Forum. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script>
function confirmLogoutWelcome() {
    if (confirm('Are you sure you want to sign out?')) {
        document.getElementById('logout-form-welcome').submit();
    }
}

// Dropdown functionality
document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.dropdown');
    if (dropdown) {
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        if (!dropdown.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    }
});

// Show dropdown on hover
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    if (dropdown) {
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        dropdown.addEventListener('mouseenter', function() {
            dropdownMenu.style.display = 'block';
        });
        dropdown.addEventListener('mouseleave', function() {
            dropdownMenu.style.display = 'none';
        });
    }
});
</script>
</body>
</html>
