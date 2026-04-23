@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.guest')
@section('title', 'POLMAN 375')

@section('content')
<div id="landing-root" class="min-h-screen text-slate-100" style="background-color: #00334E; color: #E8E8E8;">
    <nav id="landingNav" class="sticky top-0 z-50 w-full backdrop-blur-md" style="background-color: rgba(0,51,78,0.75); border-bottom: 1px solid rgba(20,83,116,0.6);">
        <div class="max-w-screen-xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/polman.png') }}" alt="Logo POLMAN" class="h-10 w-auto">
                <span class="font-bold tracking-wider text-xl" style="color: #E8E8E8;">POLMAN 375</span>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a href="#hero1" class="text-sm transition-colors nav-link" style="color: rgba(232,232,232,0.7);">HOME</a>
                <a href="#hero2" class="text-sm transition-colors nav-link" style="color: rgba(232,232,232,0.7);">DETAIL</a>
                <a href="#hero3" class="text-sm transition-colors nav-link" style="color: rgba(232,232,232,0.7);">STANDAR</a>
                <a href="#hero4" class="text-sm transition-colors nav-link" style="color: rgba(232,232,232,0.7);">ALUR & REWARD</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm transition-colors nav-link" style="color: #E8E8E8;">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold rounded-full transition-all btn-primary" style="background-color: #5588A3; color: #E8E8E8; box-shadow: 0 0 15px rgba(85,136,163,0.35);">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <main class="pt-24">
        {{-- HERO 1 --}}
        <section id="hero1" class="relative z-0 overflow-hidden px-4 py-32 transition-colors duration-500" style="background-color: #00334E; color: #E8E8E8;">
            <div class="pointer-events-none absolute left-0 top-0 h-[420px] w-[420px] rounded-full blur-3xl" style="background-color: rgba(85,136,163,0.12);"></div>
            <div class="w-full max-w-[1920px] mx-auto grid gap-10 lg:grid-cols-2 items-center">
                <div class="space-y-8 relative z-10">
                    <p class="text-sm uppercase tracking-[0.35em]" style="color: #5588A3;">Platform Pelaporan Terintegrasi</p>
                    <h1 class="text-[clamp(2rem,10vw,5rem)] font-black leading-tight tracking-tight font-sans" style="color: #E8E8E8;">
                        Sistem Pelaporan <span style="color: #4ade80;">K3,</span> <span style="color: #f87171;">7S,</span> <span style="color: #E8E8E8;">dan</span> <span style="color: #5588A3;">5R</span>
                    </h1>
                    <p class="max-w-xl text-lg leading-relaxed hero-copy" style="color: rgba(232,232,232,0.6);">Sistem pelaporan modern untuk mengumpulkan, memverifikasi, dan menindaklanjuti temuan K3, 7S, dan 5R dengan visualisasi yang jelas dan responsif.</p>
                    <div class="grid gap-4 sm:grid-cols-3 relative z-20">
                        <a href="{{ route('register') }}" class="btn-hero rounded-[12px] px-6 py-3 text-sm font-black uppercase tracking-[0.18em] transition-all" style="background-color: #5588A3; color: #E8E8E8; box-shadow: 0 8px 24px rgba(85,136,163,0.3);">Mulai Dari Sini</a>
                        <a href="/create/publik/reports" class="btn-hero rounded-[12px] px-6 py-3 text-sm font-semibold uppercase tracking-[0.18em] transition-all btn-outline" style="border: 1px solid rgba(85,136,163,0.5); color: #E8E8E8; background: transparent;">Lapor sebagai Publik</a>
                        <a href="#hero3" class="btn-hero rounded-[12px] px-6 py-3 text-sm font-semibold uppercase tracking-[0.18em] transition-all btn-outline" style="border: 1px solid rgba(85,136,163,0.5); color: #E8E8E8; background: transparent;">Pelajari Lebih Lanjut</a>
                    </div>
                </div>

                <div class="slider-container relative overflow-hidden rounded-[28px] p-5 backdrop-blur-xl" style="border: 1px solid rgba(20,83,116,0.6); background-color: rgba(20,83,116,0.25); box-shadow: 0 40px 90px rgba(85,136,163,0.15);">
