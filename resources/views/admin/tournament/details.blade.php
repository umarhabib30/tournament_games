@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Tournament Details</h5>
            <div class="card-body">

                {{-- Tournament Info --}}
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{ $tournament->name }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $tournament->date }}</td>
                    </tr>
                    <tr>
                        <th>Start Time</th>
                        <td>{{ $tournament->start_time }}</td>
                    </tr>
                    <tr>
                        <th>End Time</th>
                        <td>{{ $tournament->end_time }}</td>
                    </tr>
                    <tr>
                        <th>Time to Enter</th>
                        <td>{{ $tournament->time_to_enter ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Open / Close</th>
                        <td>
                            <span class="badge {{ $tournament->open_close == 'open' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($tournament->open_close) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tournament Type</th>
                        <td>{{ $tournament->time_or_free == 'time' ? 'Knock Out' : 'Free Form' }}</td>
                    </tr>
                    <tr>
                        <th>Elimination Type</th>
                        <td>
                            @if($tournament->elimination_type == 'percentage')
                                By Percentage ({{ $tournament->elimination_percent }}%)
                            @else
                                Play till end
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($tournament->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($tournament->status == 'inactive')
                                <span class="badge badge-secondary">Inactive</span>
                            @elseif($tournament->status == 'inprogress')
                                <span class="badge badge-warning">In Progress</span>
                            @elseif($tournament->status == 'completed')
                                <span class="badge badge-dark">Completed</span>
                            @endif
                        </td>
                    </tr>
                </table>

                {{-- Rounds Section --}}
                <h5 class="mt-4">Rounds</h5>
                @if($tournament->tournament_rounds->count())
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sequence</th>
                                <th>Game</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tournament->tournament_rounds as $round)
                                <tr>
                                    <td>{{ $round->sequence }}</td>
                                    <td>{{ $round->get_game->title ?? 'N/A' }}</td>
                                    <td>
                                        @if($round->start_time)
                                            {{ $round->start_time }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($round->end_time)
                                            {{ $round->end_time }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No rounds have been added for this tournament.</p>
                @endif

                {{-- Back button --}}
                <a href="{{ route('admin.tournament') }}" class="btn btn-secondary mt-3">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
