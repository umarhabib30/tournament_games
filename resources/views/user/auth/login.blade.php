<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Gaming Tournament</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'game-blue': '#1e40af',
                        'game-purple': '#7c3aed',
                        'game-dark': '#0f172a',
                    },
                    animation: {
                        fadeIn: 'fadeIn 1s ease forwards',
                        float: 'float 4s ease-in-out infinite',
                        slideUp: 'slideUp 0.8s ease forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-15px)' } },
                        slideUp: { '0%': { transform: 'translateY(40px)', opacity: 0 }, '100%': { transform: 'translateY(0)', opacity: 1 } },
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-game-dark via-game-blue to-game-purple min-h-screen flex flex-col overflow-hidden">

  <!-- Header -->
  <header class="sticky top-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20 text-white animate-fadeIn">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl animate-float"></div>
          <span class="text-xl md:text-2xl font-extrabold tracking-wide">Game<span class="text-purple-400">Verse</span></span>
        </div>
        <div class="text-sm">
          <a href="{{ route('user.signup') }}" class="bg-white/10 px-3 py-2 md:px-4 md:py-2 rounded-lg hover:bg-white/20 transition-all duration-300 text-sm md:text-base">
            Sign Up
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 flex items-center justify-center px-4 py-6">
    <div class="relative max-w-md w-full bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-6 md:p-8 space-y-6 animate-slideUp overflow-hidden">

      <!-- Floating Orbs (Animated Background) -->
      <div class="absolute top-[-20px] left-[-20px] md:top-[-30px] md:left-[-30px] w-12 h-12 md:w-16 md:h-16 bg-blue-500/50 rounded-full animate-float blur-2xl"></div>
      <div class="absolute bottom-[-30px] right-[-30px] md:bottom-[-40px] md:right-[-40px] w-20 h-20 md:w-24 md:h-24 bg-purple-500/40 rounded-full animate-float blur-3xl"></div>

      <!-- Form Header -->
      <div class="text-center space-y-2">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white drop-shadow-md">Welcome Back</h2>
        <p class="text-gray-200 text-sm md:text-base">Please sign in to join tournament</p>
      </div>

      <!-- Form -->
      <form id="loginForm" action="{{ route('user.authenticate') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label for="email" class="block text-sm font-medium text-gray-200 mb-1">Email Address</label>
          <input type="email" id="email" name="email" required
            class="w-full px-4 py-3 rounded-xl bg-white/20 text-white placeholder-gray-300 border border-white/30 focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-300 text-sm md:text-base">
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-200 mb-1">Password</label>
          <input type="password" id="password" name="password" required
            class="w-full px-4 py-3 rounded-xl bg-white/20 text-white placeholder-gray-300 border border-white/30 focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-300 text-sm md:text-base">
        </div>

        <!-- Remember Me -->
        <div class="flex items-center space-x-2">
          <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-purple-400 focus:ring-purple-400">
          <label for="remember" class="text-sm text-gray-200">Remember me for 30 days</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="submitBtn"
          class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-[1.05] hover:shadow-purple-500/50 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 text-sm md:text-base">
          Sign In
        </button>

        <!-- Success/Error Message -->
        <div id="message" class="hidden p-4 rounded-lg text-sm font-medium"></div>
      </form>

      <!-- Footer Text -->
      <p class="text-center text-gray-400 text-xs md:text-sm mt-4">
        © {{ date('Y') }} GameTournament. All rights reserved.
      </p>
    </div>
  </main>
</body>
</html>
