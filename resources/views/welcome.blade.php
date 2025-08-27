<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameVerse - Premium Brain Games</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;600;700&display=swap');

        .game-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .game-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.4);
        }

        .neon-glow {
            text-shadow: 0 0 10px currentColor, 0 0 20px currentColor, 0 0 40px currentColor;
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }

        @keyframes pulseGlow {
            from { box-shadow: 0 0 20px rgba(139, 92, 246, 0.5); }
            to { box-shadow: 0 0 30px rgba(139, 92, 246, 0.8), 0 0 40px rgba(139, 92, 246, 0.6); }
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
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #5b21b6 50%, #7c3aed 75%, #2563eb 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="relative z-10 px-6 py-4">
        <nav class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-cyan-500 rounded-lg flex items-center justify-center pulse-glow">
                    <span class="text-white font-bold text-xl">G</span>
                </div>
                <h1 class="text-3xl font-black text-white neon-glow" style="font-family: 'Orbitron', sans-serif;">GameVerse</h1>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-white hover:text-cyan-300 transition-colors font-medium">Games</a>
                <a href="#" class="text-white hover:text-cyan-300 transition-colors font-medium">Leaderboard</a>
                <a href="#" class="text-white hover:text-cyan-300 transition-colors font-medium">Profile</a>
                <button class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-2 rounded-full font-semibold transform hover:scale-105 transition-all shadow-lg hover:shadow-xl">
                    Sign Up
                </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative px-6 py-20 text-center">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-6xl md:text-8xl font-black gradient-text mb-6" style="font-family: 'Orbitron', sans-serif;">
                LEVEL UP YOUR MIND
            </h2>
            <p class="text-xl md:text-2xl text-white/90 mb-12 leading-relaxed">
                Challenge yourself with premium brain games designed to enhance cognitive abilities and provide endless entertainment
            </p>
            <button class="bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white text-xl font-bold px-12 py-4 rounded-full transform hover:scale-110 transition-all shadow-2xl hover:shadow-cyan-500/50 pulse-glow">
                START PLAYING NOW
            </button>
        </div>
    </section>

    <!-- Games Section -->
    <section class="px-6 py-20">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-5xl font-black text-white text-center mb-4" style="font-family: 'Orbitron', sans-serif;">
                FEATURED GAMES
            </h3>
            <p class="text-xl text-white/80 text-center mb-16">Discover your next addiction</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sudoku -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 0s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3v18h18V3H3zm16 16H5V5h14v14zM7 7h2v2H7V7zm4 0h2v2h-2V7zm4 0h2v2h-2V7zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2zM7 15h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Sudoku Master</h4>
                        <p class="text-white/70 mb-6">Classic number puzzles with multiple difficulty levels and smart hints</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm font-semibold">Logic</span>
                        <span class="text-yellow-400">⭐ 4.8</span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Pattern Solver -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 0.5s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-pink-500 to-red-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Pattern Quest</h4>
                        <p class="text-white/70 mb-6">Decode complex sequences and unlock hidden patterns in challenging puzzles</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm font-semibold">Puzzle</span>
                        <span class="text-yellow-400">⭐ 4.9</span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-pink-600 to-red-600 hover:from-pink-700 hover:to-red-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Memory Matrix -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 1s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Memory Matrix</h4>
                        <p class="text-white/70 mb-6">Test your memory with increasing complexity grids and time challenges</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-teal-500/20 text-teal-300 px-3 py-1 rounded-full text-sm font-semibold">Memory</span>
                        <span class="text-yellow-400">⭐ 4.7</span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Word Forge -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 1.5s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 8c-1.45 0-2.26 1.44-1.93 2.51l-3.57 3.58c-.21-.11-.46-.16-.71-.16-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5c0-.25-.05-.5-.16-.71l3.58-3.57C20.56 10.74 22 9.93 22 8.5 22 8.22 21.78 8 21.5 8h-.5zM11.5 11c.83 0 1.5-.67 1.5-1.5S12.33 8 11.5 8 10 8.67 10 9.5s.67 1.5 1.5 1.5zM8 21l.5-2H7l.5 2zM7 9H3V7h4v2zM3 6V4l2-.5L3 1h2l1 2.5L7 1h2L7 3.5 9 4v2L7 7v2H3V7l2-.5L3 6z"/>
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Word Forge</h4>
                        <p class="text-white/70 mb-6">Craft words from letters and compete in timed vocabulary challenges</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-orange-500/20 text-orange-300 px-3 py-1 rounded-full text-sm font-semibold">Vocabulary</span>
                        <span class="text-yellow-400">⭐ 4.6</span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-orange-600 to-yellow-600 hover:from-orange-700 hover:to-yellow-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Logic Circuits -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 2s;">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3.5 6L12 10.5 8.5 8 12 5.5 15.5 8zM8.5 16L12 13.5 15.5 16 12 18.5 8.5 16z"/>
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Logic Circuits</h4>
                        <p class="text-white/70 mb-6">Connect pathways and solve electrical puzzles with increasing complexity</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-cyan-500/20 text-cyan-300 px-3 py-1 rounded-full text-sm font-semibold">Strategy</span>
                        <span class="text-yellow-400">⭐ 4.8</span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="px-6 py-20 bg-black/20">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-4xl font-black text-white mb-12" style="font-family: 'Orbitron', sans-serif;">
                JOIN THE GAMING REVOLUTION
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-black gradient-text mb-2">2.5M+</div>
                    <div class="text-white/80 text-lg">Active Players</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-black gradient-text mb-2">15M+</div>
                    <div class="text-white/80 text-lg">Games Played</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-black gradient-text mb-2">98%</div>
                    <div class="text-white/80 text-lg">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="px-6 py-20 text-center">
        <div class="max-w-3xl mx-auto">
            <h3 class="text-5xl font-black text-white mb-6" style="font-family: 'Orbitron', sans-serif;">
                READY TO DOMINATE?
            </h3>
            <p class="text-xl text-white/90 mb-12">
                Join millions of players and start your journey to cognitive mastery today
            </p>
            <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                <button class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xl font-bold px-12 py-4 rounded-full transform hover:scale-110 transition-all shadow-2xl">
                    CREATE FREE ACCOUNT
                </button>
                <button class="w-full sm:w-auto bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 text-white text-xl font-bold px-12 py-4 rounded-full transform hover:scale-110 transition-all">
                    EXPLORE GAMES
                </button>
            </div>
        </div>
    </section>
</body>
</html>
