<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'Forum') }}</title>
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
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .strength-weak { background: linear-gradient(90deg, #ef4444 0%, #f87171 100%); }
        .strength-medium { background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%); }
        .strength-strong { background: linear-gradient(90deg, #10b981 0%, #34d399 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header Section -->
            <div>
                <div class="text-center">
                    <div class="floating mb-6">
                        <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto shadow-2xl border-4 border-gray-600">
                            <i class="fas fa-user-plus text-3xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-100 mb-2">
                        Join Our Community
                    </h2>
                    <p class="text-gray-400">
                        Create your account to start participating in discussions
                    </p>
                    <div class="mt-4 flex items-center justify-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="form-card rounded-xl shadow-2xl p-8">
                <form class="space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Full Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-user text-green-400 mr-2"></i>Full Name
                        </label>
                        <div class="relative">
                            <input id="name" name="name" type="text" autocomplete="name" required
                                   value="{{ old('name') }}"
                                   class="input-focus appearance-none relative block w-full px-4 py-3 pl-12 bg-gray-700 border border-gray-600 placeholder-gray-400 text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors sm:text-sm"
                                   placeholder="Enter your full name">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

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
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="input-focus appearance-none relative block w-full px-4 py-3 pl-12 pr-12 bg-gray-700 border border-gray-600 placeholder-gray-400 text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors sm:text-sm"
                                   placeholder="Create a strong password"
                                   onkeyup="checkPasswordStrength(this.value)">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <i id="password-toggle" class="fas fa-eye text-gray-400 hover:text-gray-200 transition-colors"></i>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div id="password-strength" class="password-strength bg-gray-600 w-full"></div>
                            <p id="password-text" class="text-xs text-gray-400 mt-1">Password strength will appear here</p>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-shield-alt text-yellow-400 mr-2"></i>Confirm Password
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="input-focus appearance-none relative block w-full px-4 py-3 pl-12 pr-12 bg-gray-700 border border-gray-600 placeholder-gray-400 text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors sm:text-sm"
                                   placeholder="Confirm your password"
                                   onkeyup="checkPasswordMatch()">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-shield-alt text-gray-400"></i>
                            </div>
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <i id="password_confirmation-toggle" class="fas fa-eye text-gray-400 hover:text-gray-200 transition-colors"></i>
                            </button>
                        </div>
                        <div id="password-match" class="mt-2 text-xs hidden">
                            <span id="match-text"></span>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 bg-gray-700 border-gray-600 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-300">
                                I agree to the
                                <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors">Terms of Service</a>
                                and
                                <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <i class="fas fa-user-plus text-green-300 group-hover:text-green-200 transition-colors"></i>
                            </span>
                            Create Your Account
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

                    <!-- Sign In Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-400">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="fas fa-sign-in-alt mr-1"></i>Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center space-y-4">
                <p class="text-xs text-gray-500">
                    By creating an account, you agree to participate respectfully in our community.
                </p>

                <!-- Back to Home -->
                <div>
                    <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-gray-200 transition-colors">
                        <i class="fas fa-home mr-2"></i>Back to homepage
                    </a>
                </div>

                <!-- Community Benefits -->
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-200 mb-3">Join our community and enjoy:</h4>
                    <div class="grid grid-cols-1 gap-2 text-xs text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <span>Participate in meaningful discussions</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <span>Connect with like-minded individuals</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <span>Share your knowledge and experiences</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <span>Get help from our supportive community</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-10 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-10 animate-pulse"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-5 animate-pulse"></div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = document.getElementById(fieldId + '-toggle');

            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength');
            const strengthText = document.getElementById('password-text');

            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) strength += 1;
            else feedback.push('at least 8 characters');

            // Uppercase check
            if (/[A-Z]/.test(password)) strength += 1;
            else feedback.push('uppercase letter');

            // Lowercase check
            if (/[a-z]/.test(password)) strength += 1;
            else feedback.push('lowercase letter');

            // Number check
            if (/\d/.test(password)) strength += 1;
            else feedback.push('number');

            // Special character check
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;
            else feedback.push('special character');

            // Update strength bar and text
            strengthBar.className = 'password-strength w-full';

            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Weak - Add: ' + feedback.slice(0, 2).join(', ');
                strengthText.className = 'text-xs text-red-400 mt-1';
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Medium - Consider adding: ' + feedback.slice(0, 1).join(', ');
                strengthText.className = 'text-xs text-yellow-400 mt-1';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Strong password!';
                strengthText.className = 'text-xs text-green-400 mt-1';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('password-match');
            const matchText = document.getElementById('match-text');

            if (confirmPassword.length > 0) {
                matchDiv.classList.remove('hidden');

                if (password === confirmPassword) {
                    matchText.innerHTML = '<i class="fas fa-check-circle text-green-400 mr-1"></i><span class="text-green-400">Passwords match!</span>';
                } else {
                    matchText.innerHTML = '<i class="fas fa-times-circle text-red-400 mr-1"></i><span class="text-red-400">Passwords do not match</span>';
                }
            } else {
                matchDiv.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
