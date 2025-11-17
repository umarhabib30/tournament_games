<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Selected year from filter — default = current year
        $year = $request->year ?? now()->year;

        // Fetch tournaments for the selected year
        $tournaments = Tournament::whereYear('date', $year)->get();

        // Monthly stats (Jan–Dec)
        $monthlyCounts = Tournament::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Prepare 12-month format array for charts
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = $monthlyCounts[$m] ?? 0;
        }

        // Overall stats
        $totalTournaments = Tournament::count();
        $totalUpcoming = Tournament::whereDate('date', '>=', now())->count();
        $totalPast = Tournament::whereDate('date', '<', now())->count();

        $data = [
            'heading' => 'Dashboard',
            'title' => 'View Dashboard',
            'active' => 'Dashboard',
            'year' => $year,
            'monthlyData' => array_values($monthlyData),
            'totalTournaments' => $totalTournaments,
            'totalUpcoming' => $totalUpcoming,
            'totalPast' => $totalPast,
        ];

        return view('admin.dashboard.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
