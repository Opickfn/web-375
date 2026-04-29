<?php

namespace App\Livewire\Reports;

use App\Models\Point;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class ReviewReports extends Component
{
    use WithPagination;

    public string $search         = '';
    public string $filterKategori = '';
    public string $filterPrioritas = '';
    public string $sortBy         = 'created_at';
    public string $sortDir        = 'desc';
    public int    $perPage        = 15;
    public bool   $showModal       = false;
    public ?Report $selectedReport = null;

    public function updatingSearch()         { $this->resetPage(); }
    public function updatingFilterKategori() { $this->resetPage(); }
    public function updatingFilterPrioritas() { $this->resetPage(); }

    public function sort(string $column): void
    {
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy  = $column;
        $this->resetPage();
    }

    public function approve(int $id, string $notes = ''): void
    {
        // Cegah Pimpinan melakukan approval
        if (Auth::user()->isPimpinan()) {
            session()->flash('error', 'Pimpinan tidak memiliki otoritas untuk menyetujui laporan.');
            return;
        }

        $report = Report::findOrFail($id);
        $report->update([
            'status'       => 'approved',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $notes,
        ]);
        if ($report->reporter_id) {
            Point::create([
                'user_id'     => $report->reporter_id,
                'report_id'   => $report->id,
                'amount'      => 5,
                'type'        => 'approved',
                'description' => 'Bonus laporan disetujui ' . $report->code,
            ]);
        }
        session()->flash('success', 'Laporan ' . $report->code . ' telah disetujui.');
    }

    public function reject(int $id, string $notes = ''): void
    {
        // Cegah Pimpinan melakukan penolakan
        if (Auth::user()->isPimpinan()) {
            session()->flash('error', 'Pimpinan tidak memiliki otoritas untuk menolak laporan.');
            return;
        }

        $report = Report::findOrFail($id);
        $report->update([
            'status'       => 'rejected',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $notes ?: 'Ditolak oleh reviewer.',
        ]);
        if ($report->reporter_id) {
            Point::create([
                'user_id'     => $report->reporter_id,
                'report_id'   => $report->id,
                'amount'      => -10,
                'type'        => 'rejected',
                'description' => 'Poin dihapus — laporan ditolak ' . $report->code,
            ]);
        }
        session()->flash('success', 'Laporan ' . $report->code . ' telah ditolak.');
    }

    public function export(): StreamedResponse
    {
        $reports = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($reports) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Kode', 'Pelapor', 'Kategori', 'Lokasi', 'Prioritas', 'Tanggal']);
            foreach ($reports as $r) {
                fputcsv($h, [
                    $r->code,
                    $r->reporter?->full_name ?? 'Publik',
                    $r->kategori,
                    $r->lokasi,
                    $r->prioritas_label,
                    $r->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($h);
        }, 'review-laporan-' . now()->format('Ymd') . '.csv');
    }

    private function buildQuery()
    {
        $q = Report::with(['reporter', 'location'])->where('status', 'pending');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // JIKA BUKAN ADMIN (Pimpinan, SPMI, PJ Area), FILTER BERDASARKAN AREA TUGAS
        if ($user && !$user->isAdmin()) {
            // Kita gunakan method getManagedLocationIds yang ada di User.php
            // Jika method ini belum ada di User.php, sistem akan error (kita akan cek setelah ini)
            $managedIds = $user->getManagedLocationIds();
            
            $q->whereIn('location_id', $managedIds);
        }

        // Filter Search (Cari pengusul, lokasi, deskripsi)
        if ($this->search) {
            $q->where(function ($s) {
                $s->where('deskripsi', 'ILIKE', "%{$this->search}%")
                ->orWhereHas('reporter', fn ($r) => $r->where('full_name', 'ILIKE', "%{$this->search}%"))
                ->orWhereHas('location', fn ($l) => $l->where('name', 'ILIKE', "%{$this->search}%"));
            });
        }

        // Filter Kategori & Prioritas
        if ($this->filterKategori)  $q->where('kategori',  $this->filterKategori);
        if ($this->filterPrioritas) $q->where('prioritas', $this->filterPrioritas);

        // Sorting
        $allowed = ['created_at', 'kategori', 'prioritas'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'created_at';
        $q->orderBy($col, $this->sortDir);

        return $q;
    }

    public function render()
    {
        $reports = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.reports.review-reports', compact('reports'))
            ->title('Review Laporan');
    }

    public function viewDetail($id)
    {
        $this->selectedReport = Report::with('user')->find($id);
        $this->showModal = true;
    }
}
