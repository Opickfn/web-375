<?php

namespace App\Livewire\Admin;

use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageGedungRuangan extends Component
{
    // Gedung form
    public bool $showGedungForm = false;
    public ?int $editGedungId = null;
    public string $gKode = '';
    public string $gNama = '';
    public string $gNamaEn = '';

    // Ruangan form
    public bool $showRuanganForm = false;
    public ?int $editRuanganId = null;
    public string $rGedungId = '';
    public string $rKode = '';
    public string $rNama = '';
    public string $rJenjang = 'D3';

    // ─── Gedung ─────────────────────────────────

    public function openGedungForm(?int $id = null): void
    {
        if ($id) {
            $g = Gedung::findOrFail($id);
            $this->editGedungId = $g->id;
            $this->gKode = $g->kode;
            $this->gNama = $g->nama;
            $this->gNamaEn = $g->nama_en ?? '';
        } else {
            $this->resetGedungForm();
        }
        $this->showGedungForm = true;
    }

    public function resetGedungForm(): void
    {
        $this->editGedungId = null;
        $this->gKode = '';
        $this->gNama = '';
        $this->gNamaEn = '';
    }

    public function closeGedungForm(): void
    {
        $this->showGedungForm = false;
        $this->resetGedungForm();
    }

    public function saveGedung(): void
    {
        $this->validate([
            'gKode' => ['required', 'string', 'max:10', Rule::unique('gedungs', 'kode')->ignore($this->editGedungId)],
            'gNama' => ['required', 'string', 'max:150'],
            'gNamaEn' => ['nullable', 'string', 'max:150'],
        ]);

        $isEdit = (bool) $this->editGedungId;

        Gedung::updateOrCreate(
            ['id' => $this->editGedungId],
            [
                'kode' => strtoupper($this->gKode),
                'nama' => $this->gNama,
                'nama_en' => $this->gNamaEn ?: null,
            ]
        );

        $this->closeGedungForm();
        session()->flash('success', $isEdit ? 'Gedung diperbarui.' : 'Gedung ditambahkan.');
    }

    public function toggleGedung(int $id): void
    {
        $g = Gedung::findOrFail($id);
        $g->update(['is_active' => !$g->is_active]);
    }

    // ─── Ruangan ───────────────────────────────

    public function openRuanganForm(?int $id = null): void
    {
        if ($id) {
            $r = Ruangan::findOrFail($id);
            $this->editRuanganId = $r->id;
            $this->rGedungId = (string) $r->gedung_id;
            $this->rKode = $r->kode;
            $this->rNama = $r->nama;
            $this->rJenjang = $r->jenjang;
        } else {
            $this->resetRuanganForm();
        }
        $this->showRuanganForm = true;
    }

    public function resetRuanganForm(): void
    {
        $this->editRuanganId = null;
        $this->rGedungId = '';
        $this->rKode = '';
        $this->rNama = '';
        $this->rJenjang = 'D3';
    }

    public function closeRuanganForm(): void
    {
        $this->showRuanganForm = false;
        $this->resetRuanganForm();
    }

    public function saveRuangan(): void
    {
        $this->validate([
            'rGedungId' => ['required', 'exists:gedungs,id'],
            'rKode' => ['required', 'string', 'max:10', Rule::unique('ruangans', 'kode')->ignore($this->editRuanganId)],
            'rNama' => ['required', 'string', 'max:150'],
            'rJenjang' => ['required', 'in:D1,D2,D3,D4,S1,S2'],
        ]);

        $isEdit = (bool) $this->editRuanganId;

        Ruangan::updateOrCreate(
            ['id' => $this->editRuanganId],
            [
                'gedung_id' => $this->rGedungId,
                'kode' => strtoupper($this->rKode),
                'nama' => $this->rNama,
                'jenjang' => $this->rJenjang,
            ]
        );

        $this->closeRuanganForm();
        session()->flash('success', $isEdit ? 'Ruangan diperbarui.' : 'Ruangan ditambahkan.');
    }

    public function toggleRuangan(int $id): void
    {
        $r = Ruangan::findOrFail($id);
        $r->update(['is_active' => !$r->is_active]);
    }

    // ─── Render ──────────────────────────────────────

    public function render()
    {
        $gedungs = Gedung::with('ruangans')->withCount('ruangans')->orderBy('nama')->get();
        $allGedungs = Gedung::active()->orderBy('nama')->get();

        return view('livewire.admin.manage-gedung-ruangan', compact('gedungs', 'allGedungs'))
            ->layout('layouts.app')
            ->title('Kelola Gedung & Ruangan');
    }
}
