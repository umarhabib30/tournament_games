<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameVerse - Premium Brain Games</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('style')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;600;700&display=swap');

        .game-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .game-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .neon-glow {
            text-shadow: 0 0 10px currentColor, 0 0 20px currentColor, 0 0 40px currentColor;
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }

        @keyframes pulseGlow {
            from {
                box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
            }

            to {
                box-shadow: 0 0 30px rgba(139, 92, 246, 0.8), 0 0 40px rgba(139, 92, 246, 0.6);
            }
        }

        .gradient-text {
            background: linear-gradient(45deg, #8b5cf6, #06b6d4, #10b981, #f59e0b);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 300% 300%;
            animation: gradientShift 4s ease infinite;
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #5b21b6 50%, #7c3aed 75%, #2563eb 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }
    </style>
</head>

<body class="min-h-screen">
    <!-- Header -->
    <header class="relative z-10 px-6 py-4">
        <nav class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-purple-500 to-cyan-500 rounded-lg flex items-center justify-center pulse-glow">
                    <span class="text-white font-bold text-xl">G</span>
                </div>
                <h1 class="text-3xl font-black text-white neon-glow" style="font-family: 'Orbitron', sans-serif;">
                    GameVerse</h1>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-white hover:text-cyan-300 transition-colors font-medium">Games</a>
                <a href="{{ url('tournaments') }}"
                    class="text-white hover:text-cyan-300 transition-colors font-medium">Tournaments</a>
                {{-- <a href="#" class="text-white hover:text-cyan-300 transition-colors font-medium">Profile</a> --}}
                @if (Auth::check())
                    <a href="{{ route('user.logout') }}"
                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-2 rounded-full font-semibold transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                        Logout
                    </a>
                @else
                    <a href="{{ route('user.signup') }}"
                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-2 rounded-full font-semibold transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                        Sign Up
                    </a>
                @endif
            </div>
        </nav>
    </header>

    @yield('content')

    @yield('script')
</body>

</html>