@forelse($activeWarnings as $index => $warning)
                        <div class="slider-card relative min-h-[420px] overflow-hidden rounded-[24px] bg-cover bg-center shadow-2xl {{ $index === 0 ? '' : 'hidden' }}" style="background-image: url('{{ $warning->image_url }}'); box-shadow: 0 20px 60px rgba(0,51,78,0.5);">
                            <div class="absolute inset-0 backdrop-blur-xl" style="background-color: rgba(0,51,78,0.72);"></div>
                            <div class="slider-content relative z-10 flex h-full flex-col justify-between p-8" style="color: #E8E8E8;">
                                <div class="space-y-4">
                                    <p class="slider-label text-sm uppercase tracking-[0.28em]" style="color: #5588A3;">{{ strtoupper($warning->severity_label ?? 'PERINGATAN UMUM') }}</p>
                                    <h2 class="slider-title text-3xl font-bold leading-tight" style="color: #E8E8E8;">{{ $warning->title }}</h2>
                                    <p class="slider-description max-w-xl" style="color: rgba(232,232,232,0.65);">{{ Str::limit($warning->description, 150) }}</p>
                                </div>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="slider-meta text-sm" style="color: rgba(232,232,232,0.5);">Slide <span class="slider-index">{{ $index + 1 }}</span> dari <span class="slider-total">{{ $activeWarnings->count() }}</span></div>
                                    <div class="flex gap-2">
                                        <button type="button" data-action="prev" class="slider-nav rounded-[12px] px-4 py-2 text-sm transition-all" style="border: 1px solid rgba(20,83,116,0.8); background-color: rgba(0,51,78,0.7); color: #E8E8E8;">Prev</button>
                                        <button type="button" data-action="next" class="slider-nav rounded-[12px] px-4 py-2 text-sm transition-all" style="border: 1px solid rgba(20,83,116,0.8); background-color: rgba(0,51,78,0.7); color: #E8E8E8;">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="slider-card relative min-h-[420px] overflow-hidden rounded-[24px] bg-cover bg-center shadow-2xl" style="background-image: url('https://images.unsplash.com/photo-1519337265831-281ec6cc8514?auto=format&fit=crop&w=1200&q=80'); box-shadow: 0 20px 60px rgba(0,51,78,0.5);">
                            <div class="absolute inset-0 backdrop-blur-xl" style="background-color: rgba(0,51,78,0.72);"></div>
                            <div class="slider-content relative z-10 flex h-full flex-col justify-between p-8" style="color: #E8E8E8;">
                                <div class="space-y-4">
                                    <p class="slider-label text-sm uppercase tracking-[0.28em]" style="color: #5588A3;">Sistem Pelaporan K3, 7S, dan 5R</p>
                                    <h2 class="slider-title text-3xl font-bold leading-tight" style="color: #E8E8E8;">Sistem Pelaporan K3, 7S, dan 5R</h2>
                                    <p class="slider-description max-w-xl" style="color: rgba(232,232,232,0.65);">Lapor temuan lapangan dengan mudah, pantau tindak lanjut, dan bangun budaya keselamatan kampus.</p>
                                </div>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="slider-meta text-sm" style="color: rgba(232,232,232,0.5);">Slide <span class="slider-index">1</span> dari <span class="slider-total">1</span></div>
                                    <div class="flex gap-2">
                                        <button type="button" data-action="prev" class="slider-nav rounded-[12px] px-4 py-2 text-sm transition-all" style="border: 1px solid rgba(20,83,116,0.8); background-color: rgba(0,51,78,0.7); color: #E8E8E8;">Prev</button>
                                        <button type="button" data-action="next" class="slider-nav rounded-[12px] px-4 py-2 text-sm transition-all" style="border: 1px solid rgba(20,83,116,0.8); background-color: rgba(0,51,78,0.7); color: #E8E8E8;">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- {{-- HERO 2 --}}
        <section id="hero-2-section" class="hero2-section relative z-5 min-h-screen overflow-hidden px-4 py-32 transition-colors duration-700" style="background-color: #00334E; color: #E8E8E8;">
            <div class="hero-2-container w-full max-w-[1920px] mx-auto flex flex-col gap-8 items-center">
                <div id="hero-2-detail-panel" class="w-full max-w-5xl rounded-3xl p-10 min-h-[320px] flex flex-col justify-center backdrop-blur-xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(20,83,116,0.5); box-shadow: 0 40px 80px rgba(0,51,78,0.3);">
                    <div id="hero2-panel" class="hero2-panel relative z-30">
                        <div id="hero2-drop-instruction" class="hero2-drop-instruction mb-8 rounded-[1.75rem] p-6 text-center" style="border: 1px dashed rgba(85,136,163,0.4); background-color: rgba(20,83,116,0.2); color: rgba(232,232,232,0.6);">
                            Pilih salah satu kategori untuk melihat detail dan contoh laporan terkait.
                        </div>
                        <div id="hero2-detail" class="hero2-detail space-y-6 opacity-100" style="color: #E8E8E8;">
                            <p id="hero2-category" class="text-sm uppercase tracking-[0.35em]" style="color: #5588A3;">Eksplorasi Budaya Kerja</p>
                            <h2 id="hero2-heading" class="text-5xl font-black" style="color: #E8E8E8;">Pilih Kartu untuk Mulai</h2>
                            <p id="hero2-description" class="max-w-2xl text-lg leading-relaxed" style="color: rgba(232,232,232,0.6);">Eksplorasi 5R, 7S, dan K3 dalam satu antarmuka interaktif. Pilih kategori di bawah untuk melihat detail standar keselamatan.</p>
                            <div id="hero2-examples" class="grid gap-4 sm:grid-cols-2">
                                <div class="hero2-example rounded-[1.75rem] p-5 opacity-0 translate-y-6" style="border: 1px solid rgba(20,83,116,0.5); background-color: rgba(20,83,116,0.3); color: #E8E8E8;"></div>
                                <div class="hero2-example rounded-[1.75rem] p-5 opacity-0 translate-y-6" style="border: 1px solid rgba(20,83,116,0.5); background-color: rgba(20,83,116,0.3); color: #E8E8E8;"></div>
                                <div class="hero2-example rounded-[1.75rem] p-5 opacity-0 translate-y-6" style="border: 1px solid rgba(20,83,116,0.5); background-color: rgba(20,83,116,0.3); color: #E8E8E8;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-5xl items-stretch">
                    <button data-color="#083344" data-title="BUDAYA AREA RAPI (5R)" data-desc="Implementasi 5R memastikan setiap alat berada di tempatnya untuk efisiensi tinggi." class="kategori-card hero2-card flex-1 p-6 rounded-2xl cursor-pointer transition-all" style="border: 1px solid rgba(20,83,116,0.5); background-color: rgba(20,83,116,0.25);">
                        <span class="text-xs font-bold" style="color: #5588A3;">5R</span>
                        <h3 class="text-xl font-bold mt-4" style="color: #E8E8E8;">Budaya Area Rapi</h3>
                        <p class="mt-3 text-sm" style="color: rgba(232,232,232,0.6);">Tata letak rapi dan efisien menyokong operasi yang lebih cepat dan aman.</p>
                    </button>
                    <button data-color="#450a0a" data-title="BUDAYA TERATUR (7S)" data-desc="Disiplin dan standar kerja yang kuat membuat proses lebih konsisten." class="kategori-card hero2-card flex-1 p-6 rounded-2xl cursor-pointer transition-all" style="border: 1px solid rgba(20,83,116,0.5); background-color: rgba(20,83,116,0.25);">
                        <span class="text-xs font-bold" style="color: #f87171;">7S</span>
                        <h3 class="text-xl font-bold mt-4" style="color: #E8E8E8;">Budaya Teratur</h3>
                        <p class="mt-3 text-sm" style="color: rgba(232,232,232,0.6);">Disiplin dan standar kerja yang kuat membuat proses lebih konsisten.</p>
                    </button>
                    <button data-color="#064e3b" data-title="KESEHATAN & KESELAMATAN KERJA (K3)" data-desc="Keselamatan kerja menjadi prioritas utama dengan pelaporan dan tindak lanjut cepat." class="kategori-card hero2-card flex-1 p-6 rounded-2xl cursor-pointer transition-all" style="border: 1px solid rgba(20,83,116,0.5); background-color: rgba(20,83,116,0.25);">
                        <span class="text-xs font-bold" style="color: #4ade80;">K3</span>
                        <h3 class="text-xl font-bold mt-4" style="color: #E8E8E8;">Keamanan Kerja</h3>
                        <p class="mt-3 text-sm" style="color: rgba(232,232,232,0.6);">Keselamatan menjadi prioritas utama dengan pelaporan dan tindak lanjut cepat.</p>
                    </button>
                </div>
            </div>
        </section> -->
        {{-- HERO 3 — Edukasi K3 / 5R / 7S --}}
        <section id="hero3" class="relative z-10 px-4 py-32 overflow-hidden transition-colors duration-500" style="background-color: #00334E; border-top: 1px solid rgba(20,83,116,0.5);">

            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute right-0 top-20 h-[500px] w-[500px] rounded-full blur-3xl opacity-10" style="background: radial-gradient(circle, #5588A3, transparent 70%);"></div>

            <div class="w-full max-w-6xl mx-auto">

                {{-- Header --}}
                <div class="text-center mb-16 hero3-header">
                    <p class="text-sm uppercase tracking-[0.4em] mb-3" style="color: #5588A3;">Standar Keselamatan Kampus</p>
                    <h2 class="text-5xl font-black" style="color: #E8E8E8;">Kenali Sistem Pelaporan Kami</h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg" style="color: rgba(232,232,232,0.6);">Tiga pilar utama yang menjadi landasan budaya keselamatan dan kerapian di POLMAN Bandung.</p>
                </div>

                {{-- Tab Navigation --}}
                <div class="flex justify-center gap-3 mb-12 hero3-tabs">
                    <button class="h3-tab active px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all" data-tab="k3" style="background-color: #5588A3; color: #00334E;">
                        K3
                    </button>
                    <button class="h3-tab px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all" data-tab="5r" style="background-color: rgba(20,83,116,0.3); color: rgba(232,232,232,0.6); border: 1px solid rgba(20,83,116,0.5);">
                        5R
                    </button>
                    <button class="h3-tab px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all" data-tab="7s" style="background-color: rgba(20,83,116,0.3); color: rgba(232,232,232,0.6); border: 1px solid rgba(20,83,116,0.5);">
                        7S
                    </button>
                </div>

                {{-- Tab Panels --}}
                <div id="h3-panel-k3" class="h3-panel grid lg:grid-cols-2 gap-8 items-start">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.3);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-widest mb-1" style="color: #4ade80;">Keselamatan & Kesehatan Kerja</p>
                                <h3 class="text-3xl font-black" style="color: #E8E8E8;">K3</h3>
                            </div>
                        </div>
                        <p class="text-base leading-relaxed" style="color: rgba(232,232,232,0.7);">
                            K3 (Keselamatan dan Kesehatan Kerja) adalah sistem perlindungan bagi seluruh civitas akademika dari risiko kecelakaan, penyakit akibat kerja, dan bahaya di lingkungan workshop maupun laboratorium POLMAN Bandung.
                        </p>
                        <p class="text-base leading-relaxed" style="color: rgba(232,232,232,0.7);">
                            Setiap temuan potensi bahaya wajib dilaporkan agar tim terkait dapat segera mengambil tindakan pencegahan sebelum insiden terjadi.
                        </p>
                        <div class="pt-2">
                            <p class="text-xs uppercase tracking-widest mb-3" style="color: rgba(232,232,232,0.4);">Dasar Hukum</p>
                            <span class="px-3 py-1.5 text-xs rounded-full mr-2" style="background-color: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80;">UU No.1/1970</span>
                            <span class="px-3 py-1.5 text-xs rounded-full mr-2" style="background-color: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80;">PP No.50/2012</span>
                            <span class="px-3 py-1.5 text-xs rounded-full" style="background-color: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80;">ISO 45001</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-xs uppercase tracking-widest mb-4" style="color: rgba(232,232,232,0.4);">Contoh Kasus yang Dapat Dilaporkan</p>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(74,222,128,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">APD Tidak Tersedia / Rusak</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Helm, sarung tangan, kacamata pelindung, atau sepatu safety yang hilang, rusak, atau tidak sesuai standar di area kerja.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(74,222,128,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Mesin / Peralatan Berbahaya</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Guard pengaman mesin yang terlepas, kabel listrik terbuka, atau peralatan dengan kondisi tidak layak pakai.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(74,222,128,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Potensi Kebakaran / APAR Kadaluarsa</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Bahan mudah terbakar tidak tersimpan dengan benar, APAR kosong atau kadaluarsa, jalur evakuasi terhalang.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(74,222,128,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Near Miss / Hampir Celaka</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Kejadian yang hampir menyebabkan kecelakaan namun tidak menimbulkan cedera — sangat penting untuk dicatat dan dicegah.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="h3-panel-5r" class="h3-panel hidden grid lg:grid-cols-2 gap-8 items-start">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(0,191,255,0.15); border: 1px solid rgba(0,191,255,0.3);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00BFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-widest mb-1" style="color: #00BFFF;">Ringkas · Rapi · Resik · Rawat · Rajin</p>
                                <h3 class="text-3xl font-black" style="color: #E8E8E8;">5R</h3>
                            </div>
                        </div>
                        <p class="text-base leading-relaxed" style="color: rgba(232,232,232,0.7);">
                            5R adalah metodologi manajemen tempat kerja yang bertujuan menciptakan lingkungan yang terorganisir, bersih, dan efisien. Diterapkan secara konsisten, 5R meningkatkan produktivitas dan keselamatan secara bersamaan.
                        </p>
                        <div class="grid grid-cols-1 gap-3 pt-2">
                            @foreach([['R','Ringkas','Pisahkan barang yang perlu dan tidak perlu'],['R','Rapi','Tempatkan barang pada tempat yang semestinya'],['R','Resik','Bersihkan area kerja secara rutin'],['R','Rawat','Pertahankan kondisi yang sudah baik'],['R','Rajin','Jadikan 5R sebagai kebiasaan sehari-hari']] as $i => $r)
                            <div class="flex items-center gap-3 p-3 rounded-xl" style="background-color: rgba(0,191,255,0.1); border: 1px solid rgba(0,191,255,0.2);">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0" style="background-color: #00BFFF; color: #FFFFFF;">{{ $i+1 }}</span>
                                <div>
                                    <span class="font-bold text-sm mr-2" style="color: #00BFFF;">{{ $r[1] }}</span>
                                    <span class="text-xs" style="color: rgba(232,232,232,0.55);">— {{ $r[2] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-xs uppercase tracking-widest mb-4" style="color: rgba(232,232,232,0.4);">Contoh Kasus yang Dapat Dilaporkan</p>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(0,191,255,0.25);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Area Kerja Berantakan</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Perkakas, material, atau sisa produksi berserakan di lantai workshop atau meja kerja tanpa pengelompokan yang jelas.</p>
                                </div>
                            </div>
                        </div>
                       <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(0,191,255,0.25);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Label / Penanda Tidak Terpasang</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Rak penyimpanan alat, laci, atau area khusus tidak memiliki label identifikasi yang jelas sehingga menyulitkan pencarian.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(0,191,255,0.25);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Barang Usang Menumpuk</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Peralatan rusak, barang tidak terpakai, atau dokumen lama yang memenuhi ruang dan mengganggu alur kerja.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(0,191,255,0.25);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Toilet / Pantry Tidak Bersih</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Fasilitas umum yang tidak terawat dan tidak ada jadwal pembersihan rutin yang terstruktur.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="h3-panel-7s" class="h3-panel hidden grid lg:grid-cols-2 gap-8 items-start">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-widest mb-1" style="color: #f87171;">Sort · Set · Shine · Standardize · Sustain · Safety · Spirit</p>
                                <h3 class="text-3xl font-black" style="color: #E8E8E8;">7S</h3>
                            </div>
                        </div>
                        <p class="text-base leading-relaxed" style="color: rgba(232,232,232,0.7);">
                            7S merupakan pengembangan dari 5S/5R dengan menambahkan dimensi Safety (Keselamatan) dan Spirit (Semangat). Pendekatan ini membangun budaya organisasi yang tidak hanya rapi, tetapi juga aman dan penuh motivasi.
                        </p>
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            @foreach([['Sort','Pilah'],['Set in Order','Tata'],['Shine','Bersihkan'],['Standardize','Standarisasi'],['Sustain','Pertahankan'],['Safety','Keselamatan'],['Spirit','Semangat']] as $i => $s)
                            <div class="flex items-center gap-2 p-2.5 rounded-xl" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(20,83,116,0.4);">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0" style="background-color: rgba(248,113,113,0.2); color: #f87171;">{{ $i+1 }}</span>
                                <div>
                                    <span class="text-xs font-bold" style="color: #E8E8E8;">{{ $s[0] }}</span>
                                    <span class="text-xs block" style="color: rgba(232,232,232,0.45);">{{ $s[1] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-xs uppercase tracking-widest mb-4" style="color: rgba(232,232,232,0.4);">Contoh Kasus yang Dapat Dilaporkan</p>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(248,113,113,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">SOP Tidak Diikuti</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Prosedur operasional standar yang ada di papan informasi diabaikan atau tidak diperbarui sesuai kondisi terkini.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(248,113,113,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Visual Management Buruk</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Marka lantai aus, papan informasi kosong, atau rambu peringatan yang tidak terbaca di area produksi.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(248,113,113,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Audit 7S Terlewat</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Jadwal audit rutin tidak terlaksana atau hasil audit tidak ditindaklanjuti dalam waktu yang ditetapkan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(20,83,116,0.25); border: 1px solid rgba(248,113,113,0.2);">
                            <div class="flex items-start gap-3">
                                <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                <div>
                                    <p class="font-semibold text-sm mb-1" style="color: #E8E8E8;">Lingkungan Kerja Tidak Kondusif</p>
                                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Pencahayaan tidak memadai, ventilasi buruk, atau kondisi yang menurunkan semangat dan fokus kerja.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- HERO 4 — Alur Penggunaan Web --}}
        <section id="hero4" class="relative z-20 px-4 py-32 overflow-hidden transition-colors duration-500" style="background-color: #00334E; border-top: 1px solid rgba(20,83,116,0.5);">

            <div class="pointer-events-none absolute left-0 bottom-0 h-[400px] w-[400px] rounded-full blur-3xl opacity-10" style="background: radial-gradient(circle, #5588A3, transparent 70%);"></div>

            <div class="w-full max-w-6xl mx-auto">

                {{-- Header --}}
                <div class="text-center mb-20 hero4-header">
                    <p class="text-sm uppercase tracking-[0.4em] mb-3" style="color: #5588A3;">Cara Kerja Sistem</p>
                    <h2 class="text-5xl font-black" style="color: #E8E8E8;">Alur Pelaporan & Reward</h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg" style="color: rgba(232,232,232,0.6);">Dari temuan di lapangan hingga pencapaian poin — semua terekam dan terstruktur dalam satu sistem.</p>
                </div>

                {{-- Flow Steps --}}
                <div class="relative">

                    {{-- Connector line desktop --}}
                    <div class="hidden lg:block absolute top-12 left-0 right-0 h-0.5 z-0" style="background: linear-gradient(to right, transparent 8%, rgba(20,83,116,0.5) 15%, rgba(20,83,116,0.5) 85%, transparent 92%);">
                        <div id="flow-progress-line" class="h-full w-0 transition-none" style="background: linear-gradient(to right, #5588A3, rgba(85,136,163,0.3));"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 relative z-10">

                        {{-- Step 1 --}}
                        <div class="flow-step text-center" data-step="1">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(20,83,116,0.3); border: 1px solid rgba(20,83,116,0.5);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #5588A3; box-shadow: 0 0 20px rgba(85,136,163,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#5588A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: #5588A3; color: #00334E;">1</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #E8E8E8;">Buat Laporan</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(232,232,232,0.55);">Pelapor mengisi formulir temuan K3, 5R, atau 7S lengkap dengan foto dan lokasi kejadian.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(85,136,163,0.15); border: 1px solid rgba(85,136,163,0.3); color: #5588A3;">Pelapor</div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="flow-step text-center" data-step="2">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(20,83,116,0.3); border: 1px solid rgba(20,83,116,0.5);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #5588A3; box-shadow: 0 0 20px rgba(85,136,163,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#5588A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: rgba(20,83,116,0.8); color: #E8E8E8; border: 1px solid rgba(85,136,163,0.4);">2</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #E8E8E8;">Verifikasi</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(232,232,232,0.55);">Penanggung jawab area meninjau laporan, memvalidasi temuan, dan menetapkan prioritas tindakan.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(85,136,163,0.15); border: 1px solid rgba(85,136,163,0.3); color: #5588A3;">Penanggung Jawab</div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="flow-step text-center" data-step="3">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(20,83,116,0.3); border: 1px solid rgba(20,83,116,0.5);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #5588A3; box-shadow: 0 0 20px rgba(85,136,163,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#5588A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M6.34 17.66l-1.41 1.41M20 12h-2M6 12H4M17.66 17.66l-1.41-1.41M6.34 6.34L4.93 4.93M12 20v-2M12 6V4"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: rgba(20,83,116,0.8); color: #E8E8E8; border: 1px solid rgba(85,136,163,0.4);">3</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #E8E8E8;">Tindak Lanjut</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(232,232,232,0.55);">Tim teknis melaksanakan perbaikan, mendokumentasikan progress, dan memperbarui status laporan secara real-time.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(85,136,163,0.15); border: 1px solid rgba(85,136,163,0.3); color: #5588A3;">Tim Teknis</div>
                        </div>

                        {{-- Step 4 --}}
                        <div class="flow-step text-center" data-step="4">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(20,83,116,0.3); border: 1px solid rgba(20,83,116,0.5);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #f59e0b; box-shadow: 0 0 20px rgba(245,158,11,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: #f59e0b; color: #00334E;">4</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #E8E8E8;">Poin & Achievement</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(232,232,232,0.55);">Pelapor mendapat poin otomatis, naik peringkat di leaderboard, dan membuka achievement eksklusif.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b;">Reward Otomatis</div>
                        </div>

                    </div>
                </div>

                <!-- {{-- Achievement showcase --}}
                <div class="mt-20 grid grid-cols-2 lg:grid-cols-4 gap-4 hero4-achievements">
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(20,83,116,0.4);">
                        <div class="text-2xl mb-2">🥇</div>
                        <p class="font-bold text-sm" style="color: #E8E8E8;">Pelapor Perdana</p>
                        <p class="text-xs mt-1" style="color: rgba(232,232,232,0.45);">Laporan pertama berhasil</p>
                    </div>
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(20,83,116,0.4);">
                        <div class="text-2xl mb-2">🔥</div>
                        <p class="font-bold text-sm" style="color: #E8E8E8;">Konsisten 7 Hari</p>
                        <p class="text-xs mt-1" style="color: rgba(232,232,232,0.45);">Laporan 7 hari berturut-turut</p>
                    </div>
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(20,83,116,0.4);">
                        <div class="text-2xl mb-2">🛡️</div>
                        <p class="font-bold text-sm" style="color: #E8E8E8;">Guardian K3</p>
                        <p class="text-xs mt-1" style="color: rgba(232,232,232,0.45);">10 laporan K3 diverifikasi</p>
                    </div>
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(20,83,116,0.4);">
                        <div class="text-2xl mb-2">⭐</div>
                        <p class="font-bold text-sm" style="color: #E8E8E8;">Top Contributor</p>
                        <p class="text-xs mt-1" style="color: rgba(232,232,232,0.45);">Masuk 3 besar leaderboard</p>
                    </div>
                </div> -->

                {{-- Leaderboard --}}
                <div class="mt-16">
                    <div class="mb-8 text-center">
                        <p class="text-sm uppercase tracking-[0.4em]" style="color: #5588A3;">Leaderboard Dinamis</p>
                        <h3 class="mt-2 text-3xl font-black" style="color: #E8E8E8;">LEADERBOARD PER PERIODE</h3>
                    </div>
<livewire:points.leaderboard :limit="5" />
                </div>

            </div>
        </section>
        {{-- FOOTER --}}
        <footer class="relative z-30 py-20 mt-20" style="background-color: #00334E; border-top: 1px solid rgba(20,83,116,0.4); color: rgba(232,232,232,0.6);">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-3 gap-12">
                <div>
                    <h3 class="font-bold mb-4" style="color: #E8E8E8;">POLMAN Safety System</h3>
                    <p class="text-sm" style="color: rgba(232,232,232,0.55);">Sistem pelaporan terintegrasi untuk menciptakan lingkungan kampus yang rapi, resik, dan aman bagi seluruh civitas akademika.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4" style="color: #E8E8E8;">Navigasi</h4>
                    <ul class="text-sm space-y-2" style="color: rgba(232,232,232,0.55);">
                        <li><a href="#" class="footer-link transition-colors">Dashboard Utama</a></li>
                        <li><a href="#" class="footer-link transition-colors">Standar 5R/7S</a></li>
                        <li><a href="#" class="footer-link transition-colors">Prosedur K3</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4" style="color: #E8E8E8;">Tautan Terkait</h4>
                    <div class="flex flex-col gap-3 text-sm" style="color: rgba(232,232,232,0.55);">
                        <a href="#" class="footer-link transition-colors">GitHub</a>
                        <a href="#" class="footer-link transition-colors">LinkedIn</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-12 pt-8 text-xs" style="border-top: 1px solid rgba(20,83,116,0.3); color: rgba(232,232,232,0.3);">
                &copy; 2026 TRIN POLMAN Bandung. Proyek Sistem Pelaporan Keselamatan.
            </div>
        </footer>
    </main>
</div>

@push('styles')
<style>
    :root {
        --polman-bg:        #00334E;
        --polman-border:    #145374;
        --polman-hover:     #5588A3;
        --polman-text:      #E8E8E8;
        --polman-surface:   rgba(20, 83, 116, 0.25);
        --polman-surface-2: rgba(20, 83, 116, 0.15);
    }

    /* Nav links */
    .nav-link:hover { color: var(--polman-hover) !important; }

    /* Buttons */
    .btn-primary:hover { background-color: #6899b4 !important; }
    .btn-outline:hover { border-color: var(--polman-hover) !important; color: var(--polman-hover) !important; }
    .btn-hero { transform: scale(1); transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .btn-hero:hover { transform: translateY(-2px); }

    /* Footer links */
    .footer-link:hover { color: var(--polman-hover) !important; }

    /* Slider */
    .slider-card { min-height: 420px; }
    .slider-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,51,78,0.1), rgba(0,51,78,0.75));
        pointer-events: none;
    }
    .slider-nav:hover { background-color: rgba(20,83,116,0.8) !important; }
    .slider-content { position: relative; z-index: 2; }

    /* Hero2 cards */
    .hero2-card { transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; touch-action: none; cursor: grab; }
    .hero2-card > * { pointer-events: none; }
    .hero2-card.dragging { cursor: grabbing; z-index: 20; }
    .hero2-card:hover { transform: translateY(-6px); border-color: rgba(85,136,163,0.6) !important; box-shadow: 0 16px 40px rgba(0,51,78,0.5); }

    /* Hero2 panel */
    .hero2-panel { transition: transform 0.4s ease, box-shadow 0.4s ease; }
    .hero2-panel.drop-target-active { border-color: var(--polman-hover) !important; box-shadow: 0 0 0 18px rgba(85,136,163,0.1) !important; }
    .hero2-drop-instruction { transition: opacity 0.25s ease, transform 0.25s ease; }
    .hero2-detail { transition: opacity 0.35s ease 0.1s; }
    .hero2-section { transition: background-color 0.45s ease; }

    /* Hero3 step cards */
    .step-card-v3 { transition: background-color 0.25s ease, border-color 0.25s ease, transform 0.2s ease; }
    .step-card-v3:hover { transform: translateX(4px); border-color: rgba(85,136,163,0.6) !important; background-color: rgba(85,136,163,0.15) !important; }
    .step-card-v3.active { border-color: rgba(85,136,163,0.6) !important; background-color: rgba(85,136,163,0.15) !important; }
    .step-card-v3.active .num { background-color: var(--polman-hover) !important; color: var(--polman-bg) !important; }

    /* Leaderboard rows */
    .leaderboard-row { opacity: 0; transform: translateY(18px); transition: background-color 0.2s ease; }
    .leaderboard-row:hover { background-color: rgba(85,136,163,0.08) !important; }

    /* Reveal animations */
    .reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.active { opacity: 1; transform: translateY(0); }
    .reveal-section { opacity: 0; transform: translateY(50px); transition: opacity 0.6s ease-out, transform 0.6s ease-out; will-change: opacity, transform; }
    .reveal-section.active { opacity: 1; transform: translateY(0); }

    /* Progress line */
    .progress-line { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; }

    /* Hero3 tabs */
    .h3-tab { transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease; }
    .h3-tab:hover { filter: brightness(1.1); }
    .h3-tab.active {
        background-color: #5588A3 !important;
        color: #00334E !important;
        border: none !important;
    }
    .h3-tab:not(.active) {
        background-color: rgba(20,83,116,0.3) !important;
        color: rgba(232,232,232,0.6) !important;
        border: 1px solid rgba(20,83,116,0.5) !important;
    }
    .h3-panel { transition: opacity 0.3s ease; }
    .h3-case-card { transition: transform 0.25s ease, border-color 0.25s ease; }
    .h3-case-card:hover { transform: translateX(4px); border-color: rgba(85,136,163,0.5) !important; }

    /* Hero4 flow */
    .flow-step { transition: transform 0.2s ease; }
    .flow-icon-wrap { transition: transform 0.25s ease; cursor: pointer; }
    .flow-icon-ring { transition: opacity 0.3s ease; }
    .flow-badge { transition: filter 0.2s ease; }
    .flow-badge:hover { filter: brightness(1.15); }
    .achievement-card { transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease; cursor: default; }
    .achievement-card:hover { transform: translateY(-5px) scale(1.03); border-color: rgba(85,136,163,0.5) !important; box-shadow: 0 12px 32px rgba(0,51,78,0.5); }

    /* Light mode overrides — sesuaikan jika dibutuhkan */
    html.light #landing-root { background-color: #f0f7fb !important; color: #003348 !important; }
    html.light #landingNav { background: rgba(240,247,251,0.92) !important; border-color: rgba(20,83,116,0.2) !important; }
    html.light .nav-link { color: rgba(0,51,78,0.7) !important; }
    html.light .nav-link:hover { color: var(--polman-hover) !important; }
    html.light #hero1, html.light .hero2-section, html.light #hero3, html.light #hero4, html.light footer { background-color: #f0f7fb !important; color: #003348 !important; }
    html.light .hero2-card, html.light #hero-2-detail-panel { background-color: rgba(20,83,116,0.08) !important; border-color: rgba(20,83,116,0.2) !important; }
    html.light .hero2-card h3, html.light #hero2-heading { color: #00334E !important; }
    html.light .hero2-card p, html.light #hero2-description, html.light .hero-copy { color: rgba(0,51,78,0.6) !important; }
    html.light .col-span-7, html.light #hero-2-detail-panel { background-color: rgba(20,83,116,0.08) !important; }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/hero-slider.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.h3-tab');
        const panels = document.querySelectorAll('.h3-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('data-tab');

                // 1. Reset semua tombol ke style tidak aktif
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.style.backgroundColor = 'rgba(20,83,116,0.3)';
                    t.style.color = 'rgba(232,232,232,0.6)';
                    t.style.border = '1px solid rgba(20,83,116,0.5)';
                });

                // 2. Set tombol yang diklik menjadi aktif
                // Sesuaikan warna background aktif berdasarkan data-tab jika ingin warna berbeda
                tab.classList.add('active');
                tab.style.backgroundColor = '#5588A3';
                tab.style.color = '#00334E';
                tab.style.border = 'none';

                // 3. Sembunyikan semua panel dan tampilkan yang sesuai
                panels.forEach(panel => {
                    panel.classList.add('hidden');
                    if (panel.id === `h3-panel-${target}`) {
                        panel.classList.remove('hidden');
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
