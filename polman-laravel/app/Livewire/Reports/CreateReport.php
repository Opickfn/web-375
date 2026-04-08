<?php

namespace App\Livewire\Reports;

use App\Models\Point;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateReport extends Component
{
    use WithFileUploads;

    public string $kategori = '';
    public string $lokasi = '';
    public string $deskripsi = '';
    public string $prioritas = 'sedang';
    public $bukti;

    protected function rules(): array
    {
        return [
            'kategori' => 'required|in:5R,7S,K3',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'bukti' => 'nullable|image|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'kategori.required' => 'Pilih kategori pelanggaran.',
            'lokasi.required' => 'Lokasi kejadian wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'bukti.image' => 'File harus berupa gambar.',
            'bukti.max' => 'Ukuran file maksimal 5MB.',
        ];
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'reporter_id' => Auth::id(),
            'kategori' => $this->kategori,
            'lokasi' => $this->lokasi,
            'deskripsi' => $this->deskripsi,
            'prioritas' => $this->prioritas,
            'status' => 'pending',
        ];

        if ($this->bukti) {
            $filename = time() . '_' . $this->bukti->getClientOriginalName();
            $this->bukti->storeAs('uploads', $filename, 'public');
            $data['bukti'] = $filename;
        }

        $report = Report::create($data);

        // Award points for submission
        Point::create([
            'user_id' => Auth::id(),
            'report_id' => $report->id,
            'amount' => 10,
            'type' => 'submit',
            'description' => 'Poin submit laporan ' . $report->code,
        ]);

        session()->flash('success', 'Laporan berhasil dikirim! Anda mendapatkan +10 poin.');
        return redirect()->route('reports.my');
    }

    public function render()
    {
        return view('livewire.reports.create-report')
            ->layout('layouts.app')
            ->title('Buat Laporan');
    }
}
