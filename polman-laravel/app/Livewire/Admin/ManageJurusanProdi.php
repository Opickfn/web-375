<?php

namespace App\Livewire\Admin;

use App\Models\Jurusan;
use App\Models\ProgramStudi;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageJurusanProdi extends Component
{
    // Jurusan form
    public bool $showJurusanForm = false;
    public ?int $editJurusanId = null;
    public string $jKode = '';
    public string $jNama = '';
    public string $jNamaEn = '';

    // Prodi form
    public bool $showProdiForm = false;
    public ?int $editProdiId = null;
    public string $pJurusanId = '';
    public string $pKode = '';
    public string $pNama = '';
    public string $pJenjang = 'D3';

    // ─── Jurusan ─────────────────────────────────────

    public function openJurusanForm(?int $id = null): void
    {
        if ($id) {
            $j = Jurusan::findOrFail($id);
            $this->editJurusanId = $j->id;
            $this->jKode = $j->kode;
            $this->jNama = $j->nama;
            $this->jNamaEn = $j->nama_en ?? '';
        } else {
            $this->resetJurusanForm();
        }
        $this->showJurusanForm = true;
    }

    public function resetJurusanForm(): void
    {
        $this->editJurusanId = null;
        $this->jKode = '';
        $this->jNama = '';
        $this->jNamaEn = '';
    }

    public function closeJurusanForm(): void
    {
        $this->showJurusanForm = false;
        $this->resetJurusanForm();
    }

    public function saveJurusan(): void
    {
        $this->validate([
            'jKode' => ['required', 'string', 'max:10', Rule::unique('jurusans', 'kode')->ignore($this->editJurusanId)],
            'jNama' => ['required', 'string', 'max:150'],
            'jNamaEn' => ['nullable', 'string', 'max:150'],
        ]);

        $isEdit = (bool) $this->editJurusanId;

        Jurusan::updateOrCreate(
            ['id' => $this->editJurusanId],
            [
                'kode' => strtoupper($this->jKode),
                'nama' => $this->jNama,
                'nama_en' => $this->jNamaEn ?: null,
            ]
        );

        $this->closeJurusanForm();
        session()->flash('success', $isEdit ? 'Jurusan diperbarui.' : 'Jurusan ditambahkan.');
    }

    public function toggleJurusan(int $id): void
    {
        $j = Jurusan::findOrFail($id);
        $j->update(['is_active' => !$j->is_active]);
    }

    // ─── Program Studi ───────────────────────────────

    public function openProdiForm(?int $id = null): void
    {
        if ($id) {
            $p = ProgramStudi::findOrFail($id);
            $this->editProdiId = $p->id;
            $this->pJurusanId = (string) $p->jurusan_id;
            $this->pKode = $p->kode;
            $this->pNama = $p->nama;
            $this->pJenjang = $p->jenjang;
        } else {
            $this->resetProdiForm();
        }
        $this->showProdiForm = true;
    }

    public function resetProdiForm(): void
    {
        $this->editProdiId = null;
        $this->pJurusanId = '';
        $this->pKode = '';
        $this->pNama = '';
        $this->pJenjang = 'D3';
    }

    public function closeProdiForm(): void
    {
        $this->showProdiForm = false;
        $this->resetProdiForm();
    }

    public function saveProdi(): void
    {
        $this->validate([
            'pJurusanId' => ['required', 'exists:jurusans,id'],
            'pKode' => ['required', 'string', 'max:10', Rule::unique('program_studis', 'kode')->ignore($this->editProdiId)],
            'pNama' => ['required', 'string', 'max:150'],
            'pJenjang' => ['required', 'in:D1,D2,D3,D4,S1,S2'],
        ]);

        $isEdit = (bool) $this->editProdiId;

        ProgramStudi::updateOrCreate(
            ['id' => $this->editProdiId],
            [
                'jurusan_id' => $this->pJurusanId,
                'kode' => strtoupper($this->pKode),
                'nama' => $this->pNama,
                'jenjang' => $this->pJenjang,
            ]
        );

        $this->closeProdiForm();
        session()->flash('success', $isEdit ? 'Program Studi diperbarui.' : 'Program Studi ditambahkan.');
    }

    public function toggleProdi(int $id): void
    {
        $p = ProgramStudi::findOrFail($id);
        $p->update(['is_active' => !$p->is_active]);
    }

    // ─── Render ──────────────────────────────────────

    public function render()
    {
        $jurusans = Jurusan::with('programStudis')->withCount('programStudis')->orderBy('nama')->get();
        $allJurusans = Jurusan::active()->orderBy('nama')->get();

        return view('livewire.admin.manage-jurusan-prodi', compact('jurusans', 'allJurusans'))
            ->layout('layouts.app')
            ->title('Kelola Jurusan & Prodi');
    }
}
