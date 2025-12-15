<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

    <div class="text-center mb-8 p-4 space-y-4">
        <h1 class="text-4xl font-bold text-blue-400">{{ $tournament->name }} - Tournament Results</h1>
        <p class="text-xl text-gray-300">Leaderboard</p>
    </div>

    <div class="max-w-5xl mx-auto p-4">
        <table class="w-full bg-gray-800 rounded-lg overflow-hidden shadow-lg">
            <thead class="bg-gray-700 text-gray-300">
                @if ($tournament->elimination_type === 'all')
                    <tr>
                        <th class="px-4 py-3 text-left">Final Rank</th>
                        <th class="px-4 py-3 text-left">Player</th>
                        <th class="px-4 py-3 text-left">Total Score</th>
                        <th class="px-4 py-3 text-left">Total Time</th>
                        <th class="px-4 py-3 text-left">Positions</th>
                        {{-- <th class="px-4 py-3 text-left">Rounds</th> --}}
                    </tr>
                @else
                    <tr>
                        <th class="px-4 py-3 text-left">Final Rank</th>
                        <th class="px-4 py-3 text-left">Player</th>
                        <th class="px-4 py-3 text-left">Score</th>
                        <th class="px-4 py-3 text-left">Time</th>
                        <th class="px-4 py-3 text-left">Position</th>
                        {{-- <th class="px-4 py-3 text-left">Round</th> --}}
                    </tr>
                @endif
            </thead>

            <tbody>
                {{-- ========================================================= --}}
                {{--              ELIMINATION TYPE = ALL (overall)            --}}
                {{-- ========================================================= --}}
                @if ($tournament->elimination_type === 'all')

                    @foreach ($results as $item)
                        @php
                            $rank = $item['final_rank'];
                            $user = $item['user'];
                            $medal = $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : ($rank == 3 ? '🥉' : ''));
                            $color =
                                $rank == 1
                                    ? 'bg-yellow-600'
                                    : ($rank == 2
                                        ? 'bg-gray-500'
                                        : ($rank == 3
                                            ? 'bg-green-600'
                                            : 'bg-gray-800'));
                        @endphp

                        <tr class="border-b border-gray-700 {{ $color }} bg-opacity-20">
                            <td class="px-4 py-3 font-bold">
                                {{ $medal }}
                                {{ $rank }}{{ $rank == 1 ? 'st' : ($rank == 2 ? 'nd' : ($rank == 3 ? 'rd' : 'th')) }}
                            </td>

                            <td class="px-4 py-3">{{ $user->username }}</td>

                            <td class="px-4 py-3">{{ $item['total_score'] }}</td>

                            <td class="px-4 py-3">{{ $item['formatted_time'] }}</td>

                            <td class="px-4 py-3">{{ $item['total_position'] }}</td>

                            {{-- <td class="px-4 py-3 space-x-2">
                                @foreach ($item['rounds'] as $round)
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-sm inline-block mb-1">
                                        R{{ $round['round'] }}:
                                        Score {{ $round['score'] }},
                                        Time {{ $round['time'] }},
                                        Pos {{ $round['position'] }}
                                    </span>
                                @endforeach
                            </td> --}}
                        </tr>
                    @endforeach

                {{-- ========================================================= --}}
                {{--       ELIMINATION TYPE = PERCENTAGE (final valid round) --}}
                {{-- ========================================================= --}}
                @else

                    @foreach ($results as $item)
                        @php
                            $rank = $item['position'];
                            $user = $item['user'];
                            $medal = $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : ($rank == 3 ? '🥉' : ''));
                            $color =
                                $rank == 1
                                    ? 'bg-yellow-600'
                                    : ($rank == 2
                                        ? 'bg-gray-500'
                                        : ($rank == 3
                                            ? 'bg-green-600'
                                            : 'bg-gray-800'));
                        @endphp

                        <tr class="border-b border-gray-700 {{ $color }} bg-opacity-20">
                            <td class="px-4 py-3 font-bold">
                                {{ $medal }}
                                {{ $rank }}{{ $rank == 1 ? 'st' : ($rank == 2 ? 'nd' : ($rank == 3 ? 'rd' : 'th')) }}
                            </td>

                            <td class="px-4 py-3">{{ $user->username }}</td>

                            <td class="px-4 py-3">{{ $item['score'] }}</td>

                            <td class="px-4 py-3">{{ $item['formatted_time'] }}</td>

                            <td class="px-4 py-3">{{ $item['position'] }}</td>

                            <td class="px-4 py-3">R{{ $item['round'] }}</td>
                        </tr>
                    @endforeach

                @endif
            </tbody>
        </table>
    </div>

    <div class="text-center mt-6">
        <a href="{{ url('tournaments') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
            Back to Tournaments
        </a>
    </div>

</body>

</html>
