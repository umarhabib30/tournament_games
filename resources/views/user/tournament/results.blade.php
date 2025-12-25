<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.8);
            }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #1a1a2e, #16213e, #0f3460, #533483);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .trophy-icon {
            animation: float 3s ease-in-out infinite;
        }

        .podium-gold {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.5);
        }

        .podium-silver {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            box-shadow: 0 0 30px rgba(156, 163, 175, 0.5);
        }

        .podium-bronze {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen text-white overflow-x-hidden">

    <!-- Animated Background Circles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Tournament Header -->
    <div class="relative text-center mb-12 p-8 fade-in-up">
        <div class="inline-block trophy-icon mb-4">
            <svg class="w-20 h-20 mx-auto text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
            </svg>
        </div>
        <div class="mb-4">
            <p class="text-lg md:text-xl text-gray-400 font-medium mb-2">Hello,</p>
            <p class="text-2xl md:text-3xl font-bold text-blue-300">{{ Auth::user()->username }}</p>
        </div>
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">
            {{ $tournament->name }}
        </h1>
        <p class="text-2xl md:text-3xl text-gray-300 font-light">Tournament Leaderboard 🏆</p>
    </div>

    <!-- Results Table -->
    <div class="max-w-4xl mx-auto px-4 pb-20">
        <div class="glass-effect rounded-3xl p-6 md:p-8 shadow-2xl fade-in-up">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="px-4 py-4 text-left text-lg font-bold text-blue-300">Position</th>
                            <th class="px-4 py-4 text-left text-lg font-bold text-blue-300">Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tournament->elimination_type === 'all')
                            @foreach ($results as $item)
                                @php
                                    $rank = $item['final_rank'];
                                    $user = $item['user'];
                                    $medal = $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : ($rank == 3 ? '🥉' : ''));
                                    $podiumClass = $rank == 1 ? 'podium-gold' : ($rank == 2 ? 'podium-silver' : ($rank == 3 ? 'podium-bronze' : ''));
                                @endphp

                                <tr class="border-b border-gray-700/50 hover:bg-white/5 transition-colors duration-200 fade-in-up stagger-{{ ($loop->iteration % 3) + 1 }}">
                                    <td class="px-4 py-5 font-bold text-xl">
                                        <div class="flex items-center gap-3">
                                            @if ($rank <= 3)
                                                <span class="text-3xl">{{ $medal }}</span>
                                            @endif
                                            <span class="{{ $rank <= 3 ? $podiumClass : '' }} px-4 py-2 rounded-full {{ $rank <= 3 ? 'text-white' : 'text-gray-300' }}">
                                                {{ $rank }}{{ $rank == 1 ? 'st' : ($rank == 2 ? 'nd' : ($rank == 3 ? 'rd' : 'th')) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-lg">
                                                {{ strtoupper(substr($user->username, 0, 1)) }}
                                            </div>
                                            <span class="text-lg font-semibold">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($results as $item)
                                @php
                                    $rank = $item['position'];
                                    $user = $item['user'];
                                    $medal = $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : ($rank == 3 ? '🥉' : ''));
                                    $podiumClass = $rank == 1 ? 'podium-gold' : ($rank == 2 ? 'podium-silver' : ($rank == 3 ? 'podium-bronze' : ''));
                                @endphp

                                <tr class="border-b border-gray-700/50 hover:bg-white/5 transition-colors duration-200 fade-in-up stagger-{{ ($loop->iteration % 3) + 1 }}">
                                    <td class="px-4 py-5 font-bold text-xl">
                                        <div class="flex items-center gap-3">
                                            @if ($rank <= 3)
                                                <span class="text-3xl">{{ $medal }}</span>
                                            @endif
                                            <span class="{{ $rank <= 3 ? $podiumClass : '' }} px-4 py-2 rounded-full {{ $rank <= 3 ? 'text-white' : 'text-gray-300' }}">
                                                {{ $rank }}{{ $rank == 1 ? 'st' : ($rank == 2 ? 'nd' : ($rank == 3 ? 'rd' : 'th')) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-lg">
                                                {{ strtoupper(substr($user->username, 0, 1)) }}
                                            </div>
                                            <span class="text-lg font-semibold">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-12 w-full flex flex-col md:flex-row justify-center items-center gap-4 px-4">
        <a href="{{ route('tournament.results.details', $tournament->id) }}"
            class="flex items-center gap-3 bg-gradient-to-r from-purple-600 to-pink-600
       hover:from-purple-500 hover:to-pink-500 text-white font-bold py-4 px-8 rounded-2xl
       transition-all duration-300 transform hover:scale-105 shadow-2xl border border-purple-500/50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            View Detailed Results
        </a>
        <a href="{{ url('tournaments') }}"
            class="flex items-center gap-3 bg-gradient-to-r from-gray-800 to-gray-900
       hover:from-gray-700 hover:to-gray-800 text-white font-bold py-4 px-8 rounded-2xl
       transition-all duration-300 transform hover:scale-105 shadow-2xl border border-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tournaments
        </a>
    </div>

</body>

</html>
