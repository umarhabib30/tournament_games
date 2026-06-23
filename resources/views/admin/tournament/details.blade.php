@extends('layouts.admin')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <span>Tournament Details</span>
            <a href="{{ route('admin.tournament.edit', $tournament->id) }}" class="btn-admin btn-admin-sm btn-admin-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered admin-table">
                <tr>
                    <th width="220">Name</th>
                    <td class="font-weight-bold">{{ $tournament->name }}</td>
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
                        <span class="admin-badge {{ $tournament->open_close == 'open' ? 'admin-badge-success' : 'admin-badge-danger' }}">
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
                        @if ($tournament->elimination_type == 'percentage')
                            By Percentage ({{ $tournament->elimination_percent }}%)
                        @else
                            Play till end
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Results Status</th>
                    <td>
                        @if ($tournament->results_published)
                            <span class="admin-badge admin-badge-success">Published to players</span>
                        @elseif ($tournament->hasEnded())
                            <span class="admin-badge admin-badge-warning">Ready to publish</span>
                        @else
                            <span class="admin-badge admin-badge-muted">Tournament in progress</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($tournament->status == 'active')
                            <span class="admin-badge admin-badge-success">Active</span>
                        @elseif ($tournament->status == 'inactive')
                            <span class="admin-badge admin-badge-muted">Inactive</span>
                        @elseif ($tournament->status == 'inprogress')
                            <span class="admin-badge admin-badge-warning">In Progress</span>
                        @elseif ($tournament->status == 'completed')
                            <span class="admin-badge admin-badge-info">Completed</span>
                        @endif
                    </td>
                </tr>
            </table>

            <h5 class="mt-4 mb-3 font-weight-bold">Rounds</h5>
            @if ($tournament->tournament_rounds->count())
                <table class="table table-bordered admin-table">
                    <thead>
                        <tr>
                            <th>Sequence</th>
                            <th>Game</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tournament->tournament_rounds as $round)
                            <tr>
                                <td>{{ $round->sequence }}</td>
                                <td>{{ $round->get_game->title ?? 'N/A' }}</td>
                                <td>{{ $round->start_time ?: '—' }}</td>
                                <td>{{ $round->end_time ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No rounds have been added for this tournament.</p>
            @endif

            <div class="mt-4 admin-actions">
                <a href="{{ route('admin.tournament') }}" class="btn-admin btn-admin-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('admin.tournament.results', $tournament->id) }}" class="btn-admin btn-admin-info">
                    <i class="fas fa-chart-bar"></i> View Results
                </a>
            </div>
        </div>
    </div>
@endsection
