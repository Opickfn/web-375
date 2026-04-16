<?php

namespace App\Livewire\Admin;

use App\Models\Warning;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ManageWarnings extends Component
{
    use WithPagination;

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $formTitle = '';
    public string $formDescription = '';
    public string $formSeverity = 'medium';
    public string $formStatus = 'active';
    public bool $formIsPublic = true;
    public ?string $formExpiresAt = null;
    public ?int $formReportId = null;

    // Filters
    public string $search = '';
    public string $filterSeverity = 'all';
    public string $filterStatus = 'all';

    // Message handling
    public ?string $message = null;
    public ?string $messageType = null;

    // ─── Form Methods ─────────────────────────────────

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $warning = Warning::findOrFail($id);
            $this->editingId = $warning->id;
            $this->formTitle = $warning->title;
            $this->formDescription = $warning->description;
            $this->formSeverity = $warning->severity;
            $this->formStatus = $warning->status;
            $this->formIsPublic = $warning->is_public;
            $this->formExpiresAt = $warning->expires_at?->format('Y-m-d');
            $this->formReportId = $warning->report_id;
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->formTitle = '';
        $this->formDescription = '';
        $this->formSeverity = 'medium';
        $this->formStatus = 'active';
        $this->formIsPublic = true;
        $this->formExpiresAt = null;
        $this->formReportId = null;
        $this->resetValidation();
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'formTitle' => 'required|string|max:255',
            'formDescription' => 'required|string',
            'formSeverity' => 'required|in:low,medium,high',
            'formStatus' => 'required|in:active,inactive,expired',
            'formIsPublic' => 'boolean',
            'formExpiresAt' => 'nullable|date|date_format:Y-m-d',
            'formReportId' => 'nullable|exists:reports,id',
        ]);

        $isEdit = (bool) $this->editingId;

        Warning::updateOrCreate(
            ['id' => $this->editingId],
            [
                'created_by' => Auth::id(),
                'title' => $this->formTitle,
                'description' => $this->formDescription,
                'severity' => $this->formSeverity,
                'status' => $this->formStatus,
                'is_public' => $this->formIsPublic,
                'expires_at' => $this->formExpiresAt ? now()->parse($this->formExpiresAt) : null,
                'report_id' => $this->formReportId,
            ]
        );

        $this->closeForm();
        $this->message = $isEdit ? 'Peringatan diperbarui.' : 'Peringatan ditambahkan.';
        $this->messageType = 'success';
    }

    public function deleteWarning(int $id): void
    {
        try {
            Warning::findOrFail($id)->delete();
            $this->message = 'Peringatan berhasil dihapus.';
            $this->messageType = 'success';
        } catch (\Exception $e) {
            $this->message = 'Terjadi kesalahan: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function toggleStatus(int $id): void
    {
        $warning = Warning::findOrFail($id);
        $newStatus = $warning->status === 'active' ? 'inactive' : 'active';
        $warning->update(['status' => $newStatus]);
        $this->message = 'Status peringatan diperbarui.';
        $this->messageType = 'success';
    }

    // ─── Render ───────────────────────────────────────

    public function render()
    {
        $query = Warning::query();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'ILIKE', "%{$this->search}%")
                  ->orWhere('description', 'ILIKE', "%{$this->search}%");
            });
        }

        // Filters
        if ($this->filterSeverity !== 'all') {
            $query->where('severity', $this->filterSeverity);
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $warnings = $query->with(['creator', 'report'])
                         ->orderByDesc('created_at')
                         ->paginate(15);

        $approvedReports = Report::where('status', 'approved')
                                 ->orderByDesc('created_at')
                                 ->limit(20)
                                 ->get();

        return view('livewire.admin.manage-warnings', compact('warnings', 'approvedReports'))
            ->layout('layouts.app')
            ->title('Kelola Peringatan');
    }
}
