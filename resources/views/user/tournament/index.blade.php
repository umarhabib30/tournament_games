@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Section -->
    <div class="text-center mb-16">
 <h2
      class="text-5xl sm:text-6xl md:text-8xl font-black gradient-text mb-6 leading-tight tracking-tight animate-slide-up"
      style="font-family: 'Orbitron', sans-serif;"
    >            Tournament Arena
        </h1>
        <p class="text-white text-lg max-w-2xl mx-auto">
            Compete, showcase your skills, and climb the leaderboards in our exciting tournament challenges
        </p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Tournaments</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $tournaments->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Live Now</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">
                        {{ $tournaments->where('status', 'inprogress')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Upcoming</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        {{ $tournaments->where('status', 'inactive')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completed</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">
                        {{ $tournaments->where('status', 'completed')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournaments Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach ($tournaments as $tournament)
            <div class="group relative bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-200 hover:-translate-y-2 overflow-hidden">
                <!-- Status Ribbon -->
                <div class="absolute top-6 right-6 z-10">
                    @if ($tournament->status === 'inactive')
                        <span class="px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white text-xs font-bold rounded-full shadow-lg">
                            UPCOMING
                        </span>
                    @elseif($tournament->status === 'active')
                        <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                            JOIN NOW
                        </span>
                    @elseif($tournament->status === 'inprogress')
                        <span class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                            LIVE
                        </span>
                    @elseif($tournament->status === 'completed')
                        <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs font-bold rounded-full shadow-lg">
                            COMPLETED
                        </span>
                    @endif
                </div>

                <!-- Tournament Header with Gradient -->
                <div class="relative h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 overflow-hidden">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-xl font-bold mb-2 group-hover:scale-105 transition-transform duration-300">
                            {{ $tournament->name }}
                        </h3>
                        <div class="flex items-center gap-3 text-sm opacity-90">
                            @if ($tournament->open_close === 'open')
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                                    🔓 Open Registration
                                </span>
                            @else
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                                    🔒 Closed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tournament Body -->
                <div class="p-6 space-y-6">
                    <!-- Date & Time -->
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-700">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($tournament->date)->format('F j, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($tournament->date)->format('l') }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <div class="text-xs text-gray-500 font-medium">Entry Time</div>
                                <div class="font-semibold text-gray-900 text-sm">
                                    {{ \Carbon\Carbon::parse($tournament->time_to_enter)->format('h:i A') }}
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <div class="text-xs text-gray-500 font-medium">Duration</div>
                                <div class="font-semibold text-gray-900 text-sm">
                                    {{ \Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Details -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Rounds</span>
                            <span class="font-semibold text-gray-900 bg-gray-100 px-3 py-1 rounded-full">
                                {{ $tournament->rounds }} Rounds
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Format</span>
                            <span class="font-semibold text-gray-900 bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
                                {{ $tournament->time_or_free === 'time' ? '⏱️ Timed' : '♾️ Unlimited' }}
                            </span>
                        </div>

                        @if ($tournament->elimination_type === 'percentage' && $tournament->elimination_percent)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Elimination</span>
                            <span class="font-semibold text-gray-900 bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                {{ $tournament->elimination_percent }}% per Round
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <div class="px-6 pb-6 pt-2">
                    @php $status = $tournament->status; @endphp
                    <a href="@if($status === 'active') {{ route('waiting', $tournament->id) }} @elseif($status === 'inprogress') # @elseif($status === 'completed') # @else javascript:void(0) @endif"
                       class="block w-full">
                        <button class="w-full py-3.5 rounded-xl font-bold text-sm tracking-wide transition-all duration-300 transform hover:scale-[1.02] shadow-lg
                            @if($status === 'inactive') bg-gradient-to-r from-gray-400 to-gray-500 text-white cursor-not-allowed
                            @elseif($status === 'active') bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white shadow-green-500/25
                            @elseif($status === 'inprogress') bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white shadow-red-500/25
                            @elseif($status === 'completed') bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white shadow-blue-500/25 @endif"
                            @if($status === 'inactive') disabled @endif>

                            @if($status === 'active')
                                🚀 Join Tournament
                            @elseif($status === 'inprogress')
                                📊 View Live Results
                            @elseif($status === 'completed')
                                🏆 Show Results
                            @else
                                ⏳ Coming Soon
                            @endif
                        </button>
                    </a>
                </div>

                <!-- Hover Effect -->
                <div class="absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 bg-gradient-to-r from-indigo-500/5 via-purple-500/5 to-pink-500/5 transition duration-500 pointer-events-none"></div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($tournaments->isEmpty())
    <div class="text-center py-16">
        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Tournaments Available</h3>
        <p class="text-gray-600 max-w-md mx-auto">Check back later for new tournament announcements and competitive events.</p>
    </div>
    @endif
</div>

<!-- Background Decoration -->
<div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden">
    <div class="absolute top-1/4 -left-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
    <div class="absolute top-1/3 -right-10 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-1/4 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
</div>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
@endsection
