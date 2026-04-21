<?php

namespace App\Livewire\FollowUps;

use App\Models\FollowUp;
use App\Models\Report;
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

    public function openForm(): void { $this->showForm = true; }
    public function closeForm(): void { $this->showForm = false; $this->reset(['reportId','assignedTo','actionPlan','targetDate']); }

    public function save()
    {
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
        $followUps = FollowUp::with(['report', 'creator'])->latest()->paginate(10);
        $approvedReports = Report::whereIn('status', ['approved', 'in_progress'])->get();

        return view('livewire.follow-ups.manage-follow-ups', compact('followUps', 'approvedReports'))
            ->title('Tindak Lanjut');
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
        $q = \App\Models\FollowUp::with(['report','creator']);
        if ($this->search) $q->where(fn($s) => $s->where('assigned_to_name','ILIKE',"%{$this->search}%")->orWhereHas('report',fn($r)=>$r->where('lokasi','ILIKE',"%{$this->search}%")));
        if ($this->filterStatus) $q->where('status', $this->filterStatus);
        $allowed = ['created_at','target_date','status'];
        $q->orderBy(in_array($this->sortBy,$allowed)?$this->sortBy:'created_at', $this->sortDir);
        return $q;
    }
    // In render() replace: $followUps = FollowUp::with(['report','creator'])->latest()->paginate(10);
    // with: $followUps = $this->buildFUQuery()->paginate($this->perPage);
}

