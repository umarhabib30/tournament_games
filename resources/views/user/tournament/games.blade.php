<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Play Tournament</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

    <!-- Tournament Header -->
    <div class="text-center mb-8 p-4 space-y-4">
        <h1 class="text-4xl font-bold text-blue-400">
            Hello {{ Auth::user()->username }}! Welcome to {{ $tournament->name }}
        </h1>
        <p class="text-xl text-gray-300">Choose a round and start playing!</p>
    </div>

    <!-- Tournament Rounds -->
    <div class="max-w-4xl mx-auto p-4 space-y-6">
        @foreach ($tournament->tournament_rounds as $round)
            @php
                // Check if the user has played the current round
                $is_played = \App\Models\Result::where('user_id', Auth::id())
                    ->where('tournament_id', $tournament->id)
                    ->where('round_id', $round->id)
                    ->first();

                // Check if the user has played the previous round
                $previous_round_played = true;
                if ($round->sequence > 1) {
                    $previous_round = $tournament->tournament_rounds->where('sequence', $round->sequence - 1)->first();
                    $previous_round_played = \App\Models\Result::where('user_id', Auth::id())
                        ->where('tournament_id', $tournament->id)
                        ->where('round_id', $previous_round->id)
                        ->exists();
                }
            @endphp

            <div class="bg-gray-800 bg-opacity-50 backdrop-blur-lg rounded-2xl p-6 border border-gray-700">
                <div class="absolute top-4 right-4 text-white text-sm font-semibold px-3 py-1 rounded-full">
                    @if ($is_played) Score : {{ $is_played->score }}/85 @else Score : 0/85

                    @endif
                </div>
                <h2 class="text-2xl font-bold text-blue-400 mb-4">Round {{ $round->sequence }}</h2>

                @if ($tournament->time_or_free == 'time')
                    <!-- Round Start and End Time -->
                    <div class="grid grid-cols-2 justify-between items-center">
                        <div>
                            <p><strong>Start Time:</strong>
                                {{ \Carbon\Carbon::parse($round->start_time)->format('h:i a') }}</p>
                        </div>
                        <div>
                            <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($round->end_time)->format('h:i a') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Games in this round -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-700 rounded-lg p-4">

                        <div class="text-white w-full sm:w-3/4">
                            <p><strong>Game: </strong>{{ $round->get_game->title }}</p>

                            @php
                                // Fetch and display game rules
                                $rules = json_decode($round->get_game->rules);
                            @endphp

                            <!-- Display the rules -->
                            @foreach ($rules as $rule)
                                <p class="text-sm text-gray-300">- {{ $rule }}</p>
                            @endforeach
                        </div>


                        <!-- Play Game Button -->
                        <div class="mt-4 sm:mt-0 sm:w-1/4 text-center">
                            <form action="{{ route('play.game') }}" method="GET"
                                @if (!$previous_round_played || $is_played) onsubmit="return false;" @endif>

                                <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                                <input type="hidden" name="round_id" value="{{ $round->id }}">
                                <input type="hidden" name="game_id" value="{{ $round->get_game->id }}">

                                <button type="@if ($previous_round_played && !$is_played) submit @else button @endif"
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300
                                 @if (!$previous_round_played || $is_played) opacity-50 cursor-not-allowed @endif"
                                    @if (!$previous_round_played || $is_played) disabled @endif>
                                    Play Game
                                </button>
                            </form>
                        </div>

                    </div>

                    <!-- Static Score for the round -->

                </div>
            </div>
        @endforeach
    </div>
    <a href="{{ url('tournaments') }}" class="text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">Go to Tournaments</a>

</body>

</html>
