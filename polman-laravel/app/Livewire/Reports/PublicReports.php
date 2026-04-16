<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class PublicReports extends Component
{
    use WithPagination;

    public string $filterStatus = 'approved';
    public string $filterKategori = '';

    public function render()
    {
        $query = Report::with('reporter', 'gedung')
            ->where('status', $this->filterStatus);

        if ($this->filterKategori) {
            $query->byKategori($this->filterKategori);
        }

        $reports = $query->latest()->paginate(15);

        $totalReports = Report::count();
        $approvedReports = Report::where('status', 'approved')->count();
        $pendingReports = Report::where('status', 'pending')->count();
        $rejectedReports = Report::where('status', 'rejected')->count();

        return view('livewire.reports.public-reports', compact('reports', 'totalReports', 'approvedReports', 'pendingReports', 'rejectedReports'))
            ->layout('layouts.guest')
            ->title('Laporan Publik');
    }
}
