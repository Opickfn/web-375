<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:mahasiswa,dosen,umum'],
        ];

        // Conditional validation per user type
        if (($input['user_type'] ?? '') === 'mahasiswa') {
            $rules['nim'] = ['required', 'string', 'max:20', Rule::unique(User::class)];
            $rules['kelas'] = ['required', 'string', 'max:20'];
            $rules['jurusan'] = ['required', 'string', 'max:100'];
            $rules['program_studi'] = ['required', 'string', 'max:100'];
            $rules['tahun_angkatan'] = ['required', 'string', 'size:4'];
        }

        if (($input['user_type'] ?? '') === 'dosen') {
            $rules['nomor_dosen'] = ['required', 'string', 'max:30', Rule::unique(User::class)];
            $rules['jabatan'] = ['required', 'string', 'max:100'];
        }

        Validator::make($input, $rules)->validate();

        return User::create([
            'full_name' => $input['full_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'reporter',
            'user_type' => $input['user_type'],
            'phone' => $input['phone'] ?? null,
            // Mahasiswa
            'nim' => $input['nim'] ?? null,
            'kelas' => $input['kelas'] ?? null,
            'jurusan' => $input['jurusan'] ?? null,
            'program_studi' => $input['program_studi'] ?? null,
            'tahun_angkatan' => $input['tahun_angkatan'] ?? null,
            // Dosen
            'nomor_dosen' => $input['nomor_dosen'] ?? null,
            'jabatan' => $input['jabatan'] ?? null,
        ]);
    }
}
