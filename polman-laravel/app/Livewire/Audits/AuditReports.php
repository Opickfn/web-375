<?php

namespace App\Livewire\Audits;

use App\Models\AuditReport;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AuditReports extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;
    public string $auditTarget = 'pj_area';
    public string $pjAreaId = '';
    public string $branchId = '';
    public string $floorId = '';
    public string $spaceId = '';
    public string $title = '';
    public string $findings = '';
    public string $recommendations = '';
    public string $auditDate = '';
    public $pdfFile = null;
    public ?int $editId = null;

    public function openForm(): void
    {
        $this->resetFormState();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $audit = AuditReport::with('location.parent.parent')->findOrFail($id);

        $this->resetFormState();
        $this->showForm = true;
        $this->auditTarget = 'pj_area';
        $this->editId = $audit->id;
        $this->pjAreaId = (string) $audit->pj_area_id;
        $this->title = $audit->title;
        $this->findings = $audit->findings;
        $this->recommendations = $audit->recommendations ?? '';
        $this->auditDate = $audit->audit_date?->format('Y-m-d') ?? '';
        $this->pdfFile = null;

        $location = $audit->location;
        if ($location) {
            if (in_array($location->type, ['ruangan', 'area'], true)) {
                $floor = $location->parent;
                $branch = $floor?->parent;
                $this->spaceId = (string) $location->id;
                $this->floorId = (string) ($floor->id ?? '');
                $this->branchId = (string) ($branch->id ?? '');
            } elseif ($location->type === 'lantai') {
                $this->branchId = (string) $location->id;
            } else {
                $this->branchId = (string) $location->id;
            }
        }
    }

    public function updatedAuditTarget(): void
    {
        $this->pjAreaId = '';
        $this->branchId = '';
        $this->floorId = '';
        $this->spaceId = '';
    }

    public function updatedPjAreaId(): void
    {
        $this->branchId = '';
        $this->floorId = '';
        $this->spaceId = '';
    }

    public function updatedBranchId(): void
    {
        $this->floorId = '';
        $this->spaceId = '';
    }

    public function updatedFloorId(): void
    {
        $this->spaceId = '';
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetFormState();
    }

    private function resetFormState(): void
    {
        $this->auditTarget = 'pj_area';
        $this->pjAreaId = '';
        $this->branchId = '';
        $this->floorId = '';
        $this->spaceId = '';
        $this->title = '';
        $this->findings = '';
        $this->recommendations = '';
        $this->auditDate = '';
        $this->pdfFile = null;
        $this->editId = null;
    }

    public function save(): void
    {
        $rules = [
            'auditTarget' => 'required|in:pj_area,lokasi',
            'branchId' => 'required|exists:locations,id',
            'title' => 'required|string|max:150',
            'findings' => 'required|string',
            'recommendations' => 'nullable|string',
            'auditDate' => 'required|date|before_or_equal:today',
            'pdfFile' => 'nullable|mimes:pdf|max:10240',
        ];

        if ($this->auditTarget === 'pj_area') {
            $rules['pjAreaId'] = 'required|exists:users,id';
        }

        $this->validate($rules);

        if ($this->branchType === 'gedung') {
            $this->validate([
                'floorId' => 'required|exists:locations,id',
                'spaceId' => 'required|exists:locations,id',
            ]);
        }

        if ($this->branchType === 'lantai') {
            $this->validate([
                'spaceId' => 'required|exists:locations,id',
            ]);
        }

        $locationId = $this->branchId;
        if ($this->branchType === 'gedung' || $this->branchType === 'lantai') {
            $locationId = $this->spaceId;
        }

        if ($this->auditTarget === 'lokasi') {
            $pjAreaId = $this->findPjAreaForLocation($locationId);
            if (! $pjAreaId) {
                $this->addError('branchId', 'Lokasi yang dipilih belum memiliki PJ Area terkait.');
                return;
            }
        } else {
            $pjAreaId = $this->pjAreaId;
        }

        $data = [
            'auditor_id' => Auth::id(),
            'pj_area_id' => $pjAreaId,
            'location_id' => $locationId,
            'title' => $this->title,
            'findings' => $this->findings,
            'recommendations' => $this->recommendations,
            'audit_date' => $this->auditDate,
        ];

        if ($this->pdfFile) {
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $this->pdfFile->getClientOriginalName());
            $this->pdfFile->storeAs('audits', $filename, 'public');
            $data['pdf_path'] = $filename;
        }

        if ($this->editId) {
            $audit = AuditReport::findOrFail($this->editId);

            if ($this->pdfFile && $audit->pdf_path && Storage::disk('public')->exists('audits/' . $audit->pdf_path)) {
                Storage::disk('public')->delete('audits/' . $audit->pdf_path);
            }

            $audit->update($data);
            session()->flash('success', 'Laporan audit berhasil diperbarui.');
        } else {
            AuditReport::create($data);
            session()->flash('success', 'Laporan audit berhasil disimpan.');
        }

        $this->closeForm();
    }

    public function deleteReport(int $id): void
    {
        $audit = AuditReport::findOrFail($id);

        if ($audit->pdf_path && Storage::disk('public')->exists('audits/' . $audit->pdf_path)) {
            Storage::disk('public')->delete('audits/' . $audit->pdf_path);
        }

        $audit->delete();

        if ($this->editId === $id) {
            $this->closeForm();
        }

        session()->flash('success', 'Laporan audit berhasil dihapus.');
    }

    public function getAssignedLocationsProperty()
    {
        if ($this->auditTarget === 'pj_area') {
            if (! $this->pjAreaId) {
                return collect();
            }

            $pjArea = User::find($this->pjAreaId);
            if (! $pjArea) {
                return collect();
            }

            $assigned = $pjArea->assignedLocations()
                ->active()
                ->orderBy('name')
                ->get();

            if ($assigned->isNotEmpty()) {
                return $assigned;
            }

            return Location::whereIn('type', ['gedung', 'infrastruktur'])
                ->active()
                ->orderBy('name')
                ->get();
        }

        $assigned = Auth::user()->assignedLocations()
            ->active()
            ->orderBy('name')
            ->get();

        if ($assigned->isNotEmpty()) {
            return $assigned;
        }

        return Location::whereIn('type', ['gedung', 'infrastruktur'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getFloorsProperty()
    {
        if (! $this->branchId || $this->branchType !== 'gedung') {
            return collect();
        }

        return Location::where('parent_id', $this->branchId)
            ->where('type', 'lantai')
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getSpacesProperty()
    {
        if ($this->branchType === 'gedung') {
            if (! $this->floorId) {
                return collect();
            }

            return Location::where('parent_id', $this->floorId)
                ->whereIn('type', ['ruangan', 'area'])
                ->active()
                ->orderBy('name')
                ->get();
        }

        if ($this->branchType === 'lantai') {
            return Location::where('parent_id', $this->branchId)
                ->whereIn('type', ['ruangan', 'area'])
                ->active()
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    public function getBranchTypeProperty(): ?string
    {
        if (! $this->branchId) {
            return null;
        }

        return Location::find($this->branchId)?->type;
    }

    private function findPjAreaForLocation(string $locationId): ?int
    {
        $location = Location::find($locationId);
        if (! $location) {
            return null;
        }

        $locationIds = [$location->id];
        $parent = $location->parent;

        while ($parent) {
            $locationIds[] = $parent->id;
            $parent = $parent->parent;
        }

        return User::where('role', 'pj_area')
            ->whereHas('assignedLocations', function ($query) use ($locationIds) {
                $query->whereIn('locations.id', $locationIds);
            })
            ->value('id');
    }

    private function getUserAuditLocationIds(): array
    {
        $assignedIds = Auth::user()->assignedLocations()->select('locations.id')->pluck('id')->all();
        if (empty($assignedIds)) {
            return [];
        }

        $allIds = $assignedIds;
        $currentIds = $assignedIds;

        while (! empty($currentIds)) {
            $childIds = Location::whereIn('parent_id', $currentIds)->pluck('id')->all();
            $currentIds = array_diff($childIds, $allIds);
            if (empty($currentIds)) {
                break;
            }

            $allIds = array_merge($allIds, $currentIds);
        }

        return array_values($allIds);
    }

    public function render()
    {
        $pjAreas = User::where('role', 'pj_area')->orderBy('full_name')->get();

        $query = AuditReport::with(['auditor', 'pjArea', 'location'])->latest();

        if (Auth::user()->isPjArea() || Auth::user()->isPimpinan()) {
            $locationIds = $this->getUserAuditLocationIds();

            $query->where(function ($subQuery) use ($locationIds) {
                if (! empty($locationIds)) {
                    $subQuery->whereIn('location_id', $locationIds);
                }

                if (Auth::user()->isPjArea()) {
                    $subQuery->orWhere('pj_area_id', Auth::id());
                }
            });
        }

        $auditReports = $query->paginate(10);

        return view('livewire.audits.audit-reports', compact('pjAreas', 'auditReports'))
            ->layout('layouts.app')
            ->title('Audit PJ Area');
    }
}
