<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Play Tournament</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
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

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #1a1a2e, #16213e, #0f3460, #533483);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .shimmer-effect {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        .trophy-icon {
            animation: float 3s ease-in-out infinite;
        }

        .score-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
    </style>
</head>

<body class="gradient-bg min-h-screen text-white overflow-x-hidden">

    <!-- Animated Background Circles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>
{{--
    <!-- Tournament Header -->
    <div class="relative text-center mb-12 p-8 fade-in-up">
        <div class="inline-block trophy-icon mb-4">
            <svg class="w-20 h-20 mx-auto text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
            </svg>
        </div>
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">
            Hello {{ Auth::user()->username }}!
        </h1>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            {{ $tournament->name }}
        </h2>
        <p class="text-xl md:text-2xl text-gray-300 font-light">Choose a round and start your journey! 🎮</p>
    </div> --}}

    <!-- Tournament Rounds -->
    <div class="max-w-2xl mx-auto px-2 mt-12 pb-20 space-y-8">
        @foreach ($tournament->tournament_rounds as $round)
            @php
                $is_played = \App\Models\Result::where('user_id', Auth::id())
                    ->where('tournament_id', $tournament->id)
                    ->where('round_id', $round->id)
                    ->first();

                $previous_round_played = true;
                if ($round->sequence > 1) {
                    $previous_round = $tournament->tournament_rounds->where('sequence', $round->sequence - 1)->first();
                    $previous_round_played = \App\Models\Result::where('user_id', Auth::id())
                        ->where('tournament_id', $tournament->id)
                        ->where('round_id', $previous_round->id)
                        ->exists();
                }
            @endphp

            <div class="card-hover glass-effect rounded-3xl p-4 relative overflow-hidden fade-in-up stagger-{{ $loop->iteration % 4 + 1 }}">

                <!-- Shimmer Effect on Hover -->
                <div class="absolute inset-0 shimmer-effect opacity-0 hover:opacity-100 transition-opacity duration-300"></div>

                <!-- Score Badge -->
                <div class="absolute top-6 right-6 score-badge text-white text-sm font-bold px-5 py-2 rounded-full shadow-2xl z-10">
                    @if ($is_played)
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $is_played->score }}/85
                        </span>
                    @else
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            0/85
                        </span>
                    @endif
                </div>

                <!-- Round Title -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-2xl font-bold shadow-lg">
                        {{ $round->sequence }}
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">Round {{ $round->sequence }}</h2>
                </div>

                @if ($tournament->time_or_free == 'time')
                    <!-- Time Information -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                       <div class="glass-effect rounded-xl p-3 md:p-4 flex items-center gap-3">
    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-500/20 flex items-center justify-center">
        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div>
        <p class="text-xs md:text-sm text-gray-400 font-medium">Start Time</p>
        <p class="text-base md:text-lg font-bold text-white">
            {{ \Carbon\Carbon::parse($round->start_time)->format('h:i a') }}
        </p>
    </div>
</div>
<div class="glass-effect rounded-xl p-3 md:p-4 flex items-center gap-3">
    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-500/20 flex items-center justify-center">
        <svg class="w-5 h-5 md:w-6 md:h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div>
        <p class="text-xs md:text-sm text-gray-400 font-medium">End Time</p>
        <p class="text-base md:text-lg font-bold text-white">
            {{ \Carbon\Carbon::parse($round->end_time)->format('h:i a') }}
        </p>
    </div>
</div>

                    </div>
                @endif

                <!-- Game Information -->
<div class="bg-gray-800/50 rounded-2xl p-3 md:p-6 mb-6">
                    <div class="flex items-start gap-4">

                        <div class="flex-1">
                            <p class="text-xl font-bold text-blue-300 mb-3">{{ $round->get_game->title }}</p>

                            @php
                                $rules = json_decode($round->get_game->rules);
                            @endphp

<div class="space-y-1 md:space-y-2">
                                @foreach ($rules as $rule)
<div class="flex items-start gap-1 md:gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-2 flex-shrink-0"></div>
                                        <p class="text-sm text-gray-300 leading-relaxed">{{ $rule }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Play Button -->
                <div class="flex justify-center">
                    <form action="{{ route('play.game') }}" method="GET" class="w-full md:w-auto"
                        @if (!$previous_round_played || $is_played) onsubmit="return false;" @endif>

                        <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                        <input type="hidden" name="round_id" value="{{ $round->id }}">
                        <input type="hidden" name="game_id" value="{{ $round->get_game->id }}">

                        <button type="@if ($previous_round_played && !$is_played) submit @else button @endif"
                            class="w-full md:w-auto relative group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-4 px-12 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-2xl
                            @if (!$previous_round_played || $is_played) opacity-50 cursor-not-allowed @else hover:shadow-blue-500/50 @endif"
                            @if (!$previous_round_played || $is_played) disabled @endif>

                            <span class="relative z-10 flex items-center justify-center gap-3 text-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                </svg>
                                @if (!$previous_round_played)
                                    Complete Previous Round
                                @elseif ($is_played)
                                    Already Completed
                                @else
                                    Play Game Now
                                @endif
                            </span>

                            @if ($previous_round_played && !$is_played)
                                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-400 to-purple-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                            @endif
                        </button>
                    </form>
                </div>

            </div>
        @endforeach
    </div>

    <!-- Back to Tournaments Button -->
<div class=" mb-12 w-full flex justify-center">
    <a href="{{ url('tournaments') }}"
       class="flex items-center gap-3 bg-gradient-to-r from-gray-800 to-gray-900
       hover:from-gray-700 hover:to-gray-800 text-white font-bold py-4 px-8 rounded-2xl
       transition-all duration-300 transform hover:scale-105 shadow-2xl border border-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Tournaments
    </a>
</div>


</body>

</html>
