<?php

namespace App\Livewire\Profile;

use App\Models\Jurusan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditProfile extends Component
{
    // Profile fields
    public string $full_name = '';
    public string $email = '';
    public string $phone = '';

    // Mahasiswa
    public string $nim = '';
    public string $kelas = '';
    public string $jurusan = '';
    public string $program_studi = '';
    public string $tahun_angkatan = '';

    // Dosen
    public string $nomor_dosen = '';
    public string $jabatan = '';

    // Password
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->full_name = $user->full_name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';

        if ($user->isMahasiswa()) {
            $this->nim = $user->nim ?? '';
            $this->kelas = $user->kelas ?? '';
            $this->jurusan = $user->jurusan ?? '';
            $this->program_studi = $user->program_studi ?? '';
            $this->tahun_angkatan = $user->tahun_angkatan ?? '';
        }

        if ($user->isDosen()) {
            $this->nomor_dosen = $user->nomor_dosen ?? '';
            $this->jabatan = $user->jabatan ?? '';
        }
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $rules = [
            'full_name' => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => ['nullable', 'string', 'max:20'],
        ];

        if ($user->isMahasiswa()) {
            $rules['nim']            = ['required', 'string', 'max:20', Rule::unique('users', 'nim')->ignore($user->id)];
            $rules['kelas']          = ['required', 'string', 'max:20'];
            $rules['jurusan']        = ['required', 'string', 'max:150'];
            $rules['program_studi']  = ['required', 'string', 'max:150'];
            $rules['tahun_angkatan'] = ['required', 'string', 'size:4'];
        }

        if ($user->isDosen()) {
            $rules['nomor_dosen'] = ['required', 'string', 'max:30', Rule::unique('users', 'nomor_dosen')->ignore($user->id)];
            $rules['jabatan']     = ['required', 'string', 'max:100'];
        }

        $this->validate($rules);

        $data = [
            'full_name' => $this->full_name,
            'email'     => $this->email,
            'phone'     => $this->phone ?: null,
        ];

        if ($user->isMahasiswa()) {
            $data += [
                'nim'            => $this->nim,
                'kelas'          => $this->kelas,
                'jurusan'        => $this->jurusan,
                'program_studi'  => $this->program_studi,
                'tahun_angkatan' => $this->tahun_angkatan,
            ];
        }

        if ($user->isDosen()) {
            $data += [
                'nomor_dosen' => $this->nomor_dosen,
                'jabatan'     => $this->jabatan,
            ];
        }

        $user->update($data);

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password'          => ['required'],
            'new_password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_success', 'Password berhasil diubah.');
    }

    public function render()
    {
        $jurusans = Jurusan::active()->orderBy('nama')->get();

        return view('livewire.profile.edit-profile', compact('jurusans'))
            ->layout('layouts.app')
            ->title('Profil Saya');
    }
}
