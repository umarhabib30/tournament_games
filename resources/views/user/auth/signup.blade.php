<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - The Genius Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'game-blue': '#1e40af',
                        'game-purple': '#7c3aed',
                    },
                    animation: {
                        fadeIn: 'fadeIn 1s ease-in-out forwards',
                        slideUp: 'slideUp 0.8s ease-in-out forwards',
                        float: 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: 0 },
                            '100%': { opacity: 1 },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(40px)', opacity: 0 },
                            '100%': { transform: 'translateY(0)', opacity: 1 },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-gradient-to-br from-[#0f172a] via-[#1e3a8a] to-[#7c3aed] min-h-screen flex flex-col">
    <!-- Header -->
    <header class="top-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20 text-white animate-fadeIn">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
               <div class="flex items-center space-x-3">
      <!-- Logo Image -->
          <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-[100px] h-[100px]object-contain" />

    </div>
                <div class="text-sm">
                    <a href="{{ route('user.login') }}" class="bg-white/10 px-3 py-2 md:px-4 md:py-2 rounded-lg hover:bg-white/20 transition-all duration-300 text-sm md:text-base">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col mt-5 mb-6 items-center justify-center flex-1 px-4 sm:px-6 animate-fadeIn min-h-[calc(100vh-80px)]">
        <div class="relative w-full max-w-md sm:max-w-lg bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-4 sm:p-6 space-y-2 text-white animate-slideUp overflow-hidden">

            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white drop-shadow-md">Create Your Account</h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-200">Join the ultimate The Genius Arena experience</p>
            </div>

            <!-- Form -->
            <form id="signupForm" action="{{ route('user.register') }}" method="POST" class="space-y-2 sm:space-y-3">
                @csrf

                <!-- Personal Info -->
                <div class="space-y-2 sm:space-y-3">
                    <div>
                        <label for="username" class="block text-xs sm:text-sm font-medium text-gray-200 mb-1">Username <span>*</span></label>
                        <input type="text" id="username" name="username" required
                               class="w-full px-3 py-2 sm:py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-xs sm:text-sm">
                        <p class="mt-1 text-[9px] sm:text-xs text-gray-300">This will be your display name in tournaments</p>
                    </div>

                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-200 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 sm:py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-xs sm:text-sm">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs sm:text-sm font-medium text-gray-200 mb-1">Phone Number <small>(optional)</small></label>
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-3 py-2 sm:py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-xs sm:text-sm">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2 sm:space-y-3">
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-200 mb-1">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 sm:py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-xs sm:text-sm">

                        <!-- Password Strength -->
                        <div class="mt-1 sm:mt-2">
                            <div class="text-[9px] sm:text-xs text-gray-300">Password strength:</div>
                            <div class="mt-1 flex space-x-1">
                                <div id="strength-bar-1" class="h-1 w-full bg-gray-600 rounded"></div>
                                <div id="strength-bar-2" class="h-1 w-full bg-gray-600 rounded"></div>
                                <div id="strength-bar-3" class="h-1 w-full bg-gray-600 rounded"></div>
                                <div id="strength-bar-4" class="h-1 w-full bg-gray-600 rounded"></div>
                            </div>
                            <div id="password-feedback" class="mt-1 text-[9px] sm:text-xs text-gray-300">Enter a password</div>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-xs sm:text-sm font-medium text-gray-200 mb-1">Confirm Password</label>
                        <input type="password" id="confirm_password" name="password_confirmation" required
                               class="w-full px-3 py-2 sm:py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-xs sm:text-sm">
                        <div id="password-match" class="mt-1 text-[9px] sm:text-xs hidden"></div>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" id="submitBtn"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-2 sm:py-2.5 px-3 sm:px-4 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-[1.03] hover:shadow-purple-500/30 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:ring-offset-transparent text-xs sm:text-sm">
                        Create Account
                    </button>
                </div>

            </form>
        </div>
    </main>

    <script>
        // Password strength & match logic (unchanged)
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];
            if (password.length >= 8) strength++; else feedback.push("at least 8 characters");
            if (/[a-z]/.test(password)) strength++; else feedback.push("lowercase letter");
            if (/[A-Z]/.test(password)) strength++; else feedback.push("uppercase letter");
            if (/[0-9]/.test(password)) strength++; else feedback.push("number");
            return { strength, feedback };
        }

        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const { strength, feedback } = checkPasswordStrength(password);
            const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const feedbackEl = document.getElementById('password-feedback');

            bars.forEach(bar => {
                document.getElementById(bar).className = 'h-1 w-full bg-gray-600 rounded';
            });

            for (let i = 0; i < strength; i++) {
                document.getElementById(bars[i]).className = `h-1 w-full ${colors[strength - 1]} rounded`;
            }

            if (password === '') {
                feedbackEl.textContent = 'Enter a password';
                feedbackEl.className = 'mt-1 text-xs text-gray-300';
            } else if (feedback.length === 0) {
                feedbackEl.textContent = 'Strong password';
                feedbackEl.className = 'mt-1 text-xs text-green-400';
            } else {
                feedbackEl.textContent = `Add: ${feedback.join(', ')}`;
                feedbackEl.className = 'mt-1 text-xs text-yellow-400';
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
                matchEl.className = 'mt-1 text-xs text-green-400';
            } else {
                matchEl.textContent = 'Passwords do not match';
                matchEl.className = 'mt-1 text-xs text-red-400';
            }
        }

        function showMessage(message, type) {
            const messageEl = document.getElementById('message');
            messageEl.textContent = message;
            messageEl.className = `p-4 rounded-lg text-sm font-medium ${type === 'success' ? 'bg-green-200 text-green-900' : 'bg-red-200 text-red-900'}`;
            messageEl.classList.remove('hidden');
            setTimeout(() => { messageEl.classList.add('hidden'); }, 5000);
        }

        document.getElementById('password').addEventListener('input', updatePasswordStrength);
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
