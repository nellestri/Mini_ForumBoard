<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Forum') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
        }
        .form-card {
            background: linear-gradient(145deg, #374151, #4b5563);
            border: 1px solid #6b7280;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header Section -->
            <div>
                <div class="text-center">
                    <div class="floating mb-6">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto shadow-2xl border-4 border-gray-600">
                            <i class="fas fa-comments text-3xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-100 mb-2">
                        Welcome Back
                    </h2>
                    <p class="text-gray-400">
                        Sign in to your account to join the discussion
                    </p>
                    <div class="mt-4 flex items-center justify-center space-x-2">
                        <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Login Form -->
            <div class="form-card rounded-xl shadow-2xl p-8">
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-envelope text-blue-400 mr-2"></i>Email address
                        </label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}"
                                   class="input-focus appearance-none relative block w-full px-4 py-3 pl-12 bg-gray-700 border border-gray-600 placeholder-gray-400 text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors sm:text-sm"
                                   placeholder="Enter your email address">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-lock text-purple-400 mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="input-focus appearance-none relative block w-full px-4 py-3 pl-12 bg-gray-700 border border-gray-600 placeholder-gray-400 text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors sm:text-sm"
                                   placeholder="Enter your password">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-300">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <i class="fas fa-sign-in-alt text-blue-300 group-hover:text-blue-200 transition-colors"></i>
                            </span>
                            Sign in to your account
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-gray-700 text-gray-400">or</span>
                        </div>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-400">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-medium text-green-400 hover:text-green-300 transition-colors">
                                <i class="fas fa-user-plus mr-1"></i>Create new account
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center space-y-4">
                <p class="text-xs text-gray-500">
                    Join our community to share ideas, ask questions, and connect with others.
                </p>

                <!-- Back to Home -->
                <div>
                    <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-gray-200 transition-colors">
                        <i class="fas fa-home mr-2"></i>Back to homepage
                    </a>
                </div>

                <!-- Social Features Preview -->
                <div class="flex justify-center space-x-6 text-gray-500">
                    <div class="flex items-center text-xs">
                        <i class="fas fa-users mr-1 text-blue-400"></i>
                        <span>Active Community</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <i class="fas fa-shield-alt mr-1 text-green-400"></i>
                        <span>Secure Platform</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <i class="fas fa-comments mr-1 text-purple-400"></i>
                        <span>Rich Discussions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-10 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-10 animate-pulse"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-5 animate-pulse"></div>
    </div>
</body>
</html>
