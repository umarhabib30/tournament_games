@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">Tournament Dashboard</h2>

    {{-- Year Filter --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <form method="GET">
                <label>Select Year</label>
                <select class="form-control" name="year" onchange="this.form.submit()">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow p-3 text-center">
                <h5>Total Tournaments</h5>
                <h2 class="text-primary">{{ $totalTournaments }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3 text-center">
                <h5>Upcoming Tournaments</h5>
                <h2 class="text-success">{{ $totalUpcoming }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3 text-center">
                <h5>Past Tournaments</h5>
                <h2 class="text-danger">{{ $totalPast }}</h2>
            </div>
        </div>
    </div>

    {{-- Bar Chart --}}
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow p-4">
                <h5 class="mb-3">Tournaments per Month ({{ $year }})</h5>
                <div style="height:350px;">
                    <canvas id="tournamentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const data = @json($monthlyData);

    const ctx = document.getElementById('tournamentChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Tournaments',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5,
                maxBarThickness: 35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

});
</script>
@endsection
