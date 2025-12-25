<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Detailed Results</title>
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

        .player-card {
            transition: all 0.3s ease;
        }

        .player-card:hover {
            transform: translateY(-5px);
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
    <div class="relative text-center mb-8 p-6 fade-in-up">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">
            {{ $tournament->name }}
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 font-light">Detailed Tournament Results 📊</p>
    </div>

    <!-- Players Details Container -->
    <div class="max-w-6xl mx-auto px-4 pb-20 space-y-6">
        @if($players->isEmpty())
            <div class="glass-effect rounded-3xl p-12 text-center">
                <p class="text-2xl text-gray-400">No results available yet.</p>
            </div>
        @else
            @foreach($players as $index => $player)
                <div class="player-card glass-effect rounded-3xl p-6 md:p-8 shadow-2xl fade-in-up stagger-{{ ($index % 3) + 1 }}">
                    <!-- Player Header -->
                    <div class="mb-6 pb-6 border-b border-gray-600">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-2xl">
                                    {{ strtoupper(substr($player['user']->username, 0, 1)) }}
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-bold">{{ $player['user']->username }}</h2>
                                    <p class="text-gray-400">Player Details</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                                    <p class="text-xs text-gray-300 mb-1">Overall Position</p>
                                    <p class="text-3xl font-bold">
                                        @if($player['overall_position'] == 1)
                                            🥇
                                        @elseif($player['overall_position'] == 2)
                                            🥈
                                        @elseif($player['overall_position'] == 3)
                                            🥉
                                        @endif
                                        {{ $player['overall_position'] }}{{ $player['overall_position'] == 1 ? 'st' : ($player['overall_position'] == 2 ? 'nd' : ($player['overall_position'] == 3 ? 'rd' : 'th')) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rounds Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-600">
                                    <th class="px-4 py-4 text-left text-lg font-bold text-blue-300">Round Number</th>
                                    <th class="px-4 py-4 text-left text-lg font-bold text-blue-300">Score</th>
                                    <th class="px-4 py-4 text-left text-lg font-bold text-blue-300">Time Taken</th>
                                    <th class="px-4 py-4 text-left text-lg font-bold text-blue-300">Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($player['rounds']->isEmpty())
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                            No rounds completed yet.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($player['rounds'] as $round)
                                        <tr class="border-b border-gray-700/50 hover:bg-white/5 transition-colors duration-200">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center font-bold">
                                                        {{ $round['round_number'] }}
                                                    </div>
                                                    <span class="font-semibold">Round {{ $round['round_number'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="text-lg font-bold text-green-400">{{ $round['score'] }}</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-semibold">{{ $round['time_formatted'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                @php
                                                    $position = $round['position'];
                                                    $medal = $position == 1 ? '🥇' : ($position == 2 ? '🥈' : ($position == 3 ? '🥉' : ''));
                                                    $positionClass = $position == 1 ? 'bg-yellow-600/30' : ($position == 2 ? 'bg-gray-500/30' : ($position == 3 ? 'bg-green-600/30' : 'bg-gray-700/30'));
                                                @endphp
                                                <span class="px-4 py-2 rounded-full font-bold {{ $positionClass }}">
                                                    @if($position <= 3) {{ $medal }} @endif
                                                    {{ $position }}{{ $position == 1 ? 'st' : ($position == 2 ? 'nd' : ($position == 3 ? 'rd' : 'th')) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Back Button -->
    <div class="mb-12 w-full flex justify-center px-4">
        <a href="{{ route('tournament.results', $tournament->id) }}"
            class="flex items-center gap-3 bg-gradient-to-r from-gray-800 to-gray-900
       hover:from-gray-700 hover:to-gray-800 text-white font-bold py-4 px-8 rounded-2xl
       transition-all duration-300 transform hover:scale-105 shadow-2xl border border-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Results
        </a>
    </div>

</body>

</html>
