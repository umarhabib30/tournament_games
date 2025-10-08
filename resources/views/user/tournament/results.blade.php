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

    <div class="max-w-4xl mx-auto p-4">
        <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Position</th>
                    <th class="px-4 py-2 text-left">Player</th>
                    <th class="px-4 py-2 text-left">Total Score</th>
                    <th class="px-4 py-2 text-left">Rounds</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    @php
                        $position = $result['position'];
                        $user = $result['user'];
                        $totalScore = $result['rounds']->sum('result');
                        $medal = $position == 1 ? '🥇' : ($position == 2 ? '🥈' : ($position == 3 ? '🥉' : ''));
                    @endphp

                    <tr class="border-b border-gray-700">
                        <td class="px-4 py-2 font-bold">{{ $medal }}
                            {{ $position }}{{ $position == 1 ? 'st' : ($position == 2 ? 'nd' : ($position == 3 ? 'rd' : 'th')) }}
                        </td>
                        <td class="px-4 py-2">{{ $user->username }}</td>
                        <td class="px-4 py-2">{{ $totalScore }}</td>
                        <td class="px-4 py-2 space-x-2">
                            @foreach ($result['rounds'] as $round)
                                <span class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                    R{{ $round['round'] }}: {{ $round['result'] }} ({{ $round['time'] ?? '0s' }})
                                </span>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
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
