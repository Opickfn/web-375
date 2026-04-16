@extends('layouts.guest')
@section('title', 'POLMAN 375')

@section('content')
<div id="landing-root" class="min-h-screen bg-slate-950 text-slate-100">
    <nav id="landingNav" class="fixed inset-x-0 top-0 z-50 border-b border-slate-800 bg-slate-950/95 backdrop-blur-lg">
        <div class="container mx-auto flex items-center justify-between px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-cyan-400 to-sky-600 flex items-center justify-center text-lg font-bold text-slate-950">375</div>
                <span class="text-xl font-semibold tracking-[0.18em]">POLMAN 375</span>
            </div>
            <div class="hidden gap-3 md:flex">
                <a href="#hero1" class="btn btn-outline btn-sm">Hero 1</a>
                <a href="#hero2" class="btn btn-outline btn-sm">Hero 2</a>
                <a href="#hero3" class="btn btn-outline btn-sm">Hero 3</a>
                <a href="#hero4" class="btn btn-outline btn-sm">Leaderboard</a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
            </div>
        </div>
    </nav>

    <main class="pt-24">
        <section id="hero1" class="relative overflow-hidden bg-[#0B1120] px-4 py-20">
            <div class="pointer-events-none absolute left-0 top-0 h-[420px] w-[420px] rounded-full bg-[#22d3ee]/20 blur-3xl" style="mask-image: radial-gradient(circle, rgba(255,255,255,1) 0%, transparent 62%);"></div>
            <div class="container mx-auto grid gap-10 lg:grid-cols-2 items-center">
                <div class="space-y-8 text-white relative z-10">
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">Platform Pelaporan Terintegrasi</p>
                    <h1 class="text-5xl font-black leading-tight tracking-tight font-sans">
                        Sistem Pelaporan <span class="text-white">K3,</span> <span class="text-[#22d3ee]">7S,</span> <span class="text-white">dan</span> <span class="text-[#22d3ee]">5R</span>
                    </h1>
                    <p class="max-w-xl text-[#94a3b8] text-lg leading-relaxed hero-copy">Sistem pelaporan modern untuk mengumpulkan, memverifikasi, dan menindaklanjuti temuan K3, 7S, dan 5R dengan visualisasi yang jelas dan responsif.</p>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <a href="{{ route('register') }}" class="btn-animate rounded-[12px] bg-[#22d3ee] px-6 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-slate-950 shadow-lg shadow-[#22d3ee]/20">Mulai Dari Sini</a>
                        <a href="/create/publik/reports" class="btn-animate rounded-[12px] border border-slate-600 bg-transparent px-6 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-white shadow-sm shadow-slate-950/10">Lapor sebagai Publik</a>
                        <a href="#hero3" class="btn-animate rounded-[12px] border border-slate-600 bg-transparent px-6 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-white shadow-sm shadow-slate-950/10">Pelajari Lebih Lanjut</a>
                    </div>
                </div>

                <div class="slider-container relative overflow-hidden rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-[0_40px_90px_rgba(34,211,238,0.16)] backdrop-blur-xl">
                    <div class="slider-card relative min-h-[420px] overflow-hidden rounded-[24px] bg-cover bg-center filter brightness-110 saturate-[1.2] shadow-2xl shadow-[#22d3ee]/20" style="background-image: url('https://images.unsplash.com/photo-1519337265831-281ec6cc8514?auto=format&fit=crop&w=1200&q=80');">
                        <div class="absolute inset-0 bg-slate-950/30 backdrop-blur-xl"></div>
                        <div class="slider-content relative z-10 flex h-full flex-col justify-between p-8 text-white">
                            <div class="space-y-4">
                                <p class="slider-label text-sm uppercase tracking-[0.28em] text-cyan-300">Sistem Pelaporan K3, 7S, dan 5R</p>
                                <h2 class="slider-title text-3xl font-bold leading-tight">Sistem Pelaporan K3, 7S, dan 5R</h2>
                                <p class="slider-description max-w-xl text-slate-200">Lapor temuan lapangan dengan mudah, pantau tindak lanjut, dan bangun budaya keselamatan kampus.</p>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="slider-meta text-sm text-slate-300">Slide <span class="slider-index">1</span> dari <span class="slider-total">3</span></div>
                                <div class="flex gap-2">
                                    <button type="button" data-action="prev" class="slider-nav rounded-[12px] border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm text-white">Prev</button>
                                    <button type="button" data-action="next" class="slider-nav rounded-[12px] border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm text-white">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script type="module">
            import anime from 'https://unpkg.com/animejs@4.0.0/lib/anime.es.js';

            const slides = [
                {
                    image: 'https://images.unsplash.com/photo-1519337265831-281ec6cc8514?auto=format&fit=crop&w=1200&q=80',
                    label: 'Bangunan Kampus Modern',
                    title: 'Peningkatan Infrastruktur Kampus',
                    description: 'Bangunan kampus modern dengan pemantauan dan pelaporan K3 yang terintegrasi untuk menjaga keselamatan semua pengguna.',
                    heroCopy: 'Slide pertama menyorot kekuatan sistem pelaporan untuk mendukung lingkungan kampus yang aman dan terkontrol.',
                },
                {
                    image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
                    label: 'Peringatan Kebersihan',
                    title: 'Temuan Kebersihan & Keamanan',
                    description: 'Laporan cepat ketika area kerja tidak memenuhi standar kebersihan dan SOP K3.',
                    heroCopy: 'Dukung kepatuhan lapangan dengan laporan kebersihan yang mendetail dan tindak lanjut yang transparan.',
                },
                {
                    image: 'https://images.unsplash.com/photo-1520027296538-7ecaef410a6a?auto=format&fit=crop&w=1200&q=80',
                    label: 'Peralatan Rusak',
                    title: 'Perbaikan Peralatan Prioritas',
                    description: 'Identifikasi kerusakan peralatan lebih cepat dan kirim laporan langsung kepada tim pemeliharaan.',
                    heroCopy: 'Fokus pada penanganan risiko dengan pelaporan alat rusak yang mempercepat respons teknis.',
                },
            ];

            const btns = document.querySelectorAll('.btn-animate');
            const sliderCard = document.querySelector('.slider-card');
            const sliderLabel = document.querySelector('.slider-label');
            const sliderTitle = document.querySelector('.slider-title');
            const sliderDescription = document.querySelector('.slider-description');
            const sliderIndex = document.querySelector('.slider-index');
            const sliderTotal = document.querySelector('.slider-total');
            const sliderContainer = document.querySelector('.slider-container');
            const heroCopy = document.querySelector('.hero-copy');
            const navButtons = document.querySelectorAll('.slider-nav');
            let currentSlide = 0;
            let hoveredButton = null;
            let slideInterval = null;

            if (sliderTotal) {
                sliderTotal.textContent = slides.length;
            }

            const animateButton = (target, scale, duration = 400) => {
                if (!target) return;
                anime.remove(target);
                anime({
                    targets: target,
                    scale,
                    duration,
                    elasticity: 600,
                    easing: 'easeOutElastic(1, .8)',
                });
            };

            btns.forEach((button) => {
                button.style.transformOrigin = 'center center';
                button.addEventListener('mouseenter', () => {
                    hoveredButton = button;
                    animateButton(button, 1.2, 400);
                });
                button.addEventListener('mouseleave', () => {
                    hoveredButton = null;
                    animateButton(button, 1.0, 400);
                });
                button.addEventListener('mousedown', () => {
                    animateButton(button, 0.92, 140);
                });
                button.addEventListener('mouseup', () => {
                    const scale = hoveredButton === button ? 1.2 : 1.0;
                    animateButton(button, scale, 220);
                });
            });

            function updateSlide(index) {
                if (!sliderCard || !sliderLabel || !sliderTitle || !sliderDescription || !sliderIndex || !heroCopy) return;

                currentSlide = (index + slides.length) % slides.length;
                const slide = slides[currentSlide];
                sliderIndex.textContent = currentSlide + 1;

                const fadeOut = anime.timeline({ easing: 'easeInQuad', duration: 250 });
                fadeOut.add({
                    targets: [sliderLabel, sliderTitle, sliderDescription, heroCopy],
                    opacity: [1, 0],
                    translateY: [0, 18],
                    duration: 220,
                    delay: anime.stagger(35),
                });
                fadeOut.add({
                    targets: sliderCard,
                    opacity: [1, 0.72],
                    duration: 250,
                }, 0);

                fadeOut.finished.then(() => {
                    sliderCard.style.backgroundImage = `url('${slide.image}')`;
                    sliderLabel.textContent = slide.label;
                    sliderTitle.textContent = slide.title;
                    sliderDescription.textContent = slide.description;
                    heroCopy.textContent = slide.heroCopy;

                    anime.timeline({ easing: 'easeOutExpo', duration: 420 })
                        .add({
                            targets: sliderCard,
                            opacity: [0.72, 1],
                        })
                        .add({
                            targets: [sliderLabel, sliderTitle, sliderDescription, heroCopy],
                            opacity: [0, 1],
                            translateY: [18, 0],
                            duration: 420,
                            delay: anime.stagger(50),
                        }, '-=260');
                });
            }

            navButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const action = button.dataset.action;
                    const nextIndex = action === 'next' ? currentSlide + 1 : currentSlide - 1;
                    updateSlide(nextIndex);
                });
            });

            const startSliderLoop = () => {
                if (slideInterval) {
                    clearInterval(slideInterval);
                }
                slideInterval = setInterval(() => updateSlide(currentSlide + 1), 6500);
            };

            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', () => {
                    if (slideInterval) clearInterval(slideInterval);
                });
                sliderContainer.addEventListener('mouseleave', startSliderLoop);
            }

            updateSlide(0);
            startSliderLoop();
        </script>

        <section id="hero2" class="relative min-h-screen overflow-hidden p-10 bg-[#0B1120]">
            <div id="layout-root" class="w-full max-w-6xl mx-auto grid grid-cols-1 gap-8">
                <div class="boxes-wrapper flex flex-wrap justify-center gap-6 rounded-[2rem] border border-white/10 bg-white/5 p-6 backdrop-blur-xl shadow-2xl shadow-slate-950/25">
                    <div data-layout-id="5R" class="box-item cursor-pointer rounded-[2rem] border border-cyan-400/25 bg-cyan-400/15 p-7 shadow-2xl shadow-cyan-500/15 text-left text-white">
                        <div class="flex h-full flex-col justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-cyan-100">5R</p>
                                <h3 class="mt-4 text-3xl font-bold">Budaya Area Rapi</h3>
                            </div>
                            <p class="mt-3 text-cyan-100 text-sm">Klik untuk melihat detail budaya area rapi dan pengelolaan visual.</p>
                        </div>
                    </div>
                    <div data-layout-id="7S" class="box-item cursor-pointer rounded-[2rem] border border-rose-500/25 bg-rose-500/15 p-7 shadow-2xl shadow-rose-500/15 text-left text-white">
                        <div class="flex h-full flex-col justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-rose-100">7S</p>
                                <h3 class="mt-4 text-3xl font-bold">Budaya Teratur</h3>
                            </div>
                            <p class="mt-3 text-rose-100 text-sm">Klik untuk menampilkan praktik disiplin dan tata kelola kerja.</p>
                        </div>
                    </div>
                    <div data-layout-id="K3" class="box-item cursor-pointer rounded-[2rem] border border-emerald-400/25 bg-emerald-400/15 p-7 shadow-2xl shadow-emerald-400/15 text-left text-white">
                        <div class="flex h-full flex-col justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-emerald-100">K3</p>
                                <h3 class="mt-4 text-3xl font-bold">Keamanan Kerja</h3>
                            </div>
                            <p class="mt-3 text-emerald-100 text-sm">Klik untuk melihat fokus keselamatan dan pencegahan risiko.</p>
                        </div>
                    </div>
                </div>

                <div id="content-area" class="rounded-[2rem] border border-white/10 bg-white/5 p-8 backdrop-blur-xl opacity-0" style="display:none;">
                    <p id="content-tag" class="text-xs uppercase tracking-[0.4em] text-cyan-200">Detail</p>
                    <h2 id="content-title" class="mt-4 text-4xl font-black text-white"></h2>
                    <div id="content-body" class="mt-4 text-slate-300 leading-relaxed"></div>
                    <div id="content-list" class="mt-8 space-y-3"></div>
                </div>
            </div>
        </section>

        <script type="module">
            import { animate, createLayout, utils } from 'https://cdn.jsdelivr.net/npm/animejs@4.0.0-beta.90/+esm';

            const root = document.querySelector('#layout-root');
            const boxesWrapper = document.querySelector('.boxes-wrapper');
            const boxes = Array.from(document.querySelectorAll('.box-item'));
            const contentArea = document.querySelector('#content-area');
            const contentTag = document.querySelector('#content-tag');
            const contentTitle = document.querySelector('#content-title');
            const contentBody = document.querySelector('#content-body');
            const contentList = document.querySelector('#content-list');
            const layout = createLayout(root);

            const details = {
                5R: {
                    theme: '#38bdf8',
                    tag: '5R',
                    title: 'Budaya Area Rapi',
                    body: 'Fokus pada area yang rapih, bersih, dan teratur. Setiap temuan dicatat secara cepat agar area kerja selalu siap operasi.',
                    items: [
                        'Inspeksi visual area kerja untuk menjaga kebersihan.',
                        'Sistem laporan cepat untuk temuan rapi.',
                        'Tindak lanjut terjadwal untuk area prioritas.'
                    ]
                },
                7S: {
                    theme: '#f43f5e',
                    tag: '7S',
                    title: 'Budaya Teratur',
                    body: 'Menciptakan disiplin dan tata kelola kerja yang konsisten melalui monitoring dan laporan terstruktur.',
                    items: [
                        'Standarisasi kebersihan dan kerapian area.',
                        'Pencatatan temuan secara rutin.',
                        'Evaluasi budaya kerja berdasarkan 7S.'
                    ]
                },
                K3: {
                    theme: '#10b981',
                    tag: 'K3',
                    title: 'Keamanan Kerja',
                    body: 'Menangani risiko kerja dengan laporan K3 terstruktur, memastikan perlindungan tim lapangan dan pencegahan insiden.',
                    items: [
                        'Identifikasi bahaya potensial secara proaktif.',
                        'Koordinasi tindakan keselamatan lapangan.',
                        'Pelaporan cepat untuk pencegahan insiden.'
                    ]
                }
            };

            animate('.box-item', {
                rotate: () => utils.random(-10, 10),
                y: [0, -15],
                direction: 'alternate',
                loop: true,
                duration: () => utils.random(1500, 2500),
                easing: 'easeInOutSine'
            });

            boxes.forEach((box) => {
                box.addEventListener('click', () => {
                    const selected = details[box.dataset.layoutId];
                    if (!selected) return;

                    layout.update(() => {
                        root.style.gridTemplateColumns = '180px 1fr';
                        boxesWrapper.classList.remove('flex-wrap', 'justify-center');
                        boxesWrapper.classList.add('flex-col', 'justify-start');
                        boxesWrapper.style.alignItems = 'stretch';
                        contentArea.style.display = 'block';
                    });

                    contentTag.textContent = selected.tag;
                    contentTitle.textContent = selected.title;
                    contentBody.textContent = selected.body;
                    contentList.innerHTML = selected.items
                        .map(item => `<div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-slate-200">${item}</div>`)
                        .join('');

                    animate(box, {
                        scale: 1.05,
                        boxShadow: '0 0 0 2px rgba(255,255,255,0.35)',
                        duration: 320,
                        easing: 'easeOutExpo'
                    });

                    animate('.box-item', {
                        scale: (el) => (el === box ? 1.02 : 0.88),
                        opacity: (el) => (el === box ? 1 : 0.45),
                        borderColor: (el) => (el === box ? 'rgba(255,255,255,0.45)' : 'rgba(255,255,255,0.12)'),
                        duration: 450,
                        easing: 'easeOutQuad'
                    });

                    animate(contentArea, {
                        opacity: [0, 1],
                        x: [30, 0],
                        duration: 700,
                        easing: 'easeOutQuart'
                    });
                });
            });
        </script>

        <section id="hero3" class="relative border-t border-slate-800 bg-slate-900 px-4 py-24 text-slate-100">
            <div class="container mx-auto grid gap-10 lg:grid-cols-[0.95fr_1.05fr] items-start">
                <div class="space-y-6">
                    <p class="text-sm uppercase tracking-[0.4em] text-cyan-300">Workflow Interaktif</p>
                    <h2 class="text-4xl font-black">Modal detail meledak dari setiap langkah</h2>
                    <p class="max-w-xl text-slate-400">Ketika Anda klik tiap blok, sistem menampilkan alur kerja lengkap dengan detail yang muncul sebagai modal detail.</p>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <button class="hero3-card rounded-[1.75rem] border border-slate-800 bg-slate-950/90 p-8 text-left transition" data-target="workflow-1">
                            <p class="text-sm uppercase tracking-[0.28em] text-cyan-300">Langkah 1</p>
                            <h3 class="mt-4 text-xl font-semibold">Pengamatan</h3>
                        </button>
                        <button class="hero3-card rounded-[1.75rem] border border-slate-800 bg-slate-950/90 p-8 text-left transition" data-target="workflow-2">
                            <p class="text-sm uppercase tracking-[0.28em] text-rose-300">Langkah 2</p>
                            <h3 class="mt-4 text-xl font-semibold">Verifikasi</h3>
                        </button>
                        <button class="hero3-card rounded-[1.75rem] border border-slate-800 bg-slate-950/90 p-8 text-left transition" data-target="workflow-3">
                            <p class="text-sm uppercase tracking-[0.28em] text-emerald-300">Langkah 3</p>
                            <h3 class="mt-4 text-xl font-semibold">Eksekusi</h3>
                        </button>
                    </div>
                </div>
                <div class="relative rounded-[2rem] border border-slate-800 bg-slate-950/90 p-8 shadow-2xl shadow-slate-950/40">
                    <svg id="hero3-line" class="pointer-events-none absolute inset-0 h-full w-full opacity-40" viewBox="0 0 560 360" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28 320 C180 240, 320 80, 532 60" stroke="url(#line-gradient)" stroke-width="3" stroke-linecap="round" />
                        <defs>
                            <linearGradient id="line-gradient" x1="0" y1="0" x2="560" y2="0" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#0ea5e9" />
                                <stop offset="1" stop-color="#34d399" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="relative space-y-4">
                        <div class="rounded-[1.75rem] border border-slate-800 bg-slate-950/90 p-6">
                            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Visualisasi Alur</p>
                            <h3 class="mt-4 text-3xl font-bold text-white">Workflow dalam Satu Tampilan</h3>
                            <p class="mt-4 text-slate-400">Klik salah satu kartu untuk memperluas pembahasan dan melihat data terperinci dalam modal detail.</p>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-[1.75rem] border border-slate-800 bg-slate-950/90 p-6">
                                <p class="text-sm uppercase tracking-[0.25em] text-cyan-300">Status</p>
                                <h4 class="mt-3 text-xl font-semibold text-white">Dashboard real-time</h4>
                            </div>
                            <div class="rounded-[1.75rem] border border-slate-800 bg-slate-950/90 p-6">
                                <p class="text-sm uppercase tracking-[0.25em] text-emerald-300">Integrasi</p>
                                <h4 class="mt-3 text-xl font-semibold text-white">Laporan, tindak lanjut, dan penilaian</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="hero4" class="border-t border-slate-800 bg-slate-950 px-4 py-24 text-slate-100">
            <div class="container mx-auto">
                <div class="mb-12 text-center">
                    <p class="text-sm uppercase tracking-[0.4em] text-cyan-300">Leaderboard Dinamis</p>
                    <h2 class="mt-4 text-4xl font-black">Kinerja Pelapor Berbasis Poin</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-slate-400">Leaderboard muncul dengan efek stagger pada baris, menunjukkan kontributor terbaik dalam sistem.</p>
                </div>
                <div class="overflow-hidden rounded-[2rem] border border-slate-800 bg-slate-900/95 p-6 shadow-2xl shadow-slate-950/40">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-500">
                                <th class="py-4">Rank</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th class="text-right">Total Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topUsers as $index => $user)
                            @php
                                $displayName = ($user->role === 'reporter' && !$user->show_name_on_landing) ? 'Anonim' : $user->full_name;
                            @endphp
                            <tr class="border-b border-slate-800 opacity-0 transform translate-y-6">
                                <td class="py-4 font-semibold text-white">#{{ $index + 1 }}</td>
                                <td class="py-4">{{ $displayName }}</td>
                                <td class="py-4 text-slate-400">{{ ucfirst($user->user_type) }}</td>
                                <td class="py-4 text-right font-semibold text-cyan-300">{{ number_format($user->total_points) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-500">Data leaderboard belum tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

@push('styles')
<style>
    #landingNav { transition: box-shadow 0.25s ease; }
    .btn-animate { transform: scale(1); transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .slider-card { min-height: 420px; }
    .slider-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15,23,42,0.12), rgba(15,23,42,0.8));
        pointer-events: none;
    }
    .slider-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(34,211,238,0.18), transparent 40%);
        pointer-events: none;
        mix-blend-mode: screen;
    }
    .slider-container { box-shadow: 0 40px 90px rgba(34, 211, 238, 0.16); }
    .slider-content { position: relative; z-index: 2; }
    .slider-nav { transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease; }
    .slider-nav:hover { transform: translateY(-1px); }
    .exploration-wrapper { display: flex; justify-content: center; gap: 2rem; min-height: 400px; align-items: center; flex-wrap: wrap; }
    .sidebar-mode { display: flex; flex-direction: column; width: 150px; gap: 1rem; justify-content: flex-start; align-items: stretch; }
    .hero2-explore-card { width: 250px; height: 300px; overflow: hidden; }
    .hero2-box-inner { pointer-events: none; }
    .hero1-slide { transition: opacity 0.5s ease, transform 0.5s ease; }
    .hero1-slide-active { opacity: 1; transform: translateX(0); }
    .hero1-slide.hidden { opacity: 0; visibility: hidden; transform: translateX(-10%); }
    .hero2-box:hover { transform: translateY(-6px); }
    .hero2-nav-item.active { background: rgba(56, 189, 248, 0.18); border-color: rgba(56, 189, 248, 0.35); color: #ffffff !important; }
    .hero2-panel { transition: opacity 0.4s ease, transform 0.4s ease; }
    .hero2-panel.hidden { position: absolute; inset: 0; opacity: 0; pointer-events: none; transform: translateX(12%); }
    .hero3-card:hover { transform: translateY(-8px); }
    .hero3-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .modal-overlay { background: rgba(0, 0, 0, 0.65); }
    .reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.active { opacity: 1; transform: translateY(0); }
</style>
@endpush
@endsection
