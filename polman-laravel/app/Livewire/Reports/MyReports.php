<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class MyReports extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filterStatus  = '';
    public string $filterKategori = '';
    public string $sortBy        = 'created_at';
    public string $sortDir       = 'desc';
    public int    $perPage       = 10;

    protected $queryString = [
        'search'         => ['except' => ''],
        'filterStatus'   => ['except' => ''],
        'filterKategori' => ['except' => ''],
        'sortBy'         => ['except' => 'created_at'],
        'sortDir'        => ['except' => 'desc'],
    ];

    public function updatingSearch()   { $this->resetPage(); }
    public function updatingFilterStatus()   { $this->resetPage(); }
    public function updatingFilterKategori() { $this->resetPage(); }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function export(): StreamedResponse
    {
        $reports = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($reports) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode', 'Kategori', 'Lokasi', 'Prioritas', 'Status', 'Tanggal']);
            foreach ($reports as $r) {
                fputcsv($handle, [
                    $r->code,
                    $r->kategori,
                    $r->lokasi,
                    $r->prioritas_label,
                    $r->status_label,
                    $r->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        }, 'laporan-saya-' . now()->format('Ymd') . '.csv');
    }

    private function buildQuery()
    {
        $q = Report::with('location')->where('reporter_id', Auth::id());

        if ($this->search) {
            $q->where(function ($sub) {
                $sub->where('deskripsi', 'ILIKE', "%{$this->search}%")
                    ->orWhere('lokasi',    'ILIKE', "%{$this->search}%");
            });
        }
        if ($this->filterStatus)   $q->where('status',   $this->filterStatus);
        if ($this->filterKategori) $q->where('kategori', $this->filterKategori);

        $allowed = ['created_at', 'kategori', 'prioritas', 'status'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'created_at';
        $q->orderBy($col, $this->sortDir);

        return $q;
    }

    public function render()
    {
        $reports = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.reports.my-reports', compact('reports'))
            ->title('Laporan Saya');
    }
}
