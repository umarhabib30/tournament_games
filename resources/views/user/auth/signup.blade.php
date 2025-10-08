<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Gaming Tournament</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'game-blue': '#1e40af',
                        'game-purple': '#7c3aed',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg"></div>
                    <span class="text-xl font-bold text-gray-900">GameTournament</span>
                </div>
                <div class="text-sm text-gray-600">
                    <a href="{{ route('user.login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex min-h-screen justify-center items-start py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <!-- Left Side - Form -->
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Create Your Account</h2>
                <p class="mt-2 text-gray-600">Join the ultimate gaming tournament experience</p>
            </div>

            <!-- Form -->
            <form class="space-y-6" id="signupForm" action="{{ route('user.register') }}" method="POST">
                @csrf
                <!-- Personal Information -->
                <div class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span>*</span></label>
                        <input type="text" id="username" name="username" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <p class="mt-1 text-xs text-gray-500">This will be your display name in tournaments</p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <small>(optional)</small></label>
                        <input type="tel" id="phone" name="phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                    </div>
                </div>

                <!-- Account Security -->
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <div class="mt-2">
                            <div class="text-xs text-gray-600">Password strength:</div>
                            <div class="mt-1 flex space-x-1">
                                <div id="strength-bar-1" class="h-1 w-full bg-gray-200 rounded"></div>
                                <div id="strength-bar-2" class="h-1 w-full bg-gray-200 rounded"></div>
                                <div id="strength-bar-3" class="h-1 w-full bg-gray-200 rounded"></div>
                                <div id="strength-bar-4" class="h-1 w-full bg-gray-200 rounded"></div>
                            </div>
                            <div id="password-feedback" class="mt-1 text-xs text-gray-500">Enter a password</div>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="confirm_password" name="password_confirmation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <div id="password-match" class="mt-1 text-xs hidden"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Account
                    </button>
                </div>

                <!-- Success/Error Messages -->
                <div id="message" class="hidden p-4 rounded-lg text-sm font-medium"></div>
            </form>
        </div>
    </div>

    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];

            if (password.length >= 8) {
                strength++;
            } else {
                feedback.push("at least 8 characters");
            }

            if (/[a-z]/.test(password)) {
                strength++;
            } else {
                feedback.push("lowercase letter");
            }

            if (/[A-Z]/.test(password)) {
                strength++;
            } else {
                feedback.push("uppercase letter");
            }

            if (/[0-9]/.test(password)) {
                strength++;
            } else {
                feedback.push("number");
            }

            return { strength, feedback };
        }

        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const { strength, feedback } = checkPasswordStrength(password);
            const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const feedbackEl = document.getElementById('password-feedback');

            // Reset all bars
            bars.forEach(bar => {
                document.getElementById(bar).className = 'h-1 w-full bg-gray-200 rounded';
            });

            // Fill bars based on strength
            for (let i = 0; i < strength; i++) {
                document.getElementById(bars[i]).className = `h-1 w-full ${colors[strength - 1]} rounded`;
            }

            // Update feedback
            if (password === '') {
                feedbackEl.textContent = 'Enter a password';
                feedbackEl.className = 'mt-1 text-xs text-gray-500';
            } else if (feedback.length === 0) {
                feedbackEl.textContent = 'Strong password';
                feedbackEl.className = 'mt-1 text-xs text-green-600';
            } else {
                feedbackEl.textContent = `Add: ${feedback.join(', ')}`;
                feedbackEl.className = 'mt-1 text-xs text-orange-600';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchEl = document.getElementById('password-match');

            if (confirmPassword === '') {
                matchEl.classList.add('hidden');
                return;
            }

            matchEl.classList.remove('hidden');

            if (password === confirmPassword) {
                matchEl.textContent = 'Passwords match';
                matchEl.className = 'mt-1 text-xs text-green-600';
            } else {
                matchEl.textContent = 'Passwords do not match';
                matchEl.className = 'mt-1 text-xs text-red-600';
            }
        }

        function showMessage(message, type) {
            const messageEl = document.getElementById('message');
            messageEl.textContent = message;
            messageEl.className = `p-4 rounded-lg text-sm font-medium ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
            messageEl.classList.remove('hidden');

            setTimeout(() => {
                messageEl.classList.add('hidden');
            }, 5000);
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', updatePasswordStrength);
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
