<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\ManageGedungRuangan;
use App\Livewire\Admin\ManageWarnings;
use App\Livewire\FollowUps\ManageFollowUps;
use App\Livewire\Points\Leaderboard;
use App\Livewire\Audits\AuditReports;
use App\Livewire\Logs\ActionHistory;
use App\Livewire\Points\MyPoints;
use App\Livewire\Profile\EditProfile;
use App\Livewire\Reports\CreatePublicReport;
use App\Livewire\Reports\CreateReport;
use App\Livewire\Reports\MyReports;
use App\Livewire\Reports\ReviewReports;
use App\Livewire\Rewards\RewardPeriods;
use App\Livewire\Users\UserManagement;
use App\Models\AuditReport;
use App\Models\Gedung;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $hasShowNameOnLanding = Schema::hasColumn('users', 'show_name_on_landing');

    $topUsers = \Illuminate\Support\Facades\DB::table('points')
        ->join('users', 'users.id', '=', 'points.user_id')
        ->select(
            'users.full_name',
            'users.role',
            $hasShowNameOnLanding ? 'users.show_name_on_landing' : \Illuminate\Support\Facades\DB::raw('true as show_name_on_landing'),
            'users.user_type',
            'users.gedung',
            'users.jabatan',
            \Illuminate\Support\Facades\DB::raw('SUM(points.amount) as total_points')
        )
        ->groupBy(
            'users.id',
            'users.full_name',
            'users.role',
            $hasShowNameOnLanding ? 'users.show_name_on_landing' : \Illuminate\Support\Facades\DB::raw('true'),
            'users.user_type',
            'users.gedung',
            'users.jabatan'
        )
        ->orderByDesc('total_points')
        ->limit(10)
        ->get();

    $activeWarnings = \App\Models\Warning::public()->active()->orderByDesc('created_at')->get();

    return view('home', compact('topUsers', 'activeWarnings'));
})->name('home');

// Leaderboard bisa dilihat publik
Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');

// Alias route untuk jalur publik create/publik/reports
Route::redirect('/create/publik/reports', '/reports/public');

// Public bisa buat laporan tanpa login (tidak ada poin)
Route::get('/reports/public', CreatePublicReport::class)->name('reports.public');

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

    // PJ Area routes
    Route::middleware('role:pj_area,admin')->group(function () {
        Route::get('/reports/review', ReviewReports::class)->name('reports.review');
        Route::get('/follow-ups', ManageFollowUps::class)->name('followups.index');
        Route::get('/warnings', ManageWarnings::class)->name('warnings.index');
    });

    // Audit routes
    Route::middleware('role:spmi,admin,pimpinan,pj_area')->group(function () {
        Route::get('/audits', AuditReports::class)->name('audits.index');
        Route::get('/audits/download/{audit}', function (AuditReport $audit) {
            if (! $audit->pdf_path || ! \Illuminate\Support\Facades\Storage::disk('public')->exists('audits/' . $audit->pdf_path)) {
                abort(404);
            }

            return \Illuminate\Support\Facades\Storage::disk('public')->download('audits/' . $audit->pdf_path, $audit->title . '.pdf');
        })->name('audits.download');
        Route::get('/logs/history', ActionHistory::class)->name('logs.history');
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', UserManagement::class)->name('users.index');
        Route::get('/rewards', RewardPeriods::class)->name('rewards.index');
        Route::get('/gedung-ruangan', ManageGedungRuangan::class)->name('admin.gedung-ruangan');
    });
});
