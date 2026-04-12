<?php

namespace App\Livewire\Points;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Leaderboard extends Component
{
    public function render()
    {
        $leaderboard = DB::table('points')
            ->join('users', 'users.id', '=', 'points.user_id')
            ->select(
                'users.id',
                'users.full_name',
                'users.user_type',
                'users.gedung',
                'users.jabatan',
                DB::raw('SUM(points.amount) as total_points'),
                DB::raw('COUNT(DISTINCT points.report_id) as total_reports')
            )
            ->groupBy('users.id', 'users.full_name', 'users.user_type', 'users.gedung', 'users.jabatan')
            ->orderByDesc('total_points')
            ->get();

        $layout = Auth::check() ? 'layouts.app' : 'layouts.guest';

        return view('livewire.points.leaderboard', compact('leaderboard'))
            ->layout($layout)
            ->title('Leaderboard');
    }
}
