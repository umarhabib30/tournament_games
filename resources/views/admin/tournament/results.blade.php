@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <h5 class="card-header">{{ $heading }} - {{ $tournament->name }}</h5>
            <div class="card-body">

                @if ($results->count())
                    <table class="table table-striped table-bordered">
                        <thead>
                            @if ($tournament->elimination_type === 'all')
                                <tr>
                                    <th>Final Rank</th>
                                    <th>Player</th>
                                    <th>Total Score</th>
                                    <th>Total Time</th>
                                    <th>Total Position</th>
                                    <th>Rounds</th>
                                </tr>
                            @else
                                <tr>
                                    <th>Final Rank</th>
                                    <th>Player</th>
                                    <th>Score</th>
                                    <th>Time</th>
                                    <th>Position</th>
                                    <th>Round</th>
                                </tr>
                            @endif
                        </thead>

                        <tbody>

                            {{-- ========================================================= --}}
                            {{--              ELIMINATION TYPE = ALL (Best of all rounds) --}}
                            {{-- ========================================================= --}}
                            @if ($tournament->elimination_type === 'all')

                                @foreach ($results as $item)
                                    <tr class="
                                        {{ $item['final_rank'] == 1 ? 'table-warning' : '' }}
                                        {{ $item['final_rank'] == 2 ? 'table-secondary' : '' }}
                                        {{ $item['final_rank'] == 3 ? 'table-success'   : '' }}
                                    ">
                                        <td>
                                            @if ($item['final_rank'] == 1) 🥇 1st
                                            @elseif ($item['final_rank'] == 2) 🥈 2nd
                                            @elseif ($item['final_rank'] == 3) 🥉 3rd
                                            @else {{ $item['final_rank'] }}th
                                            @endif
                                        </td>

                                        <td>{{ $item['user']->username }}</td>

                                        <td>{{ $item['total_score'] }}</td>

                                        <td>{{ $item['formatted_time'] }}</td>

                                        <td>{{ $item['total_position'] }}</td>

                                        <td>
                                            @foreach ($item['rounds'] as $round)
                                                <span class="badge badge-info">
                                                    R{{ $round['round'] }}:
                                                    Score {{ $round['score'] }},
                                                    Time {{ $round['time'] }},
                                                    Pos {{ $round['position'] }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach

                            {{-- ========================================================= --}}
                            {{--        ELIMINATION TYPE = PERCENTAGE (Last valid round) --}}
                            {{-- ========================================================= --}}
                            @else

                                @foreach ($results as $item)
                                    <tr class="
                                        {{ $item['position'] == 1 ? 'table-warning' : '' }}
                                        {{ $item['position'] == 2 ? 'table-secondary' : '' }}
                                        {{ $item['position'] == 3 ? 'table-success'   : '' }}
                                    ">
                                        <td>
                                            @if ($item['position'] == 1) 🥇 1st
                                            @elseif ($item['position'] == 2) 🥈 2nd
                                            @elseif ($item['position'] == 3) 🥉 3rd
                                            @else {{ $item['position'] }}th
                                            @endif
                                        </td>

                                        <td>{{ $item['user']->username }}</td>

                                        <td>{{ $item['score'] }}</td>

                                        <td>{{ $item['formatted_time'] }}</td>

                                        <td>{{ $item['position'] }}</td>

                                        <td>R{{ $item['round'] }}</td>
                                    </tr>
                                @endforeach

                            @endif

                        </tbody>

                    </table>
                @else
                    <p class="text-muted">No results found.</p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
