<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Models\Report;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageGedungRuangan extends Component
{
    public bool $showForm = false;
    public ?int $editLocationId = null;
    public string $lParentId = '';
    public string $lType = '';
    public string $lCode = '';
    public string $lName = '';
    public string $lCategory = '';

    public ?string $message = null;
    public ?string $messageType = null;

    public ?int $activeAddParentId = null;
    public string $activeAddType = '';
    public string $addName = '';
    public string $addCode = '';

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $location = Location::findOrFail($id);
            $this->editLocationId = $location->id;
            $this->lParentId = (string) $location->parent_id;
            $this->lType = $location->type;
            $this->lCode = $location->code ?? '';
            $this->lName = $location->name;
            $this->lCategory = $location->category ?? '';
        } else {
            $this->resetForm();
            $this->lType = 'campus';
            $this->lCategory = 'Kampus';
        }

        $this->showForm = true;
    }

    public function openChildForm(int $parentId, string $type = ''): void
    {
        $this->showForm = false;
        $this->activeAddParentId = $parentId;
        $this->activeAddType = $type;
        $this->addName = '';
        $this->addCode = '';
    }

    public function cancelAddChild(): void
    {
        $this->activeAddParentId = null;
        $this->activeAddType = '';
        $this->addName = '';
        $this->addCode = '';
    }

    public function submitAddChild(): void
    {
        $rules = [
            'addName' => ['required', 'string', 'max:150'],
            'addCode' => ['nullable', 'string', 'max:20'],
            'activeAddType' => ['required', Rule::in(['gedung', 'infrastruktur', 'lantai', 'ruangan', 'area'])],
        ];

        if (! $this->activeAddParentId) {
            $this->message = 'Induk lokasi tidak valid.';
            $this->messageType = 'error';
            return;
        }

        $this->validate($rules);

        $parent = Location::find($this->activeAddParentId);
        if (! $parent) {
            $this->message = 'Induk lokasi tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        if (in_array($this->activeAddType, ['gedung', 'infrastruktur'], true) && $parent->type !== 'campus') {
            $this->message = 'Induk lokasi untuk Gedung/Infrastruktur harus berupa Kampus.';
            $this->messageType = 'error';
            return;
        }

        if ($this->activeAddType === 'lantai' && $parent->type !== 'gedung') {
            $this->message = 'Induk lokasi untuk Lantai harus berupa Gedung.';
            $this->messageType = 'error';
            return;
        }

        if (in_array($this->activeAddType, ['ruangan', 'area'], true) && $parent->type !== 'lantai') {
            $this->message = 'Induk lokasi untuk Ruangan/Area harus berupa Lantai.';
            $this->messageType = 'error';
            return;
        }

        $category = match ($this->activeAddType) {
            'gedung' => 'Gedung',
            'infrastruktur' => 'Infrastruktur Umum',
            'ruangan' => 'Ruangan',
            'area' => 'Area Lainnya',
            default => null,
        };

        Location::create([
            'parent_id' => $parent->id,
            'code' => $this->addCode ? strtoupper($this->addCode) : null,
            'name' => $this->addName,
            'type' => $this->activeAddType,
            'category' => $category,
            'is_active' => true,
        ]);

        $this->message = 'Sub-lokasi berhasil ditambahkan.';
        $this->messageType = 'success';
        $this->cancelAddChild();
    }

    public function resetForm(): void
    {
        $this->editLocationId = null;
        $this->lParentId = '';
        $this->lType = 'campus';
        $this->lCode = '';
        $this->lName = '';
        $this->lCategory = 'Kampus';
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function updatedLType(): void
    {
        $this->lParentId = '';
    }

    public function saveLocation(): void
    {
        $rules = [
            'lType' => ['required', Rule::in(['campus', 'gedung', 'infrastruktur', 'lantai', 'ruangan', 'area'])],
            'lName' => ['required', 'string', 'max:150'],
            'lCode' => ['nullable', 'string', 'max:20'],
            'lParentId' => $this->lType === 'campus' ? 'nullable' : ['required', 'exists:locations,id'],
        ];

        $this->validate($rules);

        if ($this->lType === 'gedung' || $this->lType === 'infrastruktur') {
            $parent = Location::find($this->lParentId);
            if (! $parent || $parent->type !== 'campus') {
                $this->message = 'Induk lokasi untuk Gedung/Infrastruktur harus berupa Kampus.';
                $this->messageType = 'error';
                return;
            }
        }

        if ($this->lType === 'lantai') {
            $parent = Location::find($this->lParentId);
            if (! $parent || $parent->type !== 'gedung') {
                $this->message = 'Induk lokasi untuk Lantai harus berupa Gedung.';
                $this->messageType = 'error';
                return;
            }
        }

        if (in_array($this->lType, ['ruangan', 'area'], true)) {
            $parent = Location::find($this->lParentId);
            if (! $parent || $parent->type !== 'lantai') {
                $this->message = 'Induk lokasi untuk Ruangan/Area harus berupa Lantai.';
                $this->messageType = 'error';
                return;
            }
        }

        $category = match ($this->lType) {
            'gedung' => 'Gedung',
            'infrastruktur' => 'Infrastruktur Umum',
            'ruangan' => 'Ruangan',
            'area' => 'Area Lainnya',
            default => null,
        };

        $isEdit = (bool) $this->editLocationId;

        Location::updateOrCreate(
            ['id' => $this->editLocationId],
            [
                'parent_id' => $this->lParentId ?: null,
                'code' => $this->lCode ? strtoupper($this->lCode) : null,
                'name' => $this->lName,
                'type' => $this->lType,
                'category' => $category,
            ]
        );

        $this->closeForm();
        $this->message = $isEdit ? 'Lokasi diperbarui.' : 'Lokasi ditambahkan.';
        $this->messageType = 'success';
    }

    public function toggleLocation(int $id): void
    {
        $location = Location::findOrFail($id);
        $location->update(['is_active' => ! $location->is_active]);
    }

    protected function gatherDescendantIds(Location $location): array
    {
        $ids = [];

        foreach ($location->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->gatherDescendantIds($child));
        }

        return $ids;
    }

    public function deleteLocation(int $id): void
    {
        $location = Location::with('children')->findOrFail($id);
        $locationIds = array_merge([$location->id], $this->gatherDescendantIds($location));

        if (Report::whereIn('location_id', $locationIds)->count() > 0) {
            $this->message = 'Tidak dapat menghapus lokasi yang atau sub-lokasinya sudah digunakan dalam laporan. Hapus atau edit laporan terlebih dahulu.';
            $this->messageType = 'error';
            return;
        }

        try {
            Location::destroy($locationIds);
            $this->message = 'Lokasi dan seluruh sub-lokasinya berhasil dihapus.';
            $this->messageType = 'success';
        } catch (\Exception $e) {
            $this->message = 'Terjadi kesalahan: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function getParentOptionsProperty()
    {
        return match ($this->lType) {
            'gedung', 'infrastruktur' => Location::type('campus')->active()->orderBy('name')->get(),
            'lantai' => Location::type('gedung')->active()->orderBy('name')->get(),
            'ruangan', 'area' => Location::type('lantai')->active()->orderBy('name')->get(),
            default => collect(),
        };
    }

    public function getLocationsProperty()
    {
        return Location::with(['children.children.children'])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.manage-gedung-ruangan', [
            'locations' => $this->locations,
            'parentOptions' => $this->parentOptions,
        ])
            ->layout('layouts.app')
            ->title('Kelola Lokasi');
    }
}
