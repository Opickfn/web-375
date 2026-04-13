@extends('layouts.guest')
@section('title', 'Sistem Pelaporan Improvement')

@section('content')
{{-- Landing Navbar --}}
<nav class="landing-nav" id="landingNav" style="position: fixed; width: 100%; top: 0; z-index: 100; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transition: box-shadow 0.3s; border-bottom: 1px solid rgba(0,0,0,0.05);">
    <div class="navbar-brand">
        <img src="{{ asset('images/polman.png') }}" alt="Polman" style="height: 40px;">
        <span style="font-weight: 700; font-size: 1.25rem;">Polman Report</span>
    </div>
    <div class="landing-nav-links">
        <a href="{{ route('leaderboard') }}" class="btn btn-outline btn-sm">
            <i data-lucide="trophy" style="width:14px;height:14px;"></i> Leaderboard
        </a>
        <a href="{{ route('reports.public') }}" class="btn btn-outline btn-sm">
            <i data-lucide="file-text" style="width:14px;height:14px;"></i> Laporan
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
    </div>
</nav>

{{-- Hero Section with Typing Effect --}}
<section class="landing-hero" style="min-height: 80vh; display: flex; align-items: center; padding-top: 80px; background: radial-gradient(circle at 10% 20%, rgba(12, 107, 175, 0.05) 0%, transparent 40%);">
    <div class="container" style="text-align: center;">
        <div class="typewriter-wrapper">
            <h1 class="typewriter" style="font-size: 3.5rem; color: var(--text-dark); margin-bottom: 24px; display: inline-block;">
                Sistem Pelaporan Improvement
            </h1>
        </div>
        <p class="reveal" style="font-size: 1.25rem; color: var(--text-muted); max-width: 700px; margin: 0 auto 40px; line-height: 1.6;">
            Platform digital modern untuk melaporkan, memantau, dan mengelola temuan perbaikan 5R, 7S, dan K3 di lingkungan kampus Politeknik Manufaktur Bandung secara real-time dan terintegrasi.
        </p>
        <div class="landing-hero-actions reveal" style="display: flex; justify-content: center; gap: 16px; margin-top: 20px;">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg" style="transform: scale(1); transition: transform 0.2s; box-shadow: 0 10px 25px rgba(12,107,175,0.3);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i data-lucide="arrow-right" style="width:20px;height:20px;"></i>
                Mulai Dari Sini
            </a>
            <a href="#features" class="btn btn-outline btn-lg" onclick="document.getElementById('features').scrollIntoView({behavior: 'smooth'})">
                Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
</section>

{{-- Video Parallax Section --}}
<section class="video-section" id="videoParallax">
    <div class="video-container" id="videoContainer">
        <video autoplay muted loop playsinline>
            {{-- Menggunakan stock video berkualitas tinggi tentang kerja modern --}}
            <source src="https://assets.mixkit.co/videos/preview/mixkit-software-developer-working-on-code-1730-large.mp4" type="video/mp4">
        </video>
    </div>
    <div class="video-overlay-text reveal" id="videoText">
        <h1>Transformasi Menuju Kampus Unggul</h1>
        <p>Setiap laporan Anda adalah langkah nyata menuju lingkungan belajar yang lebih aman, bersih, dan produktif.</p>
    </div>
</section>

