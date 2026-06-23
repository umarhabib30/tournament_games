@extends('layouts.admin')

@section('content')
    <div class="admin-card-hero mb-4">
        <div class="position-relative" style="z-index: 1;">
            <h2 class="text-white">Tournament Dashboard</h2>
            <p class="text-white">Overview of tournaments, activity, and yearly performance.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <form method="GET" class="admin-form">
                <label class="mb-2">Filter by year</label>
                <select class="form-control" name="year" onchange="this.form.submit()">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center">
                    <div class="admin-stat-icon primary mr-3"><i class="fas fa-trophy"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Total Tournaments</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalTournaments }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center">
                    <div class="admin-stat-icon success mr-3"><i class="fas fa-calendar-plus"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Upcoming</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalUpcoming }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center">
                    <div class="admin-stat-icon danger mr-3"><i class="fas fa-history"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Past</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalPast }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <span>Tournaments per Month ({{ $year }})</span>
            <a href="{{ route('admin.tournament.create') }}" class="btn-admin btn-admin-sm btn-admin-success">
                <i class="fas fa-plus"></i> New Tournament
            </a>
        </div>
        <div class="card-body">
            <div style="height: 350px;">
                <canvas id="tournamentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <a href="{{ route('admin.tournament') }}" class="btn-admin btn-admin-primary btn-admin-lg w-100">
                <i class="fas fa-table"></i> Manage Tournaments
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('admin.game') }}" class="btn-admin btn-admin-info btn-admin-lg w-100">
                <i class="fas fa-gamepad"></i> Manage Games
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('requests.pending') }}" class="btn-admin btn-admin-warning btn-admin-lg w-100">
                <i class="fas fa-user-check"></i> Pending Requests
            </a>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const data = @json($monthlyData);
            const ctx = document.getElementById('tournamentChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Tournaments',
                        data: data,
                        backgroundColor: 'rgba(79, 70, 229, 0.55)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 40,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: 'rgba(148, 163, 184, 0.2)' },
                        },
                        x: {
                            grid: { display: false },
                        },
                    },
                },
            });
        });
    </script>
@endsection
