<?php

namespace App\Livewire\Points;

use App\Models\RewardPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class Leaderboard extends Component
{
    use WithPagination;

    public ?int $selectedPeriod = null;
    public int $limit = 0;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public string $filterType = '';
    public string $sortBy = 'total_points';
    public string $sortDir = 'desc';
    public int $perPage = 15;

    public function mount(): void
    {
        $active = RewardPeriod::active()->first();
        if ($active) {
            $this->selectedPeriod = $active->id;
        }
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingSelectedPeriod(): void { $this->resetPage(); }

    public function sort(string $column): void
    {
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'desc') ? 'asc' : 'desc';
        $this->sortBy = $column;
        $this->resetPage();
    }

    public function export(): StreamedResponse
    {
        $data = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($data) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Rank', 'Nama', 'Tipe', 'Total Laporan', 'Total Poin']);
            foreach ($data as $i => $u) {
                fputcsv($h, [
                    $i + 1,
                    ($u->role === 'reporter' && !$u->show_name_on_landing) ? 'Anonim' : $u->full_name,
                    ucfirst($u->user_type),
                    $u->total_reports,
                    $u->total_points,
                ]);
            }
            fclose($h);
        }, 'leaderboard-' . now()->format('Ymd') . '.csv');
    }

    private function buildQuery()
    {
        $hasCol = Schema::hasColumn('users', 'show_name_on_landing');

        $q = DB::table('points')
            ->join('reports', 'reports.id', '=', 'points.report_id')
            ->join('users', 'users.id', '=', 'points.user_id')
            ->where('reports.status', 'approved');

        // $q = DB::table('points')
        //     ->join('users', 'users.id', '=', 'points.user_id')
        //     ->leftJoin('reports', 'reports.id', '=', 'points.report_id');
            // Hapus where status di sini agar poin submit masuk
            
        if ($this->selectedPeriod) {
            $period = RewardPeriod::find($this->selectedPeriod);
            if ($period) {
                $q->whereBetween('points.created_at', [$period->start_date, $period->end_date]);
            }
        }

        $q->select(
            'users.id', 'users.full_name', 'users.role',
            $hasCol ? 'users.show_name_on_landing' : DB::raw('true as show_name_on_landing'),
            'users.user_type', 'users.gedung', 'users.jabatan',
            DB::raw('SUM(points.amount) as total_points'),
            DB::raw('COUNT(DISTINCT points.report_id) as total_reports')
        )->groupBy(
            'users.id', 'users.full_name', 'users.role',
            $hasCol ? 'users.show_name_on_landing' : DB::raw('true'),
            'users.user_type', 'users.gedung', 'users.jabatan'
        );

        $q->having(DB::raw('SUM(points.amount)'), '>', 0);

        if ($this->search) {
            $q->where('users.full_name', 'ILIKE', "%{$this->search}%");
        }
        if ($this->filterType) {
            $q->where('users.user_type', $this->filterType);
        }

        $allowed = ['total_points', 'total_reports', 'full_name'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'total_points';
        $q->orderBy($col, $this->sortDir);

        return $q;
    }

    public function getPeriodsProperty()
    {
        return RewardPeriod::orderBy('start_date', 'desc')->get();
    }

    public function render()
    {
        $periods = $this->getPeriodsProperty();

        if ($this->limit > 0) {
            $leaderboard = $this->buildQuery()
                ->limit($this->limit)
                ->get()
                ->values();
        } else {
            $leaderboard = $this->buildQuery()->paginate($this->perPage);
        }

        return view('livewire.points.leaderboard', compact('leaderboard', 'periods'))
            ->title('Leaderboard');
    }
}

