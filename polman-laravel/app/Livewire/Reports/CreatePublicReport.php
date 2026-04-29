<?php

namespace App\Livewire\Reports;

use App\Models\Location;
use App\Models\Report;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePublicReport extends Component
{
    use WithFileUploads;

    public string $kategori = '';
    public string $campus_id = '';
    public string $branch_id = '';
    public string $floor_id = '';
    public string $space_id = '';
    public string $detail_lokasi = '';
    public string $deskripsi = '';
    public string $prioritas = 'sedang';
    public string $solusi = '';
    public $bukti;

    // Success state
    public bool $showSuccess = false;
    public ?Report $successReport = null;

    protected function messages(): array
    {
        return [
            'kategori.required' => 'Pilih kategori pelanggaran.',
            'campus_id.required' => 'Pilih kampus.',
            'branch_id.required' => 'Pilih gedung atau infrastruktur.',
            'floor_id.required' => 'Pilih lantai.',
            'space_id.required' => 'Pilih ruangan atau area.',
            'detail_lokasi.required' => 'Detail lokasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'bukti.image' => 'File harus berupa gambar.',
            'bukti.max' => 'Ukuran file maksimal 5MB.',
        ];
    }

    public function updatedCampusId(): void
    {
        $this->branch_id = '';
        $this->floor_id = '';
        $this->space_id = '';
    }

    public function updatedBranchId(): void
    {
        $this->floor_id = '';
        $this->space_id = '';
    }

    public function updatedFloorId(): void
    {
        $this->space_id = '';
    }

    public function getCampusesProperty()
    {
        return Location::type('campus')->active()->orderBy('name')->get();
    }

    public function getBranchesProperty()
    {
        if (! $this->campus_id) {
            return collect();
        }

        return Location::where('parent_id', $this->campus_id)
            ->whereIn('type', ['gedung', 'infrastruktur'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getFloorsProperty()
    {
        if (! $this->branch_id || $this->branchType !== 'gedung') {
            return collect();
        }

        return Location::where('parent_id', $this->branch_id)
            ->where('type', 'lantai')
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getSpacesProperty()
    {
        if (! $this->floor_id) {
            return collect();
        }

        return Location::where('parent_id', $this->floor_id)
            ->whereIn('type', ['ruangan', 'area'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getBranchTypeProperty(): ?string
    {
        if (! $this->branch_id) {
            return null;
        }

        return Location::find($this->branch_id)?->type;
    }

    public function submit()
    {
        $rules = [
            'kategori' => 'required|in:5R,7S,K3',
            'campus_id' => 'required|exists:locations,id',
            'branch_id' => 'required|exists:locations,id',
            'detail_lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'bukti' => 'nullable|image|max:5120',
            'solusi' => 'nullable|string|max:255',
        ];

        if ($this->branchType === 'gedung') {
            $rules['floor_id'] = 'required|exists:locations,id';
            $rules['space_id'] = 'required|exists:locations,id';
        }

        $this->validate($rules, $this->messages());

        if ($this->branchType === 'gedung') {
            $location = Location::findOrFail($this->space_id);
            $gedungId = $this->branch_id;
        } else {
            $location = Location::findOrFail($this->branch_id);
            $gedungId = null;
        }

        $lokasi = $location->full_path;
        if ($this->detail_lokasi) {
            $lokasi .= ' - ' . $this->detail_lokasi;
        }

        $data = [
            'reporter_id' => null,
            'kategori' => $this->kategori,
            'lokasi' => $lokasi,
            'deskripsi' => $this->deskripsi,
            'prioritas' => $this->prioritas,
            'status' => 'pending',
            'gedung_id' => $gedungId,
            'location_id' => $location->id,
            'solusi' => $this->solusi,
        ];

        if ($this->bukti) {
            $filename = time() . '_' . $this->bukti->getClientOriginalName();
            $this->bukti->storeAs('uploads', $filename, 'public');
            $data['bukti'] = $filename;
        }

        $report = Report::create($data);
        $this->successReport = $report;
        $this->showSuccess = true;

        $this->kategori = '';
        $this->campus_id = '';
        $this->branch_id = '';
        $this->floor_id = '';
        $this->space_id = '';
        $this->detail_lokasi = '';
        $this->deskripsi = '';
        $this->prioritas = 'sedang';
        $this->bukti = null;
        $this->solusi = '';
    }

    public function resetForm()
    {
        $this->showSuccess = false;
        $this->successReport = null;
        $this->kategori = '';
        $this->campus_id = '';
        $this->branch_id = '';
        $this->floor_id = '';
        $this->space_id = '';
        $this->detail_lokasi = '';
        $this->deskripsi = '';
        $this->prioritas = 'sedang';
        $this->bukti = null;
    }

    public function render()
    {
        return view('livewire.reports.create-public-report', [
            'campuses' => $this->campuses,
            'branches' => $this->branches,
            'floors' => $this->floors,
            'spaces' => $this->spaces,
            'branchType' => $this->branchType,
        ])
            ->layout('layouts.guest')
            ->title('Buat Laporan');
    }
}
