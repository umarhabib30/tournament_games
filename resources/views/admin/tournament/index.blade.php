@extends('layouts.admin')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <span>{{ $heading }}</span>
            <a href="{{ route('admin.tournament.create') }}" class="btn-admin btn-admin-sm btn-admin-success">
                <i class="fas fa-plus"></i> Add Tournament
            </a>
        </div>
        <div class="card-body">
            @if ($tournaments->count())
                <div class="table-responsive">
                    <table class="table table-striped table-bordered second admin-table" id="example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Elimination</th>
                                <th>Rounds</th>
                                <th>URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tournaments as $index => $tournament)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-weight-bold">{{ $tournament->name }}</td>
                                    <td>{{ $tournament->date }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($tournament->end_time)->format('h:i A') }}
                                    </td>
                                    <td>{{ $tournament->time_or_free == 'time' ? 'Knock Out' : 'Free Form' }}</td>
                                    <td>
                                        @if ($tournament->elimination_type == 'percentage')
                                            {{ $tournament->elimination_percent }}%
                                        @else
                                            Play till end
                                        @endif
                                    </td>
                                    <td>{{ $tournament->rounds }}</td>
                                    <td>
                                        <button type="button" class="btn-admin btn-admin-sm btn-admin-secondary"
                                            data-copy-url="{{ url('waiting-area', $tournament->id) }}">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </td>
                                    <td>
                                        <div class="admin-actions">
                                            <a href="{{ route('admin.tournament.details', $tournament->id) }}"
                                                class="btn-admin btn-admin-sm btn-admin-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.tournament.results', $tournament->id) }}"
                                                class="btn-admin btn-admin-sm btn-admin-info">
                                                <i class="fas fa-chart-bar"></i>
                                                @if ($tournament->results_published)
                                                    Results
                                                @elseif ($tournament->hasEnded())
                                                    Open Results
                                                @else
                                                    Preview
                                                @endif
                                            </a>
                                            @if ($tournament->results_published)
                                                <span class="admin-badge admin-badge-success">Live</span>
                                            @elseif ($tournament->hasEnded())
                                                <span class="admin-badge admin-badge-warning">Pending</span>
                                            @endif
                                            <a href="{{ route('admin.tournament.edit', $tournament->id) }}"
                                                class="btn-admin btn-admin-sm btn-admin-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn-admin btn-admin-sm btn-admin-danger"
                                                data-swal-delete="{{ route('admin.tournament.delete', $tournament->id) }}"
                                                data-swal-title="Delete tournament?"
                                                data-swal-text="This will permanently remove {{ $tournament->name }}.">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="admin-empty">
                    <i class="fas fa-trophy d-block"></i>
                    <h5 class="font-weight-bold text-dark">No tournaments yet</h5>
                    <p class="mb-3">Create your first tournament to get started.</p>
                    <a href="{{ route('admin.tournament.create') }}" class="btn-admin btn-admin-success">
                        <i class="fas fa-plus"></i> Add Tournament
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
