<?php

namespace App\Livewire\Points;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class Leaderboard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $hasShowNameOnLanding = Schema::hasColumn('users', 'show_name_on_landing');

        $leaderboard = DB::table('points')
            ->join('reports', 'reports.id', '=', 'points.report_id')
            ->join('users', 'users.id', '=', 'points.user_id')
            ->where('reports.status', 'approved')
            ->select(
                'users.id',
                'users.full_name',
                'users.role',
                $hasShowNameOnLanding ? 'users.show_name_on_landing' : DB::raw('true as show_name_on_landing'),
                'users.user_type',
                'users.gedung',
                'users.jabatan',
                DB::raw('SUM(points.amount) as total_points'),
                DB::raw('COUNT(DISTINCT points.report_id) as total_reports')
            )
            ->groupBy(
                'users.id',
                'users.full_name',
                'users.role',
                $hasShowNameOnLanding ? 'users.show_name_on_landing' : DB::raw('true'),
                'users.user_type',
                'users.gedung',
                'users.jabatan'
            )
            ->orderByDesc('total_points')
            ->paginate(10);

        $layout = Auth::check() ? 'layouts.app' : 'layouts.guest';

        return view('livewire.points.leaderboard', compact('leaderboard'))
            ->layout($layout)
            ->title('Leaderboard');
    }
}