{{-- Target 5R, 7S, K3 Section --}}
<section class="landing-features" style="padding: 100px 0; background: var(--bg-body);">
    <div class="container text-center reveal">
        <h2 style="font-size: 2.5rem; margin-bottom: 16px;">Fokus Perbaikan</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 60px;">Membangun budaya disiplin dan keselamatan kerja berstandar industri melalui tiga pilar utama.</p>
        
        <div class="grid grid-3 gap-6">
            <div class="card reveal" style="padding: 40px 24px; text-align: center; border: 1px solid var(--border-light); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 80px; height: 80px; background: rgba(12,107,175,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: var(--primary);">
                    <h2 style="margin:0; font-size: 2rem;">5R</h2>
                </div>
                <h3>Budaya 5R</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Ringkas, Rapi, Resik, Rawat, Rajin. Mengurangi pemborosan dan meningkatkan efisiensi area kerja.</p>
            </div>
            <div class="card reveal" style="padding: 40px 24px; text-align: center; border: 1px solid var(--border-light); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 80px; height: 80px; background: rgba(16,185,129,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #10b981;">
                    <h2 style="margin:0; font-size: 2rem;">7S</h2>
                </div>
                <h3>Sistem 7S</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Pengembangan dari 5S dengan tambahan Safety & Spirit untuk lingkungan akademis dan industri.</p>
            </div>
            <div class="card reveal" style="padding: 40px 24px; text-align: center; border: 1px solid var(--border-light); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 80px; height: 80px; background: rgba(239,68,68,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #ef4444;">
                    <h2 style="margin:0; font-size: 2rem;">K3</h2>
                </div>
                <h3>Keselamatan (K3)</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Kesehatan dan Keselamatan Kerja. Mencegah kecelakaan dan memastikan operasional lab yang aman.</p>
            </div>
        </div>
    </div>
</section>

{{-- How it works (Timeline) --}}
<section class="landing-features" id="features" style="padding: 100px 0; background: #fff;">
    <div class="container">
        <h2 class="reveal" style="text-align: center; font-size: 2.5rem; margin-bottom: 60px;">Alur Kerja Sistem</h2>
        <div class="timeline">
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3 style="color: var(--primary); margin-bottom: 8px;">1. Temukan & Laporkan</h3>
                    <p style="color: var(--text-muted);">Melihat kabel berantakan? Alat lab rusak? Foto dan submit langsung melalui dashboard Anda dalam hitungan detik.</p>
                </div>
            </div>
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3 style="color: var(--primary); margin-bottom: 8px;">2. Validasi Manajemen</h3>
                    <p style="color: var(--text-muted);">Laporan secara otomatis masuk ke antrean manajer K3 atau Kepala Bengkel untuk di-review dan disetujui.</p>
                </div>
            </div>
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3 style="color: var(--primary); margin-bottom: 8px;">3. Tindak Lanjut Ekskusi</h3>
                    <p style="color: var(--text-muted);">Admin/Manajer menugaskan tim perbaikan dan memantau status 'Dalam Proses' hingga masalah dinyatakan 'Selesai'.</p>
                </div>
            </div>
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3 style="color: var(--primary); margin-bottom: 8px;">4. Dapatkan Reward</h3>
                    <p style="color: var(--text-muted);">Setiap kontribusi dihargai. Anda mendatkan Poin dari pelaporan dan menjadi yang teratas di Leaderboard Kampus!</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Top 10 Leaderboard --}}
