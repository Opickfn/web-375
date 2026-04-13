<?php

namespace App\Livewire\Reports;

use App\Models\Point;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewReports extends Component
{
    use WithPagination;

    public string $filterKategori = '';

    public function approve(int $reportId, string $notes = '')
    {
        $report = Report::findOrFail($reportId);
        $report->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);

        // Bonus points for reporter
        Point::create([
            'user_id' => $report->reporter_id,
            'report_id' => $report->id,
            'amount' => 5,
            'type' => 'approved',
            'description' => 'Bonus laporan disetujui ' . $report->code,
        ]);

        session()->flash('success', 'Laporan ' . $report->code . ' telah disetujui.');
    }

    public function reject(int $reportId, string $notes = '')
    {
        $report = Report::findOrFail($reportId);
        $report->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $notes ?: 'Ditolak oleh reviewer.',
        ]);

        // Remove points from submission by creating negative points entry
        Point::create([
            'user_id' => $report->reporter_id,
            'report_id' => $report->id,
            'amount' => -10,
            'type' => 'rejected',
            'description' => 'Poin dihapus - laporan ditolak ' . $report->code,
        ]);

        session()->flash('success', 'Laporan ' . $report->code . ' telah ditolak. Poin submission dihapus.');
    }

    public function render()
    {
        $query = Report::with('reporter')->where('status', 'pending');

        if ($this->filterKategori) {
            $query->byKategori($this->filterKategori);
        }

        $reports = $query->latest()->paginate(10);

        return view('livewire.reports.review-reports', compact('reports'))
            ->layout('layouts.app')
            ->title('Review Laporan');
    }
}
