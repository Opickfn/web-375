<?php

namespace App\Livewire\Points;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MyPoints extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';
    public int $perPage = 15;

    public function render()
    {
        $user = Auth::user();
        $totalPoints = $user->totalPoints();
        $points = $this->buildQuery()->paginate($this->perPage);

    return view('livewire.points.my-points-fixed', compact('totalPoints', 'points'))
            ->title('Poin Saya');
    }
    
    // MyPoints.php additions — add sort + export + search

    // New properties:
    // public string $search     = '';
    // public string $filterType = '';
    // public string $sortBy     = 'created_at';
    // public string $sortDir    = 'desc';
    // public int    $perPage    = 15;

    // New methods:
    public function sort(string $column): void
    {
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'desc') ? 'asc' : 'desc';
        $this->sortBy  = $column;
        $this->resetPage();
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $points = $this->buildQuery()->get();
        return response()->streamDownload(function () use ($points) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Tanggal', 'Kode Laporan', 'Deskripsi', 'Tipe', 'Poin']);
            foreach ($points as $p) {
                fputcsv($h, [$p->created_at->format('d/m/Y H:i'), $p->report?->code ?? '—', $p->description, $p->type, $p->amount]);
            }
            fclose($h);
        }, 'poin-saya-' . now()->format('Ymd') . '.csv');
    }

    private function buildQuery()
    {
        $q = \Illuminate\Support\Facades\Auth::user()->points()->with('report');
        if ($this->search) $q->where('description', 'ILIKE', "%{$this->search}%");
        if ($this->filterType) $q->where('type', $this->filterType);
        $allowed = ['created_at', 'amount', 'type'];
        $q->orderBy(in_array($this->sortBy, $allowed) ? $this->sortBy : 'created_at', $this->sortDir);
        return $q;
    }

    // In render(), replace points query:
    // $totalPoints = $user->totalPoints();
    // $points = $this->buildQuery()->paginate($this->perPage);

}



