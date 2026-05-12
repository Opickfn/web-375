<?php

namespace App\Livewire\FollowUps;

use App\Models\FollowUp;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageFollowUps extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public string $reportId = '';
    public string $assignedTo = '';
    public string $actionPlan = '';
    public string $targetDate = '';

    // TAMBAHKAN PROPERTI FILTER & SEARCH BERIKUT INI:
    public string $search = '';
    public string $filterStatus = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    public function openForm(): void { $this->showForm = true; }
    public function closeForm(): void { $this->showForm = false; $this->reset(['reportId','assignedTo','actionPlan','targetDate']); }

    public $showModal = false;
    public $selectedReport;

    public function openReportDetail($reportId)
    {
        // Ambil data report berdasarkan ID
        $this->selectedReport = \App\Models\Report::with(['user', 'location'])->find($reportId);
        $this->showModal = true;
    }
    
    public function save()
    {
        // Hanya Admin dan PJ Area yang boleh membuat tindak lanjut
        if (Auth::user()->isPimpinan()) {
            session()->flash('error', 'Pimpinan hanya memiliki akses baca.');
            return;
        }

        $this->validate([
            'reportId' => 'required|exists:reports,id',
            'assignedTo' => 'required|string|max:100',
            'actionPlan' => 'required|string',
            'targetDate' => 'required|date|after_or_equal:today',
        ]);

        FollowUp::create([
            'report_id' => $this->reportId,
            'created_by' => Auth::id(),
            'assigned_to_name' => $this->assignedTo,
            'action_plan' => $this->actionPlan,
            'target_date' => $this->targetDate,
        ]);

        Report::where('id', $this->reportId)->update(['status' => 'in_progress']);

        $this->closeForm();
        session()->flash('success', 'Tindak lanjut berhasil dibuat.');
    }

    public function complete(int $id)
    {
        if (Auth::user()->isPimpinan()) {
        session()->flash('error', 'Akses ditolak.');
        return;
        }
        
        $followUp = FollowUp::findOrFail($id);
        $followUp->update(['status' => 'completed', 'completed_at' => now()]);

        // Check if all follow-ups for report are completed
        $report = $followUp->report;
        $allDone = $report->followUps()->where('status', '!=', 'completed')->count() === 0;
        if ($allDone) {
            $report->update(['status' => 'resolved']);
        }

        session()->flash('success', 'Tindak lanjut ditandai selesai.');
    }

   public function render()
    {
        $followUps = $this->buildFUQuery()->paginate(10);

        // Filter dropdown laporan agar hanya muncul yang sesuai area tugas
        $reportsQuery = Report::whereIn('status', ['approved', 'in_progress']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user && !$user->isAdmin()) {
            $managedIds = $user->getManagedLocationIds();
            $reportsQuery->whereIn('location_id', $managedIds);
        }

        // Gunakan nama 'approvedReports' agar cocok dengan @foreach di Blade Anda
        $approvedReports = $reportsQuery->latest()->get();

        return view('livewire.follow-ups.manage-follow-ups', [
            'followUps' => $followUps,
            'approvedReports' => $approvedReports 
        ])->title('Tindak Lanjut');
    }

    // ==========================================
    // ManageFollowUps.php additions
    // ==========================================

    // New properties:
    // public string $search = '';
    // public string $filterStatus = '';
    // public string $sortBy  = 'created_at';
    // public string $sortDir = 'desc';
    // public int    $perPage = 15;

    public function sortFU(string $col): void
    {
        $this->sortDir = ($this->sortBy === $col && $this->sortDir === 'desc') ? 'asc' : 'desc';
        $this->sortBy = $col; $this->resetPage();
    }

    public function exportFU(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $fus = $this->buildFUQuery()->get();
        return response()->streamDownload(function () use ($fus) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Laporan', 'Ditugaskan', 'Rencana', 'Target', 'Status', 'Selesai']);
            foreach ($fus as $f) {
                fputcsv($h, [$f->report->code, $f->assigned_to_name, $f->action_plan, $f->target_date->format('d/m/Y'), $f->status, $f->completed_at?->format('d/m/Y') ?? '—']);
            }
            fclose($h);
        }, 'tindak-lanjut-' . now()->format('Ymd') . '.csv');
    }

    private function buildFUQuery()
    {
        // Ambil query dasar dengan relasi report
        $q = \App\Models\FollowUp::with(['report', 'creator']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // FILTER BERDASARKAN TAG AREA TUGAS
        if ($user && !$user->isAdmin()) {
            $managedIds = $user->getManagedLocationIds();
            
            // Kita filter follow_up yang laporannya berada di lokasi tugas user
            $q->whereHas('report', function($query) use ($managedIds) {
                $query->whereIn('location_id', $managedIds);
            });
        }

        // Filter Search
        if ($this->search) {
            $q->where(fn($s) => $s
                ->where('assigned_to_name', 'ILIKE', "%{$this->search}%")
                ->orWhereHas('report', fn($r) => $r->where('lokasi', 'ILIKE', "%{$this->search}%"))
            );
        }

        // Filter Status
        if ($this->filterStatus) {
            $q->where('status', $this->filterStatus);
        }

        // Sorting
        $allowed = ['created_at', 'target_date', 'status'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'created_at';
        $q->orderBy($col, $this->sortDir);

        return $q;

        
    }
    // In render() replace: $followUps = FollowUp::with(['report','creator'])->latest()->paginate(10);
    // with: $followUps = $this->buildFUQuery()->paginate($this->perPage);
}

