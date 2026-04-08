<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Jurusan & Prodi ────────────────────────────

        $tomm = Jurusan::create([
            'kode' => 'TOMM',
            'nama' => 'Teknik Otomasi Manufaktur dan Mekatronika',
            'nama_en' => 'Automation Engineering',
        ]);
        ProgramStudi::insert([
            ['jurusan_id' => $tomm->id, 'kode' => 'TOM', 'nama' => 'Teknik Otomasi Manufaktur', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tomm->id, 'kode' => 'TMK', 'nama' => 'Teknik Mekatronika', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tomm->id, 'kode' => 'TOI', 'nama' => 'Teknik Otomasi Industri', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $tm = Jurusan::create([
            'kode' => 'TM',
            'nama' => 'Teknik Manufaktur',
            'nama_en' => 'Manufacture Engineering',
        ]);
        ProgramStudi::insert([
            ['jurusan_id' => $tm->id, 'kode' => 'TME', 'nama' => 'Teknik Mesin', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tm->id, 'kode' => 'TMF', 'nama' => 'Teknik Manufaktur', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tm->id, 'kode' => 'TMP', 'nama' => 'Teknik Manufaktur', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $tp = Jurusan::create([
            'kode' => 'TP',
            'nama' => 'Teknik Perancangan',
            'nama_en' => 'Design Engineering',
        ]);
        ProgramStudi::insert([
            ['jurusan_id' => $tp->id, 'kode' => 'TPM', 'nama' => 'Teknik Perancangan Mekanik', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tp->id, 'kode' => 'TKB', 'nama' => 'Teknik Konstruksi Bangunan', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tp->id, 'kode' => 'TPK', 'nama' => 'Teknik Perancangan dan Konstruksi Mesin', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $tpl = Jurusan::create([
            'kode' => 'TPL',
            'nama' => 'Teknik Pengecoran Logam',
            'nama_en' => 'Foundry Engineering',
        ]);
        ProgramStudi::insert([
            ['jurusan_id' => $tpl->id, 'kode' => 'TPG', 'nama' => 'Teknik Pengecoran Logam', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['jurusan_id' => $tpl->id, 'kode' => 'TRP', 'nama' => 'Teknologi Rekayasa Pengecoran Logam', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─── Users ──────────────────────────────────────

        // Admin (Manajer Puncak)
        User::create([
            'full_name' => 'Admin Polman',
            'email' => 'admin@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'user_type' => 'umum',
        ]);

        // Manager (Kepala Manajemen)
        User::create([
            'full_name' => 'Dr. Budi Santoso, M.T.',
            'email' => 'manager@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'user_type' => 'dosen',
            'nomor_dosen' => '0028107501',
            'jabatan' => 'Kepala K3 & Lingkungan',
        ]);

        // Reporter - Mahasiswa 1
        User::create([
            'full_name' => 'Andi Pratama',
            'email' => 'andi@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'mahasiswa',
            'nim' => '221511001',
            'kelas' => '2A',
            'jurusan' => $tomm->nama,
            'program_studi' => 'D3 Teknik Mekatronika',
            'tahun_angkatan' => '2022',
        ]);

        // Reporter - Mahasiswa 2
        User::create([
            'full_name' => 'Sari Dewi',
            'email' => 'sari@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'mahasiswa',
            'nim' => '221512015',
            'kelas' => '2B',
            'jurusan' => $tm->nama,
            'program_studi' => 'D3 Teknik Mesin',
            'tahun_angkatan' => '2022',
        ]);

        // Reporter - Dosen
        User::create([
            'full_name' => 'Ir. Cahya Nugraha, M.Eng.',
            'email' => 'cahya@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'dosen',
            'nomor_dosen' => '0015098802',
            'jabatan' => 'Lektor',
        ]);

        // Reporter - Umum
        User::create([
            'full_name' => 'Rini Kurniawati',
            'email' => 'rini@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'umum',
            'phone' => '081234567890',
        ]);
    }
}
