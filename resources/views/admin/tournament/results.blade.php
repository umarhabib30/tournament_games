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
                                <tr>
                                    <th>Position</th>
                                    <th>Player</th>
                                    <th>Game</th>
                                    <th>Total Score</th>
                                    <th>Rounds</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $item)
                                    <tr class="{{ $item['position'] == 1 ? 'table-warning' : ($item['position'] == 2 ? 'table-secondary' : ($item['position'] == 3 ? 'table-success' : '')) }}">
                                        <td>
                                            @if ($item['position'] == 1) 🥇 1st
                                            @elseif ($item['position'] == 2) 🥈 2nd
                                            @elseif ($item['position'] == 3) 🥉 3rd
                                            @else {{ $item['position'] }}th
                                            @endif
                                        </td>
                                        <td>{{ $item['user']->username }}</td>
                                        <td>{{ $item['rounds']->first()['game'] }}</td>
                                        <td>{{ $item['rounds']->sum('result') }}</td>
                                        <td>
                                            @foreach ($item['rounds'] as $round)
                                                <span class="badge badge-info">R{{ $round['round'] }}: {{ $round['result'] }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
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
