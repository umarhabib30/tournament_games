@extends('layouts.admin')

@section('style')
    <style>
        .results-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
            border-radius: 16px;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .results-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 55%);
            pointer-events: none;
        }

        .results-stat-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e9ecef;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .results-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
        }

        .results-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .visibility-panel {
            border-radius: 14px;
            border: 1px solid #e9ecef;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .status-pill-live {
            background: #dcfce7;
            color: #166534;
        }

        .status-pill-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-pill-progress {
            background: #e2e8f0;
            color: #475569;
        }

        .leaderboard-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .leaderboard-card .card-header {
            background: #fff;
            border-bottom: 1px solid #edf2f7;
            padding: 1.25rem 1.5rem;
        }

        .results-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0 !important;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .results-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .results-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .rank-1 {
            background: linear-gradient(135deg, #fde68a, #f59e0b);
            color: #78350f;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
        }

        .rank-2 {
            background: linear-gradient(135deg, #e2e8f0, #94a3b8);
            color: #334155;
            box-shadow: 0 4px 14px rgba(148, 163, 184, 0.35);
        }

        .rank-3 {
            background: linear-gradient(135deg, #fdba74, #ea580c);
            color: #7c2d12;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
        }

        .rank-default {
            background: #f1f5f9;
            color: #475569;
        }

        .player-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .round-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            border-radius: 999px;
            padding: 0.3rem 0.65rem;
            margin: 0.15rem 0.25rem 0.15rem 0;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .metric-value {
            font-weight: 700;
            color: #0f172a;
        }

        .empty-results {
            text-align: center;
            padding: 3.5rem 1.5rem;
            color: #64748b;
        }

        .empty-results i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .btn-publish {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1.25rem;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.35);
        }

        .btn-publish:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(34, 197, 94, 0.4);
        }
    </style>
@endsection

@section('content')
    @php
        $playerCount = $results->count();
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="results-hero p-4 p-md-5 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start position-relative" style="z-index: 1;">
                    <div class="mb-3 mb-md-0">
                        <a href="{{ route('admin.tournament') }}"
                            class="text-white-50 small d-inline-flex align-items-center mb-3">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Tournaments
                        </a>
                        <h2 class="mb-2 font-weight-bold text-white">{{ $tournament->name }}</h2>
                        <p class="mb-0 text-white-50">
                            {{ \Carbon\Carbon::parse($tournament->date)->format('M d, Y') }}
                            &bull;
                            {{ \Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($tournament->end_time)->format('h:i A') }}
                        </p>
                    </div>

                    <div class="text-md-right">
                        @if ($tournament->results_published)
                            <span class="status-pill status-pill-live">
                                <i class="fas fa-check-circle"></i> Live for players
                            </span>
                        @elseif ($tournament->hasEnded())
                            <span class="status-pill status-pill-pending">
                                <i class="fas fa-hourglass-half"></i> Ready to publish
                            </span>
                        @else
                            <span class="status-pill status-pill-progress">
                                <i class="fas fa-play-circle"></i> Tournament in progress
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="results-stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="results-stat-icon bg-primary text-white mr-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Players Ranked</div>
                                <div class="h4 mb-0 font-weight-bold">{{ $playerCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="results-stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="results-stat-icon bg-info text-white mr-3">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Format</div>
                                <div class="h6 mb-0 font-weight-bold">
                                    {{ $tournament->elimination_type === 'percentage' ? 'Elimination' : 'Play till End' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="results-stat-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="results-stat-icon bg-warning text-white mr-3">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase font-weight-bold">Rounds</div>
                                <div class="h4 mb-0 font-weight-bold">{{ $tournament->rounds ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="visibility-panel p-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h5 class="mb-2 font-weight-bold">Results Visibility</h5>
                        @if ($tournament->results_published)
                            <p class="text-muted mb-0">
                                Players can view the leaderboard.
                                @if ($tournament->results_published_at)
                                    Opened on {{ $tournament->results_published_at->format('M d, Y \a\t h:i A') }}.
                                @endif
                            </p>
                        @elseif ($tournament->hasEnded())
                            <p class="text-muted mb-0">
                                The tournament has ended. Review the leaderboard below, then open results when you are ready.
                            </p>
                        @else
                            <p class="text-muted mb-0">
                                Results can be published after the tournament end time.
                            </p>
                        @endif
                    </div>

                    @if ($tournament->canPublishResults())
                        <form id="publish-results-form"
                            action="{{ route('admin.tournament.publish.results', $tournament->id) }}" method="POST">
                            @csrf
                            <button type="button" id="publish-results-btn" class="btn-admin btn-admin-success btn-admin-lg">
                                <i class="fas fa-unlock-alt"></i> Open Results to Players
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card leaderboard-card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 font-weight-bold">Leaderboard</h5>
                        <small class="text-muted">Admin preview — same data players will see after publish</small>
                    </div>
                    <span class="badge badge-light border px-3 py-2">
                        {{ $tournament->time_or_free === 'time' ? 'Timed Rounds' : 'Free Form' }}
                    </span>
                </div>

                <div class="card-body p-0">
                    @if ($results->count())
                        <div class="table-responsive">
                            <table class="table table-hover results-table mb-0">
                                <thead>
                                    @if ($tournament->elimination_type === 'all')
                                        <tr>
                                            <th>Rank</th>
                                            <th>Player</th>
                                            <th>Total Score</th>
                                            <th>Total Time</th>
                                            <th>Total Position</th>
                                            <th>Round Breakdown</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Rank</th>
                                            <th>Player</th>
                                            <th>Score</th>
                                            <th>Time</th>
                                            <th>Position</th>
                                            <th>Round</th>
                                        </tr>
                                    @endif
                                </thead>
                                <tbody>
                                    @if ($tournament->elimination_type === 'all')
                                        @foreach ($results as $item)
                                            @php $rank = $item['final_rank']; @endphp
                                            <tr>
                                                <td>
                                                    <span class="rank-badge {{ $rank == 1 ? 'rank-1' : ($rank == 2 ? 'rank-2' : ($rank == 3 ? 'rank-3' : 'rank-default')) }}">
                                                        @if ($rank == 1) 🥇
                                                        @elseif ($rank == 2) 🥈
                                                        @elseif ($rank == 3) 🥉
                                                        @else #{{ $rank }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="player-avatar mr-3">
                                                            {{ strtoupper(substr($item['user']->username, 0, 1)) }}
                                                        </span>
                                                        <div>
                                                            <div class="metric-value">{{ $item['user']->username }}</div>
                                                            <small class="text-muted">Player</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="metric-value">{{ $item['total_score'] }}</span></td>
                                                <td><span class="metric-value">{{ $item['formatted_time'] }}</span></td>
                                                <td><span class="metric-value">{{ $item['total_position'] }}</span></td>
                                                <td>
                                                    @foreach ($item['rounds'] as $round)
                                                        <span class="round-chip">
                                                            R{{ $round['round'] }} · {{ $round['score'] }} pts · {{ $round['time'] }}s
                                                        </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($results as $item)
                                            @php $rank = $item['position']; @endphp
                                            <tr>
                                                <td>
                                                    <span class="rank-badge {{ $rank == 1 ? 'rank-1' : ($rank == 2 ? 'rank-2' : ($rank == 3 ? 'rank-3' : 'rank-default')) }}">
                                                        @if ($rank == 1) 🥇
                                                        @elseif ($rank == 2) 🥈
                                                        @elseif ($rank == 3) 🥉
                                                        @else #{{ $rank }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="player-avatar mr-3">
                                                            {{ strtoupper(substr($item['user']->username, 0, 1)) }}
                                                        </span>
                                                        <div>
                                                            <div class="metric-value">{{ $item['user']->username }}</div>
                                                            <small class="text-muted">Player</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="metric-value">{{ $item['score'] }}</span></td>
                                                <td><span class="metric-value">{{ $item['formatted_time'] }}</span></td>
                                                <td><span class="metric-value">{{ $item['position'] }}</span></td>
                                                <td><span class="badge badge-primary px-3 py-2">Round {{ $item['round'] }}</span></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-results">
                            <i class="fas fa-chart-bar d-block"></i>
                            <h5 class="font-weight-bold text-dark">No results yet</h5>
                            <p class="mb-0">Player scores will appear here once rounds are completed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#publish-results-btn').on('click', function() {
                Swal.fire({
                    title: 'Open results to players?',
                    html: 'All players will be able to view the <strong>{{ $tournament->name }}</strong> leaderboard.<br><br>This action cannot be undone.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-unlock-alt mr-1"></i> Yes, open results',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Publishing results...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        $('#publish-results-form').submit();
                    }
                });
            });
        });
    </script>
@endsection
