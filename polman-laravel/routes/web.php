<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\ManageGedungRuangan;
use App\Livewire\FollowUps\ManageFollowUps;
use App\Livewire\Points\Leaderboard;
use App\Livewire\Points\MyPoints;
use App\Livewire\Profile\EditProfile;
use App\Livewire\Reports\CreateReport;
use App\Livewire\Reports\MyReports;
use App\Livewire\Reports\ReviewReports;
use App\Livewire\Rewards\RewardPeriods;
use App\Livewire\Users\UserManagement;
use App\Models\Gedung;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $topUsers = \Illuminate\Support\Facades\DB::table('points')
        ->join('users', 'users.id', '=', 'points.user_id')
        ->select(
            'users.full_name',
            'users.user_type',
            'users.gedung',
            'users.jabatan',
            \Illuminate\Support\Facades\DB::raw('SUM(points.amount) as total_points')
        )
        ->groupBy('users.id', 'users.full_name', 'users.user_type', 'users.gedung', 'users.jabatan')
        ->orderByDesc('total_points')
        ->limit(10)
        ->get();

    return view('home', compact('topUsers'));
})->name('home');

// Leaderboard bisa dilihat publik
Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');

// API: Get ruangan by gedung (for cascading dropdown)
Route::get('/api/gedung/{gedung}/ruangan', function (Gedung $gedung) {
    return $gedung->activeRuangan()->orderBy('nama')->get()->map(fn ($r) => [
        'id' => $r->id,
        'label' => "{$r->jenjang} {$r->nama}",
    ]);
})->name('api.gedung.ruangan');

/*
|--------------------------------------------------------------------------
| Protected Routes (harus login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (all authenticated users)
    Route::get('/profile', EditProfile::class)->name('profile');

    // Reports (hanya reporter yang bisa buat)
    Route::middleware('role:reporter')->group(function () {
        Route::get('/reports/create', CreateReport::class)->name('reports.create');
        Route::get('/reports/my', MyReports::class)->name('reports.my');
        Route::get('/my-points', MyPoints::class)->name('points.my');
    });

    // Manager routes
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/reports/review', ReviewReports::class)->name('reports.review');
        Route::get('/follow-ups', ManageFollowUps::class)->name('followups.index');
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', UserManagement::class)->name('users.index');
        Route::get('/rewards', RewardPeriods::class)->name('rewards.index');
        Route::get('/gedung-ruangan', ManageGedungRuangan::class)->name('admin.gedung-ruangan');
    });
});
