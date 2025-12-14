@extends('layouts.user')
@section('content')
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }

            50% {
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.8);
            }
        }

        .tournament-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tournament-card:hover {
            transform: translateY(-12px) scale(1.02);
        }

        .shimmer-effect {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        .live-pulse {
            animation: pulse-glow 2s infinite;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">
        <div class="grid grid-cols-1 sm:grid-cols-2  lg:grid-cols-3 cursor-pointer gap-8">
            @foreach ($tournaments as $tournament)
                <div class="tournament-card group relative bg-gradient-to-br from-white via-gray-50 to-white rounded-3xl overflow-hidden border-2 border-gray-100 hover:border-transparent hover:shadow-2xl"
                    style="animation: float 6s ease-in-out infinite; animation-delay: {{ $loop->index * 0.2 }}s;">

                    <!-- Gradient Overlay on Hover -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-blue-500/5 group-hover:via-purple-500/5 group-hover:to-pink-500/5 transition-all duration-500 pointer-events-none">
                    </div>

                    <!-- Tournament Header with Glass Effect -->
                    <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 p-6 overflow-hidden">
                        <!-- Animated Background Pattern -->
                        <div class="absolute inset-0 opacity-20">
                            <div class="absolute inset-0"
                                style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                            </div>
                        </div>

                        <!-- Shimmer Effect -->
                        <div
                            class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <h3
                                    class="text-xl font-bold text-white leading-tight pr-3 group-hover:scale-105 transition-transform duration-300">
                                    {{ $tournament->name }}
                                </h3>
                                @if ($tournament->status === 'inactive')
                                    <span
                                        class="px-3 py-1.5 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-semibold rounded-full shadow-lg border border-white/50 transform group-hover:scale-110 transition-transform">
                                        ⏳ Upcoming
                                    </span>
                                @elseif($tournament->status === 'inprogress')
                                    <span
                                        class="px-3 py-1.5 bg-green-400/90 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-lg border border-green-300/50 live-pulse transform group-hover:scale-110 transition-transform">
                                        🔴 Live
                                    </span>
                                @elseif($tournament->status === 'completed')
                                    <span
                                        class="px-3 py-1.5 bg-blue-400/90 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-lg border border-blue-300/50 transform group-hover:scale-110 transition-transform">
                                        ✓ Completed
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 flex-wrap">
                                @if ($tournament->open_close === 'open')
                                    <span
                                        class="px-3 py-1 bg-emerald-400/90 backdrop-blur-sm text-white text-xs font-bold rounded-lg shadow-md border border-emerald-300/50 transform hover:scale-105 transition-transform">
                                        🟢 Open for all
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-red-400/90 backdrop-blur-sm text-white text-xs font-bold rounded-lg shadow-md border border-red-300/50 transform hover:scale-105 transition-transform">
                                        🔒 Admin permission required
                                    </span>
                                @endif
                                <span
                                    class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-lg border border-white/30">
                                    🎯 {{ $tournament->rounds }} Rounds
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Details with Modern Layout -->
                    <div class="relative p-6 space-y-5">
                        <!-- Date with Icon -->
                        <div
                            class="flex items-center text-sm text-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-2xl border border-blue-100 group-hover:border-purple-200 transition-colors">
                            <div
                                class="bg-gradient-to-br from-blue-500 to-purple-600 p-2.5 rounded-xl mr-3 transform group-hover:rotate-12 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span
                                class="font-semibold">{{ \Carbon\Carbon::parse($tournament->date)->format('D, M d, Y') }}</span>
                        </div>

                        <!-- Time Cards with Gradient -->
                        <div class="grid grid-cols-2 gap-4">
                            @if ($tournament->time_to_enter)
                                <div
                                    class="relative bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-2xl border border-amber-100 overflow-hidden group/time hover:shadow-lg transition-all">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 bg-amber-200/30 rounded-full -mr-10 -mt-10">
                                    </div>
                                    <div class="relative">
                                        <div class="text-amber-600 text-xs font-semibold mb-1">⏰ Entry Time</div>
                                        <div class="font-bold text-gray-800 text-sm">
                                            {{ \Carbon\Carbon::parse($tournament->time_to_enter)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div
                                class="relative bg-gradient-to-br from-blue-50 to-cyan-50 p-4 rounded-2xl border border-blue-100 overflow-hidden group/time hover:shadow-lg transition-all">
                                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-200/30 rounded-full -mr-10 -mt-10">
                                </div>
                                <div class="relative">
                                    <div class="text-blue-600 text-xs font-semibold mb-1">⏱️ Duration</div>
                                    <div class="font-bold text-gray-800 text-xs leading-tight">
                                        {{ \Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($tournament->end_time)->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features with Modern Badges -->
                        <div class="flex flex-wrap gap-2.5">
                            <span
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-bold rounded-xl shadow-md transform hover:scale-105 transition-transform border border-blue-400">
                                {{ $tournament->time_or_free === 'time' ? '⚡ Timed Rounds' : '♾️ Unlimited Time' }}
                            </span>
                            @if ($tournament->elimination_type === 'percentage' && $tournament->elimination_percent)
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs font-bold rounded-xl shadow-md transform hover:scale-105 transition-transform border border-orange-400">
                                    🔥 {{ $tournament->elimination_percent }}% Elimination
                                </span>
                            @else
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold rounded-xl shadow-md transform hover:scale-105 transition-transform border border-green-400">
                                    🏆 Play till End
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button with Modern Design -->
                    <div class="p-6 pt-0">
                        @php
                            $status = $tournament->status;

                            $endTime = \Carbon\Carbon::today()->setTimeFromTimeString($tournament->end_time);
                            // Apply correct timezone
                            $endTime = $endTime->timezone(config('app.timezone'));
                            $hasEnded = $endTime->isPast();
                        @endphp

                        <a
                            @if ($status === 'active') href="{{ route('waiting', $tournament->id) }}"
                           @elseif ($status === 'inprogress') href="#"
                           @elseif ($status === 'complete') href="#"
                           @else href="javascript:void(0)" @endif>




                            <button
                                class="relative w-full py-4 rounded-2xl font-bold text-sm transition-all duration-300 overflow-hidden group/btn
                                @if ($status === 'inactive') bg-gradient-to-r from-gray-300 to-gray-400 text-gray-600 cursor-not-allowed
                                @elseif ($hasEnded)
                                    bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 text-white shadow-lg hover:shadow-2xl hover:scale-105
                                @else
                                    bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 text-white shadow-lg hover:shadow-2xl hover:scale-105 @endif"
                                @if ($status === 'inactive') disabled @endif>

                                {{-- Shine Effect --}}
                                @if ($status !== 'inactive')
                                    <div class="absolute inset-0 shimmer-effect opacity-0 group-hover/btn:opacity-100">
                                    </div>
                                @endif

                                <span class="relative z-10 flex items-center justify-center gap-2">

                                    {{-- FINAL LOGIC --}}
                                    @if ($status === 'inactive')
                                        <span class="text-lg">⏳</span> Coming Soon
                                    @elseif ($hasEnded)
                                        <span class="text-lg">📊</span> Show Results
                                    @else
                                        <span class="text-lg">🎮</span> Join Tournament
                                    @endif

                                </span>
                            </button>

                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
