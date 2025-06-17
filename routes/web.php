<?php

use Illuminate\Support\Facades\Route;
// use App\Livewire\Pages\Dashboard; => Legacy
use App\Livewire\Cameras;
use App\Livewire\Trafics;
use App\Livewire\Congestions;
use App\Livewire\Intersections;
use App\Livewire\Login;
use App\Livewire\Traveltimes;
use App\Livewire\Settings;
use App\Livewire\Simulations;
use App\Livewire\Maps;
use App\Models\Intersection;
use App\Livewire\SurveyPage;
use App\Livewire\Dashboard;
use App\Http\Controllers\IntersectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Public routes
Route::get('/', function () {
    return view('components.layouts.landing');
});

// Authentication routes
Route::get('/login', Login::class)->name('login');
Route::get('/auth/sso', [Login::class, 'loginWithSSOKeycloak'])->name('sso.login');
Route::get('/auth/callback', [Login::class, 'handleSSOCallbackKeycloak'])->name('sso.callback');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [Login::class, 'destroy'])->name('logout');
    Route::get('/maps', Maps::class)->name('maps');
});
// Route::get('/dashboard', Dashboard::class)->name('dashboard-legacy');
Route::get('/intersections', Intersections::class)->name('intersections');
Route::get('/cameras', Cameras::class)->name('cameras');
Route::get('/traffic-flow', Trafics::class)->name('traffic-flow');
Route::get('/congestions', Congestions::class)->name('congestions');
Route::get('/travel-times', Traveltimes::class)->name('travel-times');
Route::get('/settings', Settings::class)->name('settings');
Route::get('/simulations', Simulations::class)->name('simulations');


Route::get('/tutorial/intersections', [IntersectionController::class, 'tutorial'])->name('tutorial');
Route::get('/simulations/intersections', [IntersectionController::class, 'simulator'])->name('simulator');
Route::get('/simulations/intersections-check', [IntersectionController::class, 'check'])->name('check');


Route::get('/survey', SurveyPage::class)->name('survey');
Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::get('/api/traffic-data', function (Request $request) {
    // $origin = $request->query('origin', 'all');

    // $query = DB::table('traffic_volume')
    //     ->select(DB::raw('hour(timestamp) as hour, count(*) as count'))
    //     ->when($origin !== 'all', function ($q) use ($origin) {
    //         return $q->where('origin', $origin);
    //     })
    //     ->groupBy('hour')
    //     ->orderBy('hour')
    //     ->get();

    // // Data tambahan untuk "4 x 15 Menit Tertinggi"
    // $highPeakQuery = DB::table('traffic_volume')
    //     ->select(DB::raw('hour(timestamp) as hour, round(avg(count(*) * 1.2)) as count'))
    //     ->when($origin !== 'all', function ($q) use ($origin) {
    //         return $q->where('origin', $origin);
    //     })
    //     ->groupBy('hour')
    //     ->orderBy('hour')
    //     ->get();

    // // Data untuk Volume Jam Perancangan (2 Grafik Bawah)
    // $design1Query = DB::table('traffic_volume')
    //     ->select(DB::raw('hour(timestamp) as hour, count(*) * 0.8 as count'))
    //     ->when($origin !== 'all', function ($q) use ($origin) {
    //         return $q->where('origin', $origin);
    //     })
    //     ->groupBy('hour')
    //     ->orderBy('hour')
    //     ->get();

    // $design2Query = DB::table('traffic_volume')
    //     ->select(DB::raw('hour(timestamp) as hour, count(*) * 0.9 as count'))
    //     ->when($origin !== 'all', function ($q) use ($origin) {
    //         return $q->where('origin', $origin);
    //     })
    //     ->groupBy('hour')
    //     ->orderBy('hour')
    //     ->get();

    // return response()->json([
    //     'categories' => $query->pluck('hour'),
    //     'ljr_values' => $query->pluck('count'),
    //     'high_peak_values' => $highPeakQuery->pluck('count'),
    //     'design1_values' => $design1Query->pluck('count'),
    //     'design2_values' => $design2Query->pluck('count')
    // ]);

    $origin = $request->query('origin', 'all');

    // Simulasi data jam (0-23)
    $hours = range(0, 23);

    // Generate data acak antara 50 - 500 kendaraan/jam
    $randomData = array_map(fn() => rand(50, 500), $hours);
    $highPeakData = array_map(fn($val) => round($val * 1.2), $randomData);
    $design1Data = array_map(fn($val) => round($val * 0.8), $randomData);
    $design2Data = array_map(fn($val) => round($val * 0.9), $randomData);

    return response()->json([
        'categories' => $hours,
        'ljr_values' => $randomData,
        'high_peak_values' => $highPeakData,
        'design1_values' => $design1Data,
        'design2_values' => $design2Data
    ]);
});

Route::get('/api/traffic-analysis', [App\Http\Controllers\TrafficAnalysisController::class, 'index']);
Route::get('/api/traffic-analysis/intersection/{cycleTime}/{greenTime}/{capacity}', [App\Http\Controllers\TrafficAnalysisController::class, 'intersection']);
Route::get('/api/traffic-analysis/top5-data', [App\Http\Controllers\TrafficAnalysisController::class, 'top5']);
Route::get('/api/traffic-analysis/evaluation', [App\Http\Controllers\TrafficAnalysisController::class, 'evaluation']);
Route::get('/api/traffic-analysis/summary', [App\Http\Controllers\TrafficAnalysisController::class, 'summary']);




