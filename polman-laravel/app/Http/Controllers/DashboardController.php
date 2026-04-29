<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->isPjAreaOrAbove();

        // --- Stat Cards ---
        $baseQuery = function () use ($user, $isAdmin) {
            $query = Report::query();

            if ($isAdmin) {
                // Jika dia Admin/Pimpinan/PJ Area
                if (!$user->isAdmin()) {
                    // Khusus PJ Area: Filter hanya lokasi yang ditugaskan (dan turunannya)
                    $managedIds = $user->getManagedLocationIds();
                    $query->whereIn('location_id', $managedIds);
                }
                // Jika Admin murni, tampilkan semua (tidak masuk ke if di atas)
            } else {
                // Jika Reporter/Kontributor: Hanya lihat milik sendiri
                $query->where('reporter_id', $user->id);
            }
            
            return $query;
        };

        $totalReports    = $baseQuery()->count();
        $pendingReports  = $baseQuery()->where('status', 'pending')->count();
        $resolvedReports = $baseQuery()->where('status', 'resolved')->count();
        $myPoints        = $user->totalPoints();
        $totalUsers      = User::count();

        // Recent reports (10 teratas, filter sesuai peran)
        $recentReports = $baseQuery()
            ->when($user->canCreateReport(), fn($q) => $q->where('reporter_id', $user->id))
            ->with(['reporter', 'location']) // Pastikan eager load location
            ->latest()
            ->take(10)
            ->get();

        // --- Chart: Reports by Status (Doughnut) ---
        $statusCounts = $baseQuery()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $chartStatus = [
            'labels' => ['Menunggu', 'Disetujui', 'Dalam Proses', 'Selesai', 'Ditolak'],
            'data'   => [
                $statusCounts['pending'] ?? 0,
                $statusCounts['approved'] ?? 0,
                $statusCounts['in_progress'] ?? 0,
                $statusCounts['resolved'] ?? 0,
                $statusCounts['rejected'] ?? 0,
            ],
        ];

        // --- Chart: Reports by Category (Bar) ---
        $categoryCounts = $baseQuery()
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->pluck('total', 'kategori')
            ->toArray();

        $chartCategory = [
            'labels' => array_keys($categoryCounts),
            'data'   => array_values($categoryCounts),
        ];

        // --- Chart: Monthly Trend (Line) - last 6 months ---
        $months = collect();
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->translatedFormat('M Y'));
            $monthlyData->push(
                $baseQuery()
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        $chartMonthly = [
            'labels' => $months->toArray(),
            'data'   => $monthlyData->toArray(),
        ];

        // --- Chart: Monthly Points (for reporter) ---
        $chartPoints = ['labels' => [], 'data' => []];
        if ($user->canCreateReport()) {
            $pointMonths = collect();
            $pointData = collect();
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $pointMonths->push($date->translatedFormat('M Y'));
                $pointData->push(
                    Point::where('user_id', $user->id)
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount')
                );
            }
            $chartPoints = [
                'labels' => $pointMonths->toArray(),
                'data'   => $pointData->toArray(),
            ];
        }

        return view('dashboard', compact(
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'myPoints',
            'totalUsers',
            'recentReports',
            'chartStatus',
            'chartCategory',
            'chartMonthly',
            'chartPoints'
        ));
    }
}
