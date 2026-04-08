<?php

namespace App\Livewire\FollowUps;

use App\Models\FollowUp;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

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
            ->layout('layouts.app')
            ->title('Tindak Lanjut');
    }
}
