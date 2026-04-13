<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePublicReport extends Component
{
    use WithFileUploads;

    public string $kategori = '';
    public string $gedung_id = '';
    public string $ruangan_id = '';
    public string $detail_lokasi = '';
    public string $deskripsi = '';
    public string $prioritas = 'sedang';
    public $bukti;

    // Success state
    public bool $showSuccess = false;
    public ?Report $successReport = null;

    protected function rules(): array
    {
        return [
            'kategori' => 'required|in:5R,7S,K3',
            'gedung_id' => 'required|exists:gedungs,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'detail_lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'bukti' => 'nullable|image|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'kategori.required' => 'Pilih kategori pelanggaran.',
            'gedung_id.required' => 'Gedung wajib dipilih.',
            'ruangan_id.required' => 'Ruangan wajib dipilih.',
            'detail_lokasi.required' => 'Detail lokasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'bukti.image' => 'File harus berupa gambar.',
            'bukti.max' => 'Ukuran file maksimal 5MB.',
        ];
    }

    public function resetRuangan(): void
    {
        $this->ruangan_id = '';
    }

    public function submit()
    {
        $this->validate();

        try {
            // Construct lokasi from gedung/ruangan/detail_lokasi
            $gedung = \App\Models\Gedung::findOrFail($this->gedung_id);
            $ruangan = \App\Models\Ruangan::findOrFail($this->ruangan_id);
            $lokasi = "{$gedung->nama} - {$ruangan->nama} - {$this->detail_lokasi}";

            $data = [
                'reporter_id' => null, // Publik - tidak ada user ID
                'kategori' => $this->kategori,
                'lokasi' => $lokasi,
                'deskripsi' => $this->deskripsi,
                'prioritas' => $this->prioritas,
                'status' => 'pending',
                'gedung_id' => $this->gedung_id,
            ];

            if ($this->bukti) {
                $filename = time() . '_' . $this->bukti->getClientOriginalName();
                $this->bukti->storeAs('uploads', $filename, 'public');
                $data['bukti'] = $filename;
            }

            $report = Report::create($data);
            $this->successReport = $report;
            $this->showSuccess = true;

            // Reset form
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengirim laporan: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->showSuccess = false;
        $this->successReport = null;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.reports.create-public-report')
            ->layout('layouts.guest')
            ->title('Buat Laporan');
    }
}
