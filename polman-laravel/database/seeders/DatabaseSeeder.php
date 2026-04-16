<?php

namespace Database\Seeders;

use App\Models\Gedung;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Lokasi Hirarki Kampus ─────────────────────────

        $kampus = Location::create([
            'code' => 'KP1',
            'name' => 'Kampus Politeknik Negeri Malang',
            'type' => 'campus',
        ]);

        $gedungUtama = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'G1',
            'name' => 'Gedung Utama',
            'type' => 'gedung',
            'category' => 'Gedung',
        ]);

        $gedungUtamaGedung = Gedung::create([
            'kode' => 'G1',
            'nama' => 'Gedung Utama',
            'nama_en' => 'Main Building',
            'is_active' => true,
        ]);

        $lantai1 = Location::create([
            'parent_id' => $gedungUtama->id,
            'code' => 'L1',
            'name' => 'Lantai 1',
            'type' => 'lantai',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R101',
            'name' => 'Ruang Kelas A',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R102',
            'name' => 'Ruang Kelas B',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Area Sirkulasi Utama',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);

        $lantai2 = Location::create([
            'parent_id' => $gedungUtama->id,
            'code' => 'L2',
            'name' => 'Lantai 2',
            'type' => 'lantai',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R201',
            'name' => 'Lab Mesin',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);

        $gedungBengkel = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'G2',
            'name' => 'Gedung Bengkel',
            'type' => 'gedung',
            'category' => 'Gedung',
        ]);

        $gedungBengkelGedung = Gedung::create([
            'kode' => 'G2',
            'nama' => 'Gedung Bengkel',
            'nama_en' => 'Workshop Building',
            'is_active' => true,
        ]);

        $lantaiBengkel = Location::create([
            'parent_id' => $gedungBengkel->id,
            'code' => 'L1',
            'name' => 'Lantai 1',
            'type' => 'lantai',
        ]);
        $bengkelMesin = Location::create([
            'parent_id' => $lantaiBengkel->id,
            'code' => 'B001',
            'name' => 'Bengkel Mesin',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        $bengkelOtomasi = Location::create([
            'parent_id' => $lantaiBengkel->id,
            'code' => 'B002',
            'name' => 'Bengkel Otomasi',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);

        $jalanUtama = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'INF01',
            'name' => 'Jalan Utama',
            'type' => 'infrastruktur',
            'category' => 'Infrastruktur Umum',
        ]);
        $kantin = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'INF02',
            'name' => 'Kantin Polman',
            'type' => 'infrastruktur',
            'category' => 'Infrastruktur Umum',
        ]);
        $masjid = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'INF03',
            'name' => 'Masjid Kampus',
            'type' => 'infrastruktur',
            'category' => 'Infrastruktur Umum',
        ]);

        // ─── Users ──────────────────────────────────────

        User::create([
            'full_name' => 'Admin Polman',
            'email' => 'admin@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'user_type' => 'umum',
        ]);

        $pimpinan = User::create([
            'full_name' => 'Ir. Siti Hasnah, M.Eng.',
            'email' => 'pimpinan@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => '0012093405',
            'jabatan' => 'Wakil Direktur Bidang Kemahasiswaan',
        ]);
        $pimpinan->assignedLocations()->sync([$gedungUtama->id, $gedungBengkel->id]);

        $spmi = User::create([
            'full_name' => 'Dr. Rendra Fadhil, S.T.',
            'email' => 'spmi@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'spmi',
            'user_type' => 'dosen',
            'nomor_dosen' => '0011072207',
            'jabatan' => 'Auditor Internal SPMI',
        ]);
        $spmi->assignedLocations()->sync([$masjid->id, $jalanUtama->id, $kantin->id]);

        $pjArea = User::create([
            'full_name' => 'Ir. Yanto Susanto',
            'email' => 'pjarea@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pj_area',
            'user_type' => 'dosen',
            'nomor_dosen' => '0016045603',
            'jabatan' => 'Penanggung Jawab Area',
        ]);
        $pjArea->assignedLocations()->sync([$gedungBengkel->id]);

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

        User::create([
            'full_name' => 'Ir. Cahya Nugraha, M.Eng.',
            'email' => 'cahya@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'dosen',
            'nomor_dosen' => '0015098802',
            'jabatan' => 'Lektor',
        ]);

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