<section class="landing-features" style="padding: 80px 0; background: var(--bg);">
    <div class="container reveal">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 16px;">Top 10 Leaderboard🏆</h2>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 40px;">Pahlawan perbaikan kampus dengan poin tertinggi bulan ini.</p>
        
        <div class="card" style="max-width: 900px; margin: 0 auto; overflow: hidden; border-radius: var(--radius-lg); box-shadow: var(--shadow);">
            <div class="table-wrapper" style="margin: 0;">
                <table class="table" style="margin: 0;">
                    <thead style="background: var(--bg-surface);">
                        <tr>
                            <th style="width: 60px; text-align: center;">Peringkat</th>
                            <th>Nama</th>
                            <th>Tipe / Jabatan</th>
                            <th style="text-align: right;">Total Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topUsers as $index => $user)
                        <tr style="transition: background 0.2s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                            <td style="text-align: center;">
                                @if($index === 0)
                                    <div style="width: 32px; height: 32px; background: #fbbf24; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: bold; box-shadow: 0 4px 10px rgba(251,191,36,0.4);">1</div>
                                @elseif($index === 1)
                                    <div style="width: 32px; height: 32px; background: #94a3b8; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: bold; box-shadow: 0 4px 10px rgba(148,163,184,0.4);">2</div>
                                @elseif($index === 2)
                                    <div style="width: 32px; height: 32px; background: #b45309; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-weight: bold; box-shadow: 0 4px 10px rgba(180,83,9,0.4);">3</div>
                                @else
                                    <div style="font-weight: 600; color: var(--text-muted);">{{ $index + 1 }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text);">{{ $user->full_name }}</div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div>
                                        <span class="badge {{ match($user->user_type) { 'mahasiswa' => 'badge-info', 'dosen' => 'badge-warning', default => 'badge-neutral' } }}" style="font-size: 0.7rem; padding: 2px 8px;">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </div>
                                    @if($user->user_type === 'mahasiswa' && $user->gedung)
                                        <div class="text-sm text-muted" style="font-size: 0.75rem;">{{ $user->gedung }}</div>
                                    @elseif($user->user_type === 'dosen' && $user->jabatan)
                                        <div class="text-sm text-muted" style="font-size: 0.75rem;">{{ $user->jabatan }}</div>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">{{ number_format($user->total_points) }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                                <i data-lucide="award" style="width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>Belum ada data poin terkumpul. Jadilah yang pertama!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(count($topUsers) > 0)
            <div style="text-align: center; padding: 16px; border-top: 1px solid var(--border-light); background: var(--bg-surface);">
                <a href="{{ route('leaderboard') }}" style="font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
                    Lihat Leaderboard Lengkap <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="landing-footer" style="background: var(--bg-surface); padding: 40px 0; border-top: 1px solid var(--border-light); text-align: center;">
    <div class="container reveal">
        <img src="{{ asset('images/polman.png') }}" alt="Polman" style="height: 60px; margin-bottom: 24px; opacity: 0.8;">
        <h3 style="margin-bottom: 12px;">Sistem Pelaporan Improvement</h3>
        <p style="color: var(--text-muted); max-width: 500px; margin: 0 auto 24px;">Transformasi Kampus Polman yang lebih Baik Berawal dari Kepedulian Anda Hari Ini.</p>
        <div style="display:flex; justify-content:center; gap: 24px; margin-bottom: 24px;">
            <a href="{{ route('leaderboard') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Leaderboard</a>
            <a href="{{ route('reports.public') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Laporan</a>
            <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Login</a>
        </div>
        <p class="text-sm text-muted">&copy; {{ date('Y') }} Politeknik Manufaktur Bandung. All rights reserved.</p>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Nav shadow on scroll
    window.addEventListener('scroll', () => {
        if(window.scrollY > 20) {
            document.getElementById('landingNav').style.boxShadow = '0 4px 20px rgba(0,0,0,0.05)';
        } else {
            document.getElementById('landingNav').style.boxShadow = 'none';
        }
    });

    // Reveal animations using Intersection Observer
    const revealElements = document.querySelectorAll('.reveal');
    const exposeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.15 });

    revealElements.forEach(el => exposeObserver.observe(el));

    // Video Parallax logic
    const videoSection = document.getElementById('videoParallax');
    const videoContainer = document.getElementById('videoContainer');
    
    // Scale video on scroll
    window.addEventListener('scroll', () => {
        const rect = videoSection.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        
        // Cek jika bagian video terlihat di viewport
        if (rect.top <= windowHeight && rect.bottom >= 0) {
            // Hitung seberapa jauh user telah scroll masuk ke area video
            // rect.top mulai dari windowHeight hingga turun ke angka negatif
            const scrollPercent = 1 - (rect.top / windowHeight);
            
            // Limit percentage antara 0 - 1
            const limitedPercent = Math.max(0, Math.min(1, scrollPercent));
            
            // Awal ukuran 60%, max target 100%
            let newSize = 60 + (40 * limitedPercent);
            let newRadius = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--radius-xl'));
            newRadius = newRadius * (1 - limitedPercent); // radius jadi 0 saat full
            
            videoContainer.style.width = newSize + '%';
            videoContainer.style.height = newSize + '%';
            videoContainer.style.borderRadius = newRadius + 'px';
        }
    });

});
</script>
@endsection
