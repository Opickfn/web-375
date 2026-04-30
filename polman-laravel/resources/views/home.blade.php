@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.guest')
@section('title', 'POLMAN 375')

@section('content')
<div id="landing-root" class="min-h-screen text-slate-100 m-0 p-0" style="background-color: #00263a; color: #FFFFFF;">
    <nav id="landingNav" class="fixed top-0 left-0 right-0 z-50 w-full backdrop-blur-md transition-all duration-300" style="background-color: rgba(0,51,78,0.95); border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="max-w-screen-xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/polman.png') }}" alt="Logo POLMAN" class="h-10 w-auto bg-white/10 rounded-md p-1">
                <span class="font-bold tracking-wider text-xl" style="color: #FFFFFF;">POLMAN 375</span>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a href="#hero1" class="text-sm transition-colors nav-link" style="color: rgba(255,255,255,0.8);">HOME</a>
                <a href="#hero3" class="text-sm transition-colors nav-link" style="color: rgba(255,255,255,0.8);">STANDAR</a>
                <a href="#hero4" class="text-sm transition-colors nav-link" style="color: rgba(255,255,255,0.8);">ALUR & REWARD</a>
                <a href="#leaderboard" class="text-sm transition-colors nav-link" style="color: rgba(255,255,255,0.8);">LEADERBOARD</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm transition-colors nav-link" style="color: #FFFFFF;">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold rounded-full transition-all btn-primary" style="background-color: #f59e0b; color: #FFFFFF; box-shadow: 0 0 15px rgba(245,158,11,0.3);">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <main>
        {{-- HERO 1 (Full-Width Slideable) --}}
        <section id="hero1" class="relative w-full min-h-screen flex items-center justify-center overflow-hidden">
            <!-- Full Width Slider Backgrounds -->
            <div id="hero-slider-bg" class="absolute inset-0 z-0 w-full h-full min-h-[600px]">
                <!-- Slide 0 (Static/Default) -->
                <div class="hero-slide active absolute inset-0 w-full h-full transition-all duration-1000 ease-in-out">
                    <img src="{{ asset('images/polman.png') }}" alt="Background Default" class="w-full h-full object-cover opacity-100">
                    <div class="absolute inset-0 bg-[#00334E]/50 backdrop-blur-[2px]"></div>
                </div>
                
                <!-- Slide 1+ (Dynamic Warnings) -->
                @foreach($activeWarnings as $index => $warning)
                <div class="hero-slide absolute inset-0 w-full h-full transition-all duration-1000 ease-in-out opacity-0 translate-x-full">
                    <img src="{{ $warning->image_url }}" alt="{{ $warning->title }}" class="w-full h-full object-cover opacity-100">
                    <div class="absolute inset-0 bg-[#00334E]/50 backdrop-blur-[2px]"></div>
                </div>
                @endforeach
            </div>

            <!-- Content Overlay -->
            <div class="relative z-10 w-full max-w-screen-xl mx-auto px-6 pt-32 pb-24 grid lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-8 space-y-8 pm-fade-in-up">
                    <p class="text-sm md:text-base uppercase tracking-[0.35em] font-semibold text-[#10b981]">Platform Improvement Terintegrasi</p>
                    
                    <!-- Dynamic Text Container -->
                    <div id="hero-text-container" class="space-y-6">
                        <!-- Slide 0 Text (Default) -->
                        <div class="hero-text-item active space-y-6 transition-all duration-500">
                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black leading-tight tracking-tight font-sans text-white drop-shadow-xl">
                                Sistem Improvement <span class="text-[#10b981]">K3,</span> <span class="text-[#f59e0b]">7S,</span> <span class="text-white">dan</span> <span class="text-[#10b981]">5R</span>
                            </h1>
                            <p class="max-w-2xl text-lg md:text-xl leading-relaxed text-[#f8fafc] drop-shadow">
                                Suarakan temuan lapangan dengan mudah, pantau tindak lanjut, dan bangun budaya keselamatan kampus.
                            </p>
                        </div>

                        <!-- Slide 1+ Texts -->
                        @foreach($activeWarnings as $index => $warning)
                        <div class="hero-text-item hidden opacity-0 space-y-6 transition-all duration-500 absolute top-0 left-0">
                            <div class="inline-block px-4 py-1.5 rounded-full text-xs font-bold tracking-widest text-white uppercase bg-[#ef4444] shadow-[0_0_15px_rgba(239,68,68,0.5)]">
                                {{ strtoupper($warning->severity_label ?? 'PERINGATAN UMUM') }}
                            </div>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight font-sans text-white drop-shadow-xl">
                                {{ $warning->title }}
                            </h1>
                            <p class="max-w-2xl text-lg md:text-xl leading-relaxed text-[#f8fafc] drop-shadow">
                                {{ Str::limit($warning->description, 180) }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap gap-4 pt-8 relative z-20">
                        <a href="{{ route('register') }}" class="btn-hero rounded-[12px] px-8 py-4 text-sm font-black uppercase tracking-[0.18em] transition-all bg-[#f59e0b] text-white hover:bg-[#d97706] shadow-[0_8px_24px_rgba(245,158,11,0.4)]">Mulai Dari Sini</a>
                        <a href="/create/publik/reports" class="btn-hero rounded-[12px] px-8 py-4 text-sm font-semibold uppercase tracking-[0.18em] transition-all border border-white/50 text-white hover:bg-white hover:text-[#00334E] backdrop-blur-sm">Kontribusi Tanpa Login</a>
                    </div>
                </div>

                <div class="lg:col-span-4 flex justify-end h-full items-end lg:items-center pm-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="backdrop-blur-xl bg-white/10 border border-white/20 p-6 rounded-[24px] w-full max-w-sm shadow-[0_20px_60px_rgba(0,0,0,0.3)] relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-xs font-bold tracking-widest text-white/70 uppercase">Navigasi Slider</span>
                                <div class="text-sm font-bold text-white"><span id="hero-current-slide">1</span> / <span id="hero-total-slides">{{ $activeWarnings->count() + 1 }}</span></div>
                            </div>
                            <div class="flex gap-3 mb-6" id="hero-indicators">
                                <div class="h-1.5 flex-1 bg-white rounded-full transition-all slider-indicator active"></div>
                                @foreach($activeWarnings as $warning)
                                <div class="h-1.5 flex-1 bg-white/30 rounded-full transition-all slider-indicator"></div>
                                @endforeach
                            </div>
                            <div class="flex gap-3">
                                <button id="hero-prev" class="flex-1 h-12 rounded-[12px] flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all btn-hero">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                                </button>
                                <button id="hero-next" class="flex-1 h-12 rounded-[12px] flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all btn-hero">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- HERO 2 — Langkah Cepat Perbaikan --}}
        <section id="hero2" class="scroll-section relative z-20 px-4 py-24 overflow-hidden bg-gradient-to-b from-[#00334E] to-[#00263a]" style="border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="w-full max-w-6xl mx-auto">
                <div class="text-center mb-16 scroll-fade-up">
                    <p class="text-sm uppercase tracking-[0.4em] mb-3" style="color: #f59e0b;">Panduan Sistem</p>
                    <h2 class="text-4xl md:text-5xl font-black text-white">Langkah Cepat Perbaikan</h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg" style="color: rgba(255,255,255,0.7);">Cara kerja sistem monitoring improvement kami dalam 4 langkah sederhana.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 scroll-fade-up">
                    {{-- Step 1 --}}
                    <div class="group p-8 rounded-3xl transition-all duration-300 hover:-translate-y-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3);">
                            <i data-lucide="search" class="w-8 h-8" style="color: #f59e0b;"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">1. Temukan</h3>
                        <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Identifikasi potensi bahaya K3, ketidakteraturan 7S, atau inefisiensi 5R di kampus.</p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="group p-8 rounded-3xl transition-all duration-300 hover:-translate-y-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3);">
                            <i data-lucide="camera" class="w-8 h-8" style="color: #f59e0b;"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">2. Foto</h3>
                        <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Dokumentasikan temuan dengan foto yang jelas untuk mempermudah verifikasi tim.</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="group p-8 rounded-3xl transition-all duration-300 hover:-translate-y-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3);">
                            <i data-lucide="send" class="w-8 h-8" style="color: #f59e0b;"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">3. Kontribusi</h3>
                        <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Kontribusi Anda sangat berharga. Bisa dilakukan untuk meningkatkan nilai kampus tercinta.</p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="group p-8 rounded-3xl transition-all duration-300 hover:-translate-y-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3);">
                            <i data-lucide="layout-dashboard" class="w-8 h-8" style="color: #f59e0b;"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">4. Pantau</h3>
                        <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Ikuti perkembangan perbaikan secara real-time dan kumpulkan poin kontribusi.</p>
                    </div>
                </div>
            </div>
        </section>
        {{-- HERO 3 — Edukasi K3 / 5R / 7S --}}
        <section id="hero3" class="scroll-section relative z-10 px-4 py-32 overflow-hidden transition-colors duration-500 bg-gradient-to-b from-[#00263a] to-[#001f35]" style="border-top: 1px solid rgba(255,255,255,0.1);">

            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute right-0 top-20 h-[500px] w-[500px] rounded-full blur-3xl opacity-10" style="background: radial-gradient(circle, #A0AEC0, transparent 70%);"></div>

            <div class="w-full max-w-6xl mx-auto">

                {{-- Header --}}
                <div class="text-center mb-16 hero3-header scroll-fade-up">
                    <p class="text-sm uppercase tracking-[0.4em] mb-3" style="color: #FFFFFF;">Standar Keselamatan Kampus</p>
                    <h2 class="text-5xl font-black" style="color: #FFFFFF;">Kenali Sistem Improvement Kami</h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg" style="color: rgba(255,255,255,0.7);">Tiga pilar utama yang menjadi landasan budaya keselamatan dan kerapian di POLMAN Bandung.</p>
                </div>

                {{-- Tab Navigation --}}
                <div class="flex justify-center gap-3 mb-12 hero3-tabs">
                    <button class="h3-tab active px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all" data-tab="k3">
                        K3
                    </button>
                    <button class="h3-tab px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all" data-tab="7s">
                        7S
                    </button>
                    <button class="h3-tab px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all" data-tab="5r">
                        5R
                    </button>
                </div>

                {{-- Tab Panels --}}
                <div class="h3-panels-wrapper scroll-fade-up">
                    {{-- Panel K3 --}}
                    <div id="h3-panel-k3" class="h3-panel grid lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-5 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.3);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest mb-1" style="color: #4ade80;">Keselamatan & Kesehatan Kerja</p>
                                    <h3 class="text-3xl font-black" style="color: #FFFFFF;">K3</h3>
                                </div>
                            </div>
                            <p class="text-base leading-relaxed" style="color: rgba(255,255,255,0.8);">
                                K3 (Keselamatan dan Kesehatan Kerja) adalah sistem perlindungan bagi seluruh civitas akademika dari risiko kecelakaan, penyakit akibat kerja, dan bahaya di lingkungan workshop maupun laboratorium POLMAN Bandung.
                            </p>
                            <p class="text-base leading-relaxed" style="color: rgba(255,255,255,0.8);">
                                Setiap temuan potensi bahaya wajib dilaporkan agar tim terkait dapat segera mengambil tindakan pencegahan sebelum insiden terjadi.
                            </p>
                            <div class="pt-2">
                                <p class="text-xs uppercase tracking-widest mb-3" style="color: rgba(255,255,255,0.45);">Dasar Hukum</p>
                                <span class="px-3 py-1.5 text-xs rounded-full mr-2" style="background-color: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80;">UU No.1/1970</span>
                                <span class="px-3 py-1.5 text-xs rounded-full mr-2" style="background-color: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80;">PP No.50/2012</span>
                                <span class="px-3 py-1.5 text-xs rounded-full" style="background-color: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80;">ISO 45001</span>
                            </div>
                        </div>
                        <div class="lg:col-span-7">
                            <p class="text-xs uppercase tracking-widest mb-6" style="color: rgba(255,255,255,0.45);">Contoh Kasus yang perlu improvement</p>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(74,222,128,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">APD Tidak Tersedia / Rusak</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Helm, sarung tangan, kacamata pelindung, atau sepatu safety yang hilang, rusak, atau tidak sesuai standar di area kerja.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(74,222,128,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Mesin / Peralatan Berbahaya</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Guard pengaman mesin yang terlepas, kabel listrik terbuka, atau peralatan dengan kondisi tidak layak pakai.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(74,222,128,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Potensi Kebakaran / APAR Kadaluarsa</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Bahan mudah terbakar tidak tersimpan dengan benar, APAR kosong atau kadaluarsa, jalur evakuasi terhalang.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(74,222,128,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #4ade80;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Near Miss / Hampir Celaka</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Kejadian yang hampir menyebabkan kecelakaan namun tidak menimbulkan cedera — sangat penting untuk dicatat dan dicegah.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel 5R --}}
                    <div id="h3-panel-5r" class="h3-panel hidden grid lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-5 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest mb-1" style="color: #f87171;">Ringkas · Rapi · Resik · Rawat · Rajin</p>
                                    <h3 class="text-3xl font-black" style="color: #FFFFFF;">5R</h3>
                                </div>
                            </div>
                            <p class="text-base leading-relaxed" style="color: rgba(255,255,255,0.8);">
                                5R adalah metodologi manajemen tempat kerja yang bertujuan menciptakan lingkungan yang terorganisir, bersih, dan efisien. Diterapkan secara konsisten, 5R meningkatkan produktivitas dan keselamatan secara bersamaan.
                            </p>
                            <div class="grid grid-cols-2 gap-2 pt-2">
                                @foreach([['Ringkas/Seiri (整理) ','Memilah barang yang diperlukan dan tidak diperlukan, lalu membuang atau memindahkan barang yang tidak perlu.'],
                                ['Rapi/Seiton (整頓)','Mengatur dan menata barang yang diperlukan agar mudah ditemukan, digunakan, dan dikembalikan ke tempatnya.'],
                                ['Resik/Seiso (清掃)','Membersihkan lingkungan kerja dari kotoran dan debu, serta merawat peralatan agar tidak rusak.'],
                                ['Rawat/Seiketsu (清潔)','Mempertahankan kondisi yang sudah bersih dan teratur dengan membuat standar atau prosedur (SOP).'],
                                ['Rajin/Shitsuke (躾)',' Membiasakan diri dan mendisiplinkan diri untuk melakukan 4S sebelumnya secara konsisten sebagai bagian dari budaya kerja.']] as $i => $s)
                                <div class="flex items-center gap-2 p-2.5 rounded-xl" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0" style="background-color: rgba(248,113,113,0.2); color: #f87171;">{{ $i+1 }}</span>
                                    <div>
                                        <span class="text-xs font-bold" style="color: #FFFFFF;">{{ $s[0] }}</span>
                                        <span class="text-xs block" style="color: rgba(255,255,255,0.5);">{{ $s[1] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="lg:col-span-7">
                            <p class="text-xs uppercase tracking-widest mb-6" style="color: rgba(255,255,255,0.45);">Contoh Kasus yang perlu Improvement</p>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(248,113,113,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">SOP Tidak Diikuti</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Prosedur operasional standar yang ada di papan informasi diabaikan atau tidak diperbarui sesuai kondisi terkini.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(248,113,113,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Visual Management Buruk</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Marka lantai aus, papan informasi kosong, atau rambu peringatan yang tidak terbaca di area produksi.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(248,113,113,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Audit 7S Terlewat</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Jadwal audit rutin tidak terlaksana atau hasil audit tidak ditindaklanjuti dalam waktu yang ditetapkan.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(248,113,113,0.2);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #f87171;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Lingkungan Kerja Tidak Kondusif</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Pencahayaan tidak memadai, ventilasi buruk, atau kondisi yang menurunkan semangat dan fokus kerja.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel 7S --}}
                    <div id="h3-panel-7s" class="h3-panel hidden grid lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-5 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(0,191,255,0.15); border: 1px solid rgba(0,191,255,0.3);">
                                    <i data-lucide="award" class="w-8 h-8" style="color: #00BFFF;"></i>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest mb-1" style="color: #00BFFF;">Budaya Kerja Unggul</p>
                                    <h3 class="text-3xl font-black text-white">7S</h3>
                                </div>
                            </div>
                            <p class="text-base leading-relaxed" style="color: rgba(255,255,255,0.8);">
                                7S adalah Seven Waste yaitu 7 pemborosan yang sering terjadi di lingkungan kerja Polman, dan dapat memengaruhi efisiensi, produktivitas, dan kepuasan kerja.
                            </p>
                            <div class="grid grid-cols-1 gap-2 pt-2">
                                @foreach([['1','Transportasi','Memindahkan barang dari satu tempat ke tempat lain.'],
                                ['2','Persediaan','Menyimpan barang yang tidak diperlukan.'],
                                ['3','Gerakan','Gerakan yang tidak perlu dalam melakukan pekerjaan.'],
                                ['4','Penantian','Waktu yang terbuang karena menunggu proses lain.'],
                                ['5','Produksi Berlebih','Memproduksi barang lebih banyak dari yang diperlukan.'],
                                ['6','Proses Berlebih','Melakukan proses yang tidak perlu.'],
                                ['7','Cacat','Kerusakan pada produk.']] as $i => $r)
                                <div class="flex items-center gap-3 p-3 rounded-xl transition-all hover:bg-white/5" style="background-color: rgba(0,191,255,0.1); border: 1px solid rgba(0,191,255,0.2);">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0" style="background-color: #00BFFF; color: #FFFFFF;">{{ $r[0] }}</span>
                                    <div>
                                        <span class="font-bold text-sm block" style="color: #00BFFF;">{{ $r[1] }}</span>
                                        <span class="text-xs" style="color: rgba(255,255,255,0.6);">{{ $r[2] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="lg:col-span-7">
                            <p class="text-xs uppercase tracking-widest mb-6" style="color: rgba(255,255,255,0.45);">Contoh Kasus yang perlu Improvement</p>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(0,191,255,0.25);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Penumpukan Bahan Baku Besi dan Logam di Lab Pengecoran</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Di laboratorium atau bengkel pengecoran logam, terdapat tumpukan balok besi cor dan pasir cetak yang tidak teratur, sehingga memakan tempat dan menyulitkan mobilitas.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(0,191,255,0.25);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Antrean Penggunaan Mesin CNC di Bengkel Kampus</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Mahasiswa sering mengantre sangat lama untuk menggunakan mesin CNC (Computer Numerical Control) saat mengerjakan tugas praktik pemesinan. Akibatnya, jam praktik selesai tidak tepat waktu.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(0,191,255,0.25);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;"> Tingginya Kerusakan Pahat Potong pada Praktik Bubut</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Banyak mahasiswa baru yang mematahkan pahat potong atau merusak benda kerja saat melakukan praktik bubut manual karena kesalahan parameter kecepatan atau kedalaman potong.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="h3-case-card p-5 rounded-2xl" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(0,191,255,0.25);">
                                    <div class="flex items-start gap-3">
                                        <span class="w-2 h-2 rounded-full mt-2 flex-shrink-0" style="background-color: #00BFFF;"></span>
                                        <div>
                                            <p class="font-semibold text-sm mb-1" style="color: #FFFFFF;">Jarak Pemindahan Cetakan yang Jauh di Lab Perancangan</p>
                                            <p class="text-sm" style="color: rgba(255,255,255,0.6);">Dalam proses pembuatan mould (cetakan plastik) atau dies (cetakan logam), mahasiswa harus bolak-balik berjalan jauh membawa material dari gudang penyimpanan menuju mesin ukur koordinat (CMM), lalu ke mesin pengerjaan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End h3-panels-wrapper -->
            </div>
        </section>

        {{-- HERO 4 — Alur Penggunaan Web --}}
        <section id="hero4" class="scroll-section relative z-20 px-4 py-32 overflow-hidden transition-colors duration-500 bg-gradient-to-b from-[#001f35] to-[#001524]" style="border-top: 1px solid rgba(255,255,255,0.1);">

            <div class="pointer-events-none absolute left-0 bottom-0 h-[400px] w-[400px] rounded-full blur-3xl opacity-10" style="background: radial-gradient(circle, #A0AEC0, transparent 70%);"></div>

            <div class="w-full max-w-6xl mx-auto">

                {{-- Header --}}
                <div class="text-center mb-20 hero4-header scroll-fade-up">
                    <p class="text-sm uppercase tracking-[0.4em] mb-3" style="color: #FFFFFF;">Cara Kerja Sistem</p>
                    <h2 class="text-5xl font-black" style="color: #FFFFFF;">Alur Improvement & Reward</h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg" style="color: rgba(255,255,255,0.7);">Dari temuan di lapangan hingga pencapaian poin — semua terekam dan terstruktur dalam satu sistem.</p>
                </div>

                {{-- Flow Steps --}}
                <div class="relative">

                    {{-- Connector line desktop --}}
                    <div class="hidden lg:block absolute top-12 left-0 right-0 h-0.5 z-0" style="background: linear-gradient(to right, transparent 8%, rgba(255,255,255,0.1) 15%, rgba(255,255,255,0.1) 85%, transparent 92%);">
                        <div id="flow-progress-line" class="h-full w-0 transition-none" style="background: linear-gradient(to right, #f59e0b, rgba(85,136,163,0.3));"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 relative z-10">

                        {{-- Step 1 --}}
                        <div class="flow-step text-center" data-step="1">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #f59e0b; box-shadow: 0 0 20px rgba(85,136,163,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: #FFFFFF; color: #FFFFFF;">1</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #FFFFFF;">Sarankan Perbaikan</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Pengusul mengisi formulir temuan K3, 7S, atau 5R lengkap dengan foto dan lokasi kejadian.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(85,136,163,0.15); border: 1px solid rgba(85,136,163,0.3); color: #FFFFFF;">Pengusul</div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="flow-step text-center" data-step="2">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #f59e0b; box-shadow: 0 0 20px rgba(85,136,163,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: rgba(0,51,78,0.3); color: #FFFFFF; border: 1px solid rgba(85,136,163,0.4);">2</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #FFFFFF;">Verifikasi</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Penanggung jawab area meninjau usulan, memvalidasi temuan, dan menetapkan prioritas tindakan.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(85,136,163,0.15); border: 1px solid rgba(85,136,163,0.3); color: #FFFFFF;">Penanggung Jawab</div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="flow-step text-center" data-step="3">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #f59e0b; box-shadow: 0 0 20px rgba(85,136,163,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M6.34 17.66l-1.41 1.41M20 12h-2M6 12H4M17.66 17.66l-1.41-1.41M6.34 6.34L4.93 4.93M12 20v-2M12 6V4"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: rgba(0,51,78,0.3); color: #FFFFFF; border: 1px solid rgba(85,136,163,0.4);">3</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #FFFFFF;">Tindak Lanjut</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Tim teknis melaksanakan perbaikan, mendokumentasikan progress, dan memperbarui status usulan secara real-time.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(85,136,163,0.15); border: 1px solid rgba(85,136,163,0.3); color: #FFFFFF;">Tim Teknis</div>
                        </div>

                        {{-- Step 4 --}}
                        <div class="flow-step text-center" data-step="4">
                            <div class="flow-icon-wrap mx-auto w-24 h-24 rounded-3xl flex items-center justify-center mb-6 relative" style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                                <div class="flow-icon-ring absolute inset-0 rounded-3xl opacity-0" style="border: 2px solid #f59e0b; box-shadow: 0 0 20px rgba(245,158,11,0.4);"></div>
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center" style="background-color: #f59e0b; color: #FFFFFF;">4</span>
                            </div>
                            <h4 class="font-black text-lg mb-2" style="color: #FFFFFF;">Poin & Achievement</h4>
                            <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.6);">Pengusul mendapat poin otomatis, naik peringkat di leaderboard, dan membuka achievement eksklusif.</p>
                            <div class="mt-4 flow-badge inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b;">Reward Otomatis</div>
                        </div>

                    </div>
                </div>

                <!-- {{-- Achievement showcase --}}
                <div class="mt-20 grid grid-cols-2 lg:grid-cols-4 gap-4 hero4-achievements">
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="text-2xl mb-2">🥇</div>
                        <p class="font-bold text-sm" style="color: #FFFFFF;">Pengusul Perdana</p>
                        <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5);">Usulan pertama berhasil</p>
                    </div>
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="text-2xl mb-2">🔥</div>
                        <p class="font-bold text-sm" style="color: #FFFFFF;">Konsisten 7 Hari</p>
                        <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5);">Usulan 7 hari berturut-turut</p>
                    </div>
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="text-2xl mb-2">🛡️</div>
                        <p class="font-bold text-sm" style="color: #FFFFFF;">Guardian K3</p>
                        <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5);">10 usulan K3 diverifikasi</p>
                    </div>
                    <div class="achievement-card p-5 rounded-2xl text-center" style="background-color: rgba(20,83,116,0.2); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="text-2xl mb-2">⭐</div>
                        <p class="font-bold text-sm" style="color: #FFFFFF;">Top Contributor</p>
                        <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5);">Masuk 3 besar leaderboard</p>
                    </div>
                </div> -->

                {{-- Leaderboard --}}
                <div id="leaderboard" class="mt-24 scroll-fade-up">
                    <div class="mb-12 text-center">
                        <p class="text-sm uppercase tracking-[0.4em] mb-3" style="color: #f59e0b;">Peringkat Kontribusi</p>
                        <h3 class="text-4xl font-black text-white uppercase">Leaderboard Dinamis</h3>
                    </div>
                    
                    {{-- Professional Leaderboard Table --}}
                    <div class="overflow-hidden rounded-3xl border border-white/10 shadow-2xl bg-white/5 backdrop-blur-md">
                        <livewire:points.leaderboard :limit="5" />
                    </div>
                </div>

            </div>
        </section>
        {{-- FOOTER --}}
        <footer class="relative z-30 py-20 mt-20" style="background-color: #001524; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7);">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-3 gap-12">
                <div>
                    <h3 class="font-bold mb-4" style="color: #FFFFFF;">POLMAN Safety System</h3>
                    <p class="text-sm" style="color: rgba(255,255,255,0.6);">Sistem Improvement terintegrasi untuk menciptakan lingkungan kampus yang rapi, resik, dan aman bagi seluruh civitas akademika.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4" style="color: #FFFFFF;">Navigasi</h4>
                    <ul class="text-sm space-y-2" style="color: rgba(255,255,255,0.6);">
                        <li><a href="#" class="footer-link transition-colors">Dashboard Utama</a></li>
                        <li><a href="#" class="footer-link transition-colors">Standar 5R/7S</a></li>
                        <li><a href="#" class="footer-link transition-colors">Prosedur K3</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4" style="color: #FFFFFF;">Tautan Terkait</h4>
                    <div class="flex flex-col gap-3 text-sm" style="color: rgba(255,255,255,0.6);">
                        <a href="#" class="footer-link transition-colors">GitHub</a>
                        <a href="#" class="footer-link transition-colors">LinkedIn</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-12 pt-8 text-xs" style="border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.4);">
                &copy; 2026 TRIN POLMAN Bandung. Proyek Sistem Improvement Keselamatan.
            </div>
        </footer>
    </main>
</div>

@push('styles')
<style>
    /* Global Reset for Top Space */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden;
    }
    
    :root {
        --polman-bg:        #00334E;
        --polman-border:    #145374;
        --polman-hover:     #00334E;
        --polman-text:      #00334E;
        --polman-surface:   rgba(20, 83, 116, 0.25);
        --polman-surface-2: rgba(20, 83, 116, 0.15);
    }

    /* Nav links */
    .nav-link:hover { color: var(--polman-hover) !important; }

    /* Buttons */
    .btn-primary:hover { background-color: #6899b4 !important; }
    .btn-outline:hover { border-color: var(--polman-hover) !important; color: var(--polman-hover) !important; }
    .btn-hero { transform: scale(1); transition: all 0.3s ease; }
    .btn-hero:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 10px 25px rgba(245,158,11,0.5); }
    
    /* Reveal animations */
    .pm-fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s forwards cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Progress bar animation */
    .progress-fill {
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Modal Scale Up */
    .modal-scale {
        opacity: 0;
        transform: scale(0.95);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .modal-scale.show {
        opacity: 1;
        transform: scale(1);
    }

    /* Footer links */
    .footer-link:hover { color: var(--polman-hover) !important; }

    /* Scroll Animations Base */
    .scroll-fade-up {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.8s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        will-change: opacity, transform;
    }
    .scroll-fade-up.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Leaderboard Overrides */
    #leaderboard .pm-table thead tr {
        background-color: #00334E !important;
        border-bottom: 3px solid #f59e0b !important;
    }
    #leaderboard .pm-table th {
        color: #FFFFFF !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        padding: 1.2rem 1rem !important;
    }
    #leaderboard .pm-row-top {
        background: linear-gradient(90deg, rgba(245,158,11,0.1) 0%, transparent 100%) !important;
        border-left: 4px solid #f59e0b !important;
    }
    #leaderboard .pm-rank-num {
        font-weight: 900 !important;
    }

    /* Hero Full-width Slider */
    .hero-slide {
        will-change: transform, opacity;
    }
    .hero-slide.active {
        opacity: 1;
        transform: translateX(0);
        z-index: 10;
    }
    .hero-text-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .hero-text-item.active {
        position: relative;
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .hero-text-item.hidden {
        visibility: hidden;
        transform: translateY(20px);
    }
    .slider-indicator.active {
        background-color: #f59e0b;
        box-shadow: 0 0 10px rgba(245,158,11,0.6);
    }

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
    .h3-tab { transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease; cursor: pointer; }
    .h3-tab:hover { filter: brightness(1.1); }
    .h3-tab.active { background-color: #00334E; color: #FFFFFF; border: 1px solid #f59e0b; }
    .h3-tab:not(.active) { background-color: rgba(255,255,255,0.05); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.15); }
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Full Width Hero Slider Logic ---
        const slides = document.querySelectorAll('.hero-slide');
        const textItems = document.querySelectorAll('.hero-text-item');
        const indicators = document.querySelectorAll('.slider-indicator');
        const btnPrev = document.getElementById('hero-prev');
        const btnNext = document.getElementById('hero-next');
        const currentSlideEl = document.getElementById('hero-current-slide');
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        let slideInterval;

        function updateSlider(index) {
            // Reset all
            slides.forEach((slide, i) => {
                slide.style.opacity = '0';
                slide.style.zIndex = '0';
                slide.style.transform = i < index ? 'translateX(-100%)' : 'translateX(100%)';
                slide.classList.remove('active');
            });
            textItems.forEach(item => {
                item.classList.remove('active');
                item.classList.add('hidden');
                item.style.opacity = '0';
            });
            indicators.forEach(ind => ind.classList.remove('active', 'bg-[#f59e0b]'));

            // Set active
            const activeSlide = slides[index];
            activeSlide.style.opacity = '1';
            activeSlide.style.zIndex = '10';
            activeSlide.style.transform = 'translateX(0)';
            activeSlide.classList.add('active');

            const activeText = textItems[index];
            activeText.classList.remove('hidden');
            setTimeout(() => {
                activeText.classList.add('active');
                activeText.style.opacity = '1';
            }, 50);

            indicators[index].classList.add('active');
            indicators[index].classList.replace('bg-white', 'bg-[#f59e0b]');
            indicators[index].classList.replace('bg-white/30', 'bg-[#f59e0b]');

            currentSlideEl.innerText = index + 1;
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider(currentSlide);
        }

        btnNext.addEventListener('click', () => { nextSlide(); resetInterval(); });
        btnPrev.addEventListener('click', () => { prevSlide(); resetInterval(); });

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 6000); // 6 seconds
        }
        
        // Initialize first slide
        updateSlider(0);
        
        if (totalSlides > 1) {
            resetInterval();
        }

        // --- Hero 3 Tabs Logic ---
        function initHero3Tabs() {
            const tabs = document.querySelectorAll('.h3-tab');
            const panels = document.querySelectorAll('.h3-panel');
            if (tabs.length === 0) return;

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-tab').toLowerCase();
                    const targetPanel = document.getElementById(`h3-panel-${targetId}`);

                    if (!targetPanel) return;

                    // 1. Update Tabs UI
                    tabs.forEach(t => {
                        t.classList.remove('active');
                        t.style.backgroundColor = 'rgba(255,255,255,0.05)';
                        t.style.color = 'rgba(255,255,255,0.6)';
                        t.style.borderColor = 'rgba(255,255,255,0.1)';
                    });
                    
                    this.classList.add('active');
                    this.style.backgroundColor = '#00334E'; // Navy
                    this.style.color = '#FFFFFF';
                    this.style.borderColor = '#f59e0b'; // Safety Orange

                    // 2. Hide all panels
                    panels.forEach(p => {
                        p.style.display = 'none';
                        p.classList.add('hidden');
                        p.style.opacity = '0';
                        p.style.transform = 'translateY(10px)';
                    });

                    // 3. Show target panel
                    targetPanel.classList.remove('hidden');
                    targetPanel.style.setProperty('display', 'grid', 'important');
                    
                    // Trigger animation
                    setTimeout(() => {
                        targetPanel.style.opacity = '1';
                        targetPanel.style.transform = 'translateY(0)';
                        targetPanel.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    }, 20);
                });
            });
        }

        initHero3Tabs();
        document.addEventListener('livewire:load', initHero3Tabs);
        document.addEventListener('livewire:update', initHero3Tabs);

        // --- Scroll Animations (Intersection Observer) ---
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    scrollObserver.unobserve(entry.target); // Hanya animasi satu kali
                }
            });
        }, { threshold: 0.15, rootMargin: "0px 0px -50px 0px" });

        document.querySelectorAll('.scroll-fade-up').forEach((el) => {
            scrollObserver.observe(el);
        });

        // --- Lucide Icons ---
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // --- Leaderboard Medals Injection ---
        function injectMedals() {
            const tableRows = document.querySelectorAll('#leaderboard tr');
            if (tableRows.length > 1) {
                // Skip header (i=0)
                const top3 = [
                    { icon: 'medal', color: '#FFD700', text: '🥇' }, // Gold
                    { icon: 'medal', color: '#C0C0C0', text: '🥈' }, // Silver
                    { icon: 'medal', color: '#CD7F32', text: '🥉' }  // Bronze
                ];
                
                for (let i = 1; i <= Math.min(3, tableRows.length - 1); i++) {
                    const row = tableRows[i];
                    const rankCell = row.querySelector('td'); // Assume first cell is rank
                    if (rankCell) {
                        row.classList.add('pm-row-top');
                        rankCell.innerHTML = `<span class="flex items-center justify-center gap-2">
                            <span style="color: ${top3[i-1].color}">${top3[i-1].text}</span>
                            <span class="pm-rank-num">${i}</span>
                        </span>`;
                    }
                }
            }
        }
        
        // Run after livewire load
        setTimeout(injectMedals, 1000);
        document.addEventListener('livewire:load', injectMedals);
    });
</script>
@endpush
@endsection
