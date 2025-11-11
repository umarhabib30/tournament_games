@extends('layouts.user')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($tournaments as $tournament)
                <div
                    class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100">

                    <!-- Tournament Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-5 text-white">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-semibold leading-tight">{{ $tournament->name }}</h3>
                            @if ($tournament->status === 'inactive')
                                <span
                                    class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">Upcoming</span>
                            @elseif($tournament->status === 'inprogress')
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Live</span>
                            @elseif($tournament->status === 'completed')
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Completed</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($tournament->open_close === 'open')
                                <span
                                    class="px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded border border-green-200">Open</span>
                            @else
                                <span
                                    class="px-2 py-0.5 bg-red-50 text-red-700 text-xs font-medium rounded border border-red-200">Closed</span>
                            @endif
                            <span class="text-xs text-blue-100">{{ $tournament->rounds }} Rounds</span>
                        </div>
                    </div>

                    <!-- Tournament Details -->
                    <div class="p-5 space-y-4">
                        <!-- Date -->
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($tournament->date)->format('D, M d, Y') }}
                        </div>

                        <!-- Time -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-gray-500">Entry Time</div>
                                <div class="font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($tournament->time_to_enter)->format('h:i A') }}
                                </div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-gray-500">Duration</div>
                                <div class="font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($tournament->end_time)->format('h:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg border border-blue-200">
                                {{ $tournament->time_or_free === 'time' ? 'Timed Rounds' : 'Unlimited Time' }}
                            </span>
                            @if ($tournament->elimination_type === 'percentage' && $tournament->elimination_percent)
                                <span class="px-3 py-1 bg-orange-50 text-orange-700 rounded-lg border border-orange-200">
                                    {{ $tournament->elimination_percent }}% Elimination
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-lg border border-green-200">
                                    Play to End
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="p-5 pt-0">
                        @php
                            $status = $tournament->status;
                        @endphp

                        <a
                            @if ($status === 'active') href="{{ route('waiting', $tournament->id) }}"
                            @elseif ($status === 'inprogress')
                                href="#"
                            @elseif ($status === 'complete')
                                href="#"
                            @else
                                href="javascript:void(0)" @endif>
                            <button
                                class="w-full py-2.5 rounded-lg font-medium transition-colors duration-200
                                @if ($status === 'inactive') bg-gray-200 text-gray-600 cursor-not-allowed
                                @elseif ($status === 'active') bg-green-600 hover:bg-green-700 text-white
                                @elseif ($status === 'inprogress') bg-blue-600 hover:bg-blue-700 text-white
                                @elseif ($status === 'complete') bg-purple-600 hover:bg-purple-700 text-white @endif"
                                @if ($status === 'inactive') disabled @endif>
                                @if ($status === 'active')
                                    Join Tournament
                                @elseif ($status === 'inprogress')
                                    View Live
                                @elseif ($status === 'complete')
                                    Show Results
                                @else
                                    Coming Soon
                                @endif
                            </button>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
