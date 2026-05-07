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
            'full_name' => 'Darma Firmansyah Undayat., S.ST., M.T.',
            'email' => 'direktur@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Direktur Politeknik Manufaktur Negeri Bandung',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Dr. Aris Budiyarto, S.T., M.T.',
            'email' => 'wadir1@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Wakil Direktur Bidang Akademik dan Sistem Informasi',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Ery Hidayat, S.T., M.T.',
            'email' => 'wadir2@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Wakil Direktur Bidang Keuangan & Umum',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Jata Budiman, S.ST., M.T',
            'email' => 'wadir3@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Wakil Direktur Bidang Perencanaan, Kemahasiswaan dan Alumni',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Deasy Damayanti',
            'email' => 'kabagAkademikMahasiswa@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala Bagian Akademik dan kemahasiswaan',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Hilman Indrayanto',
            'email' => 'kabagUmum@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala Bagian Perencanaan, Keuangan dan Umum',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Roni Kusnowo',
            'email' => 'kadivBisnis@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala Divisi Pengembangan Bisnis (d/h BPU)',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);
        
        $pimpinan = User::create([
            'full_name' => 'Yogi Muldani Hendrawan',
            'email' => 'kampus2@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala Kantor Pengembang dan pengelola Kampus 2',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Dr. Herman Budi Harja, S.T., M.T.',
            'email' => 'kajurTM@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Ketua Jurusan Teknik Manufaktur',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Bustami Ibrahim, S.S.T., M.T',
            'email' => 'kajurTP@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Ketua Jurusan Teknik Perancangan',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Cecep Ruskandi, S.T., M.T.',
            'email' => 'kajurTPL@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Ketua Jurusan Teknik Pengecoran Logam',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Ridwan, S.S.T., M.Eng.',
            'email' => 'kajurTMO@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Ketua Jurusan Teknik Otomasi Manufaktur dan Mekatronika',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Noval Lilansa',
            'email' => 'kasatPengabdian@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala Pusat Penelitian, dan Pengabdian Kepada Masyarakat',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Dicky Rahman Hanafiah',
            'email' => 'UPAPerpus@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala UPA Perpustakaan',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Susetyo Bagas Bhaskoro',
            'email' => 'UPATIK@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala UPA Teknologi Informasi dan Komunikasi',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'M. Fauzi',
            'email' => 'UPALayanan@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala UPA Layanan Uji Kompetensi',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Agus Kustiman',
            'email' => 'UPAMaintenance@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala UPA Perawatan dan Perbaikan',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'M. Dody Priyambudi',
            'email' => 'UPAPerlengkapan@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala UPA Perlengkapan',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $pimpinan = User::create([
            'full_name' => 'Ruswandi',
            'email' => 'UPAKarier@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala UPA Pengembangan Karier dan Kewirausahaan',
        ]);
        $pimpinan->assignedLocations()->sync([$kampus->id]);

        $spmi = User::create([
            'full_name' => 'Achmad Muhammad',
            'email' => 'kaspi@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'spmi',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Ketua SPI',
        ]);
        $spmi->assignedLocations()->sync([$kampus->id]);

        $spmi = User::create([
            'full_name' => 'Hadi Supriyanto',
            'email' => 'kaspmi@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'spmi',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Kepala Pusat Penjaminan Mutu dan Pengembangan Pembelajaran',
        ]);
        $spmi->assignedLocations()->sync([$kampus->id]);

        $pjArea = User::create([
            'full_name' => 'Nurjamiluddin',
            'email' => 'pjarea@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pj_area',
            'user_type' => 'dosen',
            'nomor_dosen' => null,
            'jabatan' => 'Penanggung Jawab Area',
        ]);
        $pjArea->assignedLocations()->sync([$gedungTechnopole1->id, $jalanUtama->id, $tamanTechno->id]);

        User::create([
            'full_name' => 'Fadhlurrofiq Nurrohmat',
            'email' => 'fadhlurrofiq@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'mahasiswa',
            'nim' => '223443054',
            'kelas' => '3AEC3',
            'gedung' => 'AE',
            'ruangan' => 'TRIN',
            'tahun_angkatan' => '2023',
        ]);

        User::create([
            'full_name' => 'Diaz Ardiansyah',
            'email' => 'diaz@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'mahasiswa',
            'nim' => '223443075',
            'kelas' => '3AEC4',
            'gedung' => 'AE',
            'ruangan' => 'TRIN',
            'tahun_angkatan' => '2023',
        ]);

        User::create([
            'full_name' => 'Luthfiyyah Khansa',
            'email' => 'upi@polman.ac.id',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'user_type' => 'mahasiswa',
            'nim' => '223443083',
            'kelas' => '3AEC4',
            'gedung' => 'AE',
            'ruangan' => 'TRIN',
            'tahun_angkatan' => '2023',
        ]);

        
    }
}
