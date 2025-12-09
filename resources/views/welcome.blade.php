@extends('layouts.user')
@section('style')
    <!-- Tailwind keyframes for subtle animations -->
    <style>
        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(60px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseSlow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(0.3);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.5);
            }
        }

        @keyframes bounceSmooth {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-slide-up {
            animation: slideUp 1s ease-out forwards;
        }

        .animate-fade-in {
            animation: fadeIn 1.3s ease-out 0.2s forwards;
        }

        .animate-pulse-slow {
            animation: pulseSlow 5s infinite ease-in-out;
        }

        .animate-bounce-smooth {
            animation: bounceSmooth 3s infinite ease-in-out;
        }

        .gradient-text {
            background: linear-gradient(90deg, #06b6d4, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative px-6 py-24 text-center overflow-hidden">
        <!-- Floating glow elements  -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-cyan-500/20 blur-3xl rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-purple-600/20 blur-3xl rounded-full animate-pulse-slow"></div>

        <div class="relative z-10 max-w-4xl mx-auto">
            <!-- Animated gradient headline -->
            <h2 class="text-5xl sm:text-6xl md:text-8xl font-black gradient-text mb-6 leading-tight tracking-tight animate-slide-up"
                style="font-family: 'Orbitron', sans-serif;">
                UNLOCK YOUR INNER GENIUS
            </h2>

            <!-- Subtext with soft glow -->
            <p class="text-lg sm:text-xl md:text-2xl text-white/90 mb-12 leading-relaxed max-w-3xl mx-auto animate-fade-in">
                Train your brain with immersive logic puzzles, memory boosters, and
                problem-solving challenges built to sharpen your mind and elevate your
                focus — anytime, anywhere.
            </p>

            <!-- Button with pulse glow -->
            <a href="{{ url('tournaments') }}"
                class="bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white text-xl font-bold px-10 sm:px-12 py-4 rounded-full transform hover:scale-110 transition-all shadow-2xl hover:shadow-cyan-500/50 animate-bounce-smooth">
                Tournaments
            </a>
        </div>
    </section>
    <!-- Games Section -->
    <section class="px-6 py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto" id="games_box">
            <h3 class="text-5xl font-black text-white text-center mb-4" style="font-family: 'Orbitron', sans-serif;">
                FEATURED GAMES
            </h3>
            <p class="text-xl text-white/80 text-center mb-16">Discover your next addiction</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 cursor-pointer gap-8" >
                @foreach ($games as $game)
                <!-- Sudoku -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 0s;">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M3 3v18h18V3H3zm16 16H5V5h14v14zM7 7h2v2H7V7zm4 0h2v2h-2V7zm4 0h2v2h-2V7zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2zM7 15h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z" />
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">{{ $game->title }}</h4>
                        <p class="text-white/70 mb-6">{{ $game->description }}
                        </p>
                    </div>
                    {{-- <div class="flex items-center justify-between mb-6">
                        <span
                            class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm font-semibold">Logic</span>
                        <span class="text-yellow-400">⭐ 4.8</span>
                    </div> --}}
                    <a href="{{ url('play-game/'.$game->id) }}">
                    <button
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button></a>
                </div>
                @endforeach

                {{-- <!-- Pattern Solver -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 0.5s;">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-pink-500 to-red-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Pattern Quest</h4>
                        <p class="text-white/70 mb-6">Decode complex sequences and unlock hidden patterns in challenging
                            puzzles</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm font-semibold">Puzzle</span>
                        <span class="text-yellow-400">⭐ 4.9</span>
                    </div>
                    <button
                        class="w-full bg-gradient-to-r from-pink-600 to-red-600 hover:from-pink-700 hover:to-red-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Memory Matrix -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 1s;">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Memory Matrix</h4>
                        <p class="text-white/70 mb-6">Test your memory with increasing complexity grids and time challenges
                        </p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="bg-teal-500/20 text-teal-300 px-3 py-1 rounded-full text-sm font-semibold">Memory</span>
                        <span class="text-yellow-400">⭐ 4.7</span>
                    </div>
                    <button
                        class="w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Word Forge -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 1.5s;">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M21 8c-1.45 0-2.26 1.44-1.93 2.51l-3.57 3.58c-.21-.11-.46-.16-.71-.16-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5c0-.25-.05-.5-.16-.71l3.58-3.57C20.56 10.74 22 9.93 22 8.5 22 8.22 21.78 8 21.5 8h-.5zM11.5 11c.83 0 1.5-.67 1.5-1.5S12.33 8 11.5 8 10 8.67 10 9.5s.67 1.5 1.5 1.5zM8 21l.5-2H7l.5 2zM7 9H3V7h4v2zM3 6V4l2-.5L3 1h2l1 2.5L7 1h2L7 3.5 9 4v2L7 7v2H3V7l2-.5L3 6z" />
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Word Forge</h4>
                        <p class="text-white/70 mb-6">Craft words from letters and compete in timed vocabulary challenges
                        </p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="bg-orange-500/20 text-orange-300 px-3 py-1 rounded-full text-sm font-semibold">Vocabulary</span>
                        <span class="text-yellow-400">⭐ 4.6</span>
                    </div>
                    <button
                        class="w-full bg-gradient-to-r from-orange-600 to-yellow-600 hover:from-orange-700 hover:to-yellow-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>

                <!-- Logic Circuits -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 2s;">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3.5 6L12 10.5 8.5 8 12 5.5 15.5 8zM8.5 16L12 13.5 15.5 16 12 18.5 8.5 16z" />
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Logic Circuits</h4>
                        <p class="text-white/70 mb-6">Connect pathways and solve electrical puzzles with increasing
                            complexity</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="bg-cyan-500/20 text-cyan-300 px-3 py-1 rounded-full text-sm font-semibold">Strategy</span>
                        <span class="text-yellow-400">⭐ 4.8</span>
                    </div>
                    <button
                        class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div>
                <!-- Pattern Solver -->
                <div class="game-card rounded-3xl p-8 floating" style="animation-delay: 0.5s;">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-pink-500 to-red-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        </div>
                        <h4 class="text-3xl font-bold text-white mb-2">Pattern Quest</h4>
                        <p class="text-white/70 mb-6">Decode complex sequences and unlock hidden patterns in challenging
                            puzzles</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm font-semibold">Puzzle</span>
                        <span class="text-yellow-400">⭐ 4.9</span>
                    </div>
                    <button
                        class="w-full bg-gradient-to-r from-pink-600 to-red-600 hover:from-pink-700 hover:to-red-700 text-white font-bold py-3 rounded-2xl transform hover:scale-105 transition-all">
                        PLAY NOW
                    </button>
                </div> --}}

            </div>
        </div>
    </section>

    <!-- Stats Section -->
    {{-- <section class="relative px-6 py-24 bg-black/30 backdrop-blur-md overflow-hidden">
        <!-- Glowing background orbs -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-cyan-500/20 blur-3xl rounded-full animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-purple-600/20 blur-3xl rounded-full animate-pulse"></div>
        </div>

        <div class="max-w-6xl mx-auto text-center">
            <h3 class="text-4xl md:text-6xl font-black text-white mb-16 leading-tight"
                style="font-family: 'Orbitron', sans-serif;">
                JOIN THE <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">NEXT-GEN</span>
                GAMING ERA
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 cursor-pointer gap-10">
                <!-- Card 1 -->
                <div
                    class="group relative p-8 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-xl hover:scale-105 transition-all duration-500 hover:border-cyan-500/60 hover:shadow-cyan-500/30 shadow-xl">
                    <div class="text-6xl font-extrabold gradient-text mb-3">3M+</div>
                    <h4 class="text-white text-lg font-semibold mb-2">Global Players</h4>
                    <p class="text-white/70 text-sm">
                        Millions of minds challenging themselves daily around the world.
                    </p>
                </div>

                <!-- Card 2 -->
                <div
                    class="group relative p-8 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-xl hover:scale-105 transition-all duration-500 hover:border-purple-500/60 hover:shadow-purple-500/30 shadow-xl">
                    <div class="text-6xl font-extrabold gradient-text mb-3">20M+</div>
                    <h4 class="text-white text-lg font-semibold mb-2">Games Completed</h4>
                    <p class="text-white/70 text-sm">
                        Countless puzzles solved, records broken, and levels mastered.
                    </p>
                </div>

                <!-- Card 3 -->
                <div
                    class="group relative p-8 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-xl hover:scale-105 transition-all duration-500 hover:border-cyan-500/60 hover:shadow-purple-500/30 shadow-xl">
                    <div class="text-6xl font-extrabold gradient-text mb-3">99%</div>
                    <h4 class="text-white text-lg font-semibold mb-2">Player Satisfaction</h4>
                    <p class="text-white/70 text-sm">
                        A gaming experience trusted, loved, and celebrated by players.
                    </p>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Footer Section -->
    <footer class="relative bg-black/30 backdrop-blur-sm border-t border-white/10 px-6 py-20 text-center overflow-hidden">
        <div class="max-w-6xl mx-auto">
            <!-- Heading -->
            <h3 class="text-4xl md:text-6xl font-black text-white mb-16 leading-tight"
                style="font-family: 'Orbitron', sans-serif;">
                POWER UP <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500"> YOUR
                    MIND,</span> ONE GAME AT A TIME
            </h3>

            <!-- Description -->
            <p class="text-white/80 max-w-2xl mx-auto mb-10 text-lg leading-relaxed">
                Challenge yourself, rise through the ranks, and redefine what your brain is capable of.
                Join our global community of thinkers, dreamers, and problem-solvers.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mb-14">
                <button
                    class="bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white text-lg font-bold px-10 py-4 rounded-full transform hover:scale-110 transition-all shadow-lg hover:shadow-cyan-500/40">
                    CREATE ACCOUNT
                </button>
                <button
                    class="bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 text-white text-lg font-bold px-10 py-4 rounded-full transform hover:scale-110 transition-all">
                    EXPLORE MORE
                </button>
            </div>

            <!-- Social Links -->
            <div class="flex justify-center space-x-6 mb-10">
                <a href="#"
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 text-white text-2xl transform hover:scale-110 transition-all shadow-lg hover:shadow-cyan-500/40">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#"
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 text-white text-2xl transform hover:scale-110 transition-all shadow-lg hover:shadow-purple-500/40">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#"
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 text-white text-2xl transform hover:scale-110 transition-all shadow-lg hover:shadow-cyan-500/40">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <!-- Copyright -->
            <p class="text-white/50 text-sm tracking-widest uppercase">
                © 2025 MindPlay | Designed for Great Minds 🧠
            </p>

            <!-- Decorative Glow Bar -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-1 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full">
            </div>

            <!-- Soft Gradient Light Effect -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-96 h-96 bg-cyan-500/10 blur-3xl rounded-full">
                </div>
                <div class="absolute bottom-0 right-0 w-80 h-80 bg-purple-500/10 blur-3xl rounded-full"></div>
            </div>
        </div>
    </footer>
@endsection
