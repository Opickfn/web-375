<?php

namespace Database\Seeders;

use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Gedung & Ruangan ────────────────────────────

        $gedung1 = Gedung::create([
            'kode' => 'G1',
            'nama' => 'Gedung Utama',
            'nama_en' => 'Main Building',
        ]);
        Ruangan::insert([
            ['gedung_id' => $gedung1->id, 'kode' => 'R101', 'nama' => 'Ruang Kelas A', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung1->id, 'kode' => 'R102', 'nama' => 'Ruang Kelas B', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung1->id, 'kode' => 'R201', 'nama' => 'Lab Mesin', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $gedung2 = Gedung::create([
            'kode' => 'G2',
            'nama' => 'Gedung Bengkel',
            'nama_en' => 'Workshop Building',
        ]);
        Ruangan::insert([
            ['gedung_id' => $gedung2->id, 'kode' => 'B001', 'nama' => 'Bengkel Mesin', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung2->id, 'kode' => 'B002', 'nama' => 'Bengkel Otomasi', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung2->id, 'kode' => 'B101', 'nama' => 'Lab CNC', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $gedung3 = Gedung::create([
            'kode' => 'G3',
            'nama' => 'Gedung Administrasi',
            'nama_en' => 'Administration Building',
        ]);
        Ruangan::insert([
            ['gedung_id' => $gedung3->id, 'kode' => 'A101', 'nama' => 'Kantor Rektorat', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung3->id, 'kode' => 'A102', 'nama' => 'Ruang Meeting', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung3->id, 'kode' => 'A201', 'nama' => 'Library', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $gedung4 = Gedung::create([
            'kode' => 'G4',
            'nama' => 'Gedung Perancangan',
            'nama_en' => 'Design Building',
        ]);
        Ruangan::insert([
            ['gedung_id' => $gedung4->id, 'kode' => 'D101', 'nama' => 'Studio Desain', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung4->id, 'kode' => 'D102', 'nama' => 'Lab CAD', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['gedung_id' => $gedung4->id, 'kode' => 'D201', 'nama' => 'Lab Simulasi', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
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

        // Manager (Kepala Manajemen) - Assign ke Gedung 2
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
            'gedung' => 'Gedung Utama',
            'ruangan' => 'Ruang Kelas A',
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
            'gedung' => 'Gedung Bengkel',
            'ruangan' => 'Bengkel Mesin',
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
