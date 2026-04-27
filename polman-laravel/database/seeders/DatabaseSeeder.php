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
            'name' => 'Kampus Politeknik Manufaktur Negeri Bandung',
            'type' => 'campus',
        ]);

        $gedung1 = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'G1',
            'name' => 'Gedung ISmart',
            'type' => 'gedung',
            'category' => 'Gedung',
        ]);

        $gedung1Gedung = Gedung::create([
            'kode' => 'G1',
            'nama' => 'Gedung ISmart',
            'nama_en' => 'ISmart Building',
            'is_active' => true,
        ]);

        $lantai1 = Location::create([
            'parent_id' => $gedung1->id,
            'code' => 'L1',
            'name' => 'Lantai 1',
            'type' => 'lantai',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R101',
            'name' => 'Ruang Project Based Learning',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R102',
            'name' => 'Ruang Diskusi 1',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R103',
            'name' => 'Ruang Diskusi 2',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R104',
            'name' => 'Ruang IMaschine',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'code' => 'R105',
            'name' => 'Ruang Dosen dan Staff',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Area Sirkulasi Utama',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Mushola',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Teras Depan',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Teras Belakang',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Toilet Ruang PBL',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai1->id,
            'name' => 'Toilet Ruang IMaschine',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);

        $lantai2 = Location::create([
            'parent_id' => $gedung1->id,
            'code' => 'L2',
            'name' => 'Lantai 2',
            'type' => 'lantai',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R201',
            'name' => 'Ruang Kelas 1',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R202',
            'name' => 'Ruang Kelas 2',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R203',
            'name' => 'Ruang Kelas 3',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R204',
            'name' => 'Ruang Kelas 4',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R205',
            'name' => 'Ruang Kelas 5',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R206',
            'name' => 'Ruang Kelas 6',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R207',
            'name' => 'Ruang Komputer',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R208',
            'name' => 'Ruang Kelas 7',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'code' => 'R209',
            'name' => 'Ruang Diskusi Atas',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'name' => 'Toilet dekat Kelas 1',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai2->id,
            'name' => 'Toilet dekat Kelas 8',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);

        $gedungTechnopole1 = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'G2',
            'name' => 'Gedung Technopole 1',
            'type' => 'gedung',
            'category' => 'Gedung',
        ]);

        $gedungTechnopole1Gedung = Gedung::create([
            'kode' => 'G2',
            'nama' => 'Gedung Technopole 1',
            'nama_en' => 'Technopole 1 Building',
            'is_active' => true,
        ]);

        $lantaiTechnopole1 = Location::create([
            'parent_id' => $gedungTechnopole1->id,
            'code' => 'L1',
            'name' => 'Lantai 1',
            'type' => 'lantai',
        ]);
        Location::create([
            'parent_id' => $lantaiTechnopole1->id,
            'code' => 'B001',
            'name' => 'Technopole 1 Mesin',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantaiTechnopole1->id,
            'code' => 'B002',
            'name' => 'Technopole 1 Otomasi',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantaiTechnopole1->id,
            'name' => 'Area Sirkulasi Utama',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        $lantai2Technopole1 = Location::create([
            'parent_id' => $gedungTechnopole1->id,
            'code' => 'L2',
            'name' => 'Lantai 2',
            'type' => 'lantai',
        ]);
        $lantai3Technopole1 = Location::create([
            'parent_id' => $gedungTechnopole1->id,
            'code' => 'L3',
            'name' => 'Lantai 3',
            'type' => 'lantai',
        ]);


        $gedungTechnopole2 = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'G3',
            'name' => 'Gedung Technopole 2',
            'type' => 'gedung',
            'category' => 'Gedung',
        ]);

        $gedungTechnopole2Gedung = Gedung::create([
            'kode' => 'G3',
            'nama' => 'Gedung Technopole 2',
            'nama_en' => 'Technopole 2 Building',
            'is_active' => true,
        ]);
        $lantaiTechnopole2 = Location::create([
            'parent_id' => $gedungTechnopole2->id,
            'code' => 'L1',
            'name' => 'Lantai 1',
            'type' => 'lantai',
        ]);
        
        $lantai2Technopole2 = Location::create([
            'parent_id' => $gedungTechnopole2->id,
            'code' => 'L2',
            'name' => 'Lantai 2',
            'type' => 'lantai',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'code' => 'C201',
            'name' => 'Lab Field Automation',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'code' => 'B202',
            'name' => 'Lab Edge Computing',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'code' => 'B203',
            'name' => 'Lab PLC',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'code' => 'B203',
            'name' => 'Lab Mikrokontroler',
            'type' => 'ruangan',
            'category' => 'Ruangan',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'name' => 'Mushola Lantai 2',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'name' => 'Toilet Lantai 2',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        Location::create([
            'parent_id' => $lantai2Technopole2->id,
            'name' => 'Tempat Wudhu Lantai 2',
            'type' => 'area',
            'category' => 'Area Lainnya',
        ]);
        $lantai3Technopole2 = Location::create([
            'parent_id' => $gedungTechnopole2->id,
            'code' => 'L3', 
            'name' => 'Lantai 3',
            'type' => 'lantai',
        ]);

        $jalanUtama = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'INF01',
            'name' => 'Jalan Utama',
            'type' => 'infrastruktur',
            'category' => 'Infrastruktur Umum',
        ]);
        $tamanTechno = Location::create([
            'parent_id' => $kampus->id,
            'code' => 'INF02',
            'name' => 'Taman Gedung Technopole',
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
        $pimpinan->assignedLocations()->sync([$gedung1->id, $gedungTechnopole1->id]);

        $spmi = User::create([
            'full_name' => 'Dr. Rendra Fadhil, S.T.',
            'email' => 'spmi@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'spmi',
            'user_type' => 'dosen',
            'nomor_dosen' => '0011072207',
            'jabatan' => 'Auditor Internal SPMI',
        ]);
        $spmi->assignedLocations()->sync([$gedung1->id, $jalanUtama->id, $tamanTechno->id]);

        $pjArea = User::create([
            'full_name' => 'Ir. Yanto Susanto',
            'email' => 'pjarea@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pj_area',
            'user_type' => 'dosen',
            'nomor_dosen' => '0016045603',
            'jabatan' => 'Penanggung Jawab Area',
        ]);
        $pjArea->assignedLocations()->sync([$gedungTechnopole1->id, $jalanUtama->id, $tamanTechno->id]);

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
            'gedung' => 'Gedung Technopole1',
            'ruangan' => 'Technopole1 Mesin',
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
