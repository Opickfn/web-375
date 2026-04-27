<?php

namespace App\Livewire\Admin;

use App\Models\Warning;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class ManageWarnings extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public bool    $showForm        = false;
    public ?int    $editingId       = null;
    public string  $formTitle       = '';
    public string  $formDescription = '';
    public string  $formSeverity    = 'medium';
    public string  $formStatus      = 'active';
    public bool    $formIsPublic    = true;
    public ?string $formExpiresAt   = null;
    public ?int    $formReportId    = null;
    public $formImage;
    public string $image_source = 'manual';
    public bool $formIsDefault = false;

    // Filters + sort + pagination
    public string $search         = '';
    public string $filterSeverity = 'all';
    public string $filterStatus   = 'all';
    public string $sortBy         = 'created_at';
    public string $sortDir        = 'desc';
    public int    $perPage        = 15;

    // Feedback
    public ?string $message     = null;
    public ?string $messageType = null;

    public function updatingSearch()         { $this->resetPage(); }
    public function updatingFilterSeverity() { $this->resetPage(); }
    public function updatingFilterStatus()   { $this->resetPage(); }

    // ── Sort ──────────────────────────────────────────────
    public function sort(string $column): void
    {
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'desc') ? 'asc' : 'desc';
        $this->sortBy  = $column;
        $this->resetPage();
    }

    // ── Export ────────────────────────────────────────────
    public function exportWarnings(): StreamedResponse
    {
        $data = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($data) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['ID', 'Judul', 'Deskripsi', 'Level', 'Status', 'Publik', 'Berlaku Sampai', 'Laporan', 'Dibuat']);
            foreach ($data as $w) {
                fputcsv($h, [
                    $w->id,
                    $w->title,
                    $w->description,
                    $w->severity_label,
                    $w->status_label,
                    $w->is_public ? 'Ya' : 'Tidak',
                    $w->expires_at?->format('d/m/Y') ?? '—',
                    $w->report?->code ?? '—',
                    $w->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($h);
        }, 'peringatan-' . now()->format('Ymd') . '.csv');
    }

    // ── Form Methods ──────────────────────────────────────
    public function openForm(?int $id = null): void
    {
        if ($id) {
            $w = Warning::findOrFail($id);
            $this->editingId       = $w->id;
            $this->formTitle       = $w->title;
            $this->formDescription = $w->description;
            $this->formSeverity    = $w->severity;
            $this->formStatus      = $w->status;
            $this->formIsPublic    = $w->is_public;
            $this->formExpiresAt   = $w->expires_at?->format('Y-m-d');
            $this->formReportId    = $w->report_id;
            $this->image_source    = $w->image_source ?? 'manual';

            // Note: For simplicity, we're not pre-filling the file input with the existing image.
            $this->formIsDefault   = (bool) $w->is_default;
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->formTitle = $this->formDescription = '';
        $this->formSeverity  = 'medium';
        $this->formStatus    = 'active';
        $this->formIsPublic  = true;
        $this->formExpiresAt = null;
        $this->formReportId  = null;
        $this->image_source = 'manual';
        $this->formImage = null;

        // Default to false when creating a new warning, but keep existing value when editing
        $this->formIsDefault = false;

        $this->resetValidation();
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate([
            'formTitle'       => 'required|string|max:255',
            'formDescription' => 'required|string',
            'formSeverity'    => 'required|in:low,medium,high',
            'formStatus'      => 'required|in:active,inactive,expired',
            'formIsPublic'    => 'boolean',
            'formExpiresAt'   => 'nullable|date|date_format:Y-m-d',
            'formReportId'    => 'nullable|exists:reports,id|required_if:image_source,report',
            'image_source'    => 'required|in:manual,report',
            'formImage'       => 'nullable|image|max:2048', // 2MB
        ]);

        $imagePath = null;
        if ($this->image_source === 'manual' && $this->formImage) {
            $imagePath = $this->formImage->store('warnings', 'public');
        }

        $isEdit = (bool) $this->editingId;

        Warning::updateOrCreate(
            ['id' => $this->editingId],
            [
                'created_by'  => Auth::id(),
                'title'       => $this->formTitle,
                'description' => $this->formDescription,
                'severity'    => $this->formSeverity,
                'status'      => $this->formStatus,
                'is_public'   => $this->formIsPublic,
                'expires_at'  => $this->formExpiresAt ? now()->parse($this->formExpiresAt) : null,
                'report_id'   => $this->formReportId,
                'image_source' => $this->image_source,
                'image_path' => $imagePath,

                // If this warning is marked as default, we need to unset the default flag from all other warnings
                'is_default' => $this->formIsDefault,
            ]
        );

        $this->closeForm();
        $this->message     = $isEdit ? 'Peringatan berhasil diperbarui.' : 'Peringatan berhasil ditambahkan.';
        $this->messageType = 'success';
    }

    public function deleteWarning(int $id): void
    {
        try {
            Warning::findOrFail($id)->delete();
            $this->message     = 'Peringatan berhasil dihapus.';
            $this->messageType = 'success';
        } catch (\Exception $e) {
            $this->message     = 'Terjadi kesalahan: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function toggleStatus(int $id): void
    {
        $w = Warning::findOrFail($id);
        $w->update(['status' => $w->status === 'active' ? 'inactive' : 'active']);
        $this->message     = 'Status peringatan diperbarui.';
        $this->messageType = 'success';
    }

    // ── Query Builder ─────────────────────────────────────
    private function buildQuery()
    {
        $q = Warning::with(['creator', 'report']);

        if ($this->search) {
            $q->where(fn ($s) => $s
                ->where('title',       'ILIKE', "%{$this->search}%")
                ->orWhere('description', 'ILIKE', "%{$this->search}%")
            );
        }

        if ($this->filterSeverity !== 'all') $q->where('severity', $this->filterSeverity);
        if ($this->filterStatus   !== 'all') $q->where('status',   $this->filterStatus);

        $allowed = ['title', 'severity', 'status', 'expires_at', 'created_at'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'created_at';
        $q->orderBy($col, $this->sortDir);

        return $q;
    }

    public function render()
    {
        $warnings        = $this->buildQuery()->paginate($this->perPage);
        $approvedReports = Report::whereIn('status', ['approved', 'in_progress'])
                                 ->orderByDesc('created_at')->limit(30)->get();

        return view('livewire.admin.manage-warnings', compact('warnings', 'approvedReports'))
            ->title('Kelola Peringatan');
    }
}
