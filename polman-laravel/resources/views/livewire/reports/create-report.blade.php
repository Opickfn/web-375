<div class="public-report-shell">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="public-report-card w-full max-w-5xl">
            <div class="public-report-inner">
                <div class="public-report-header mb-10">
                    <h1>Buat Laporan Internal</h1>
                    <p class="text-slate-300 leading-relaxed">
                        Laporkan temuan perbaikan 5R, 7S, atau K3 dengan detail lengkap untuk diproses oleh tim internal.
                    </p>
                </div>

                @if($showSuccess && $successReport)
                    <div class="success-card">
                        <div class="success-panel">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-cyan-500/15 text-cyan-300 mb-6 shadow-[0_20px_60px_rgba(6,182,212,0.18)]">
                                <i data-lucide="check-circle" class="w-10 h-10"></i>
                            </div>
                            <h2 class="success-title">Laporan Berhasil Dikirim!</h2>
                            <p class="text-slate-300 max-w-2xl mx-auto mb-8">Terima kasih atas kontribusi Anda. Laporan telah terkirim dan akan ditinjau oleh tim manajemen.</p>

                            <div class="grid gap-4 sm:grid-cols-2 mb-8 text-left text-slate-200">
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Kode Laporan</span>
                                    <p class="mt-3 text-xl font-semibold">{{ $successReport->code }}</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Kategori</span>
                                    <p class="mt-3 text-xl font-semibold">{{ $successReport->kategori }}</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Prioritas</span>
                                    <p class="mt-3 text-xl font-semibold capitalize">{{ $successReport->prioritas }}</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Lokasi</span>
                                    <p class="mt-3 text-xl font-semibold">{{ $successReport->location_breadcrumb ?? $successReport->detail_lokasi }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                                <button wire:click="resetForm" class="submit-button px-6 py-4 w-full sm:w-auto">Buat Laporan Baru</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-lg px-6 py-4 text-center w-full sm:w-auto">Kembali ke Dashboard</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] items-start">
                        <div class="glass-card p-8">
                            <div class="mb-6 rounded-3xl bg-slate-900/70 border border-cyan-500/10 p-5">
                                <p class="text-cyan-300 font-medium">Form Laporan Internal</p>
                                <p class="mt-2 text-slate-400 text-sm leading-relaxed">Laporan lengkap dengan lokasi, kategori, prioritas dan bukti akan diproses oleh tim manajemen internal.</p>
                            </div>

                            <form wire:submit="submit" class="space-y-5">
                                <div class="form-group input-group">
                                    <label class="form-label" for="kategori">Kategori Pelanggaran</label>
                                    <select wire:model="kategori" id="kategori" class="input-field-custom appearance-none bg-slate-950 text-white">
                                        <option value="">Pilih kategori</option>
                                        <option value="5R">5R (Ringkas, Rapi, Resik, Rawat, Rajin)</option>
                                        <option value="7S">7S (Seiri, Seiton, Seiso, Seiketsu, Shitsuke, Safety, Semangat)</option>
                                        <option value="K3">K3 (Keselamatan dan Kesehatan Kerja)</option>
                                    </select>
                                    @error('kategori') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="prioritas">Prioritas</label>
                                    <select wire:model="prioritas" id="prioritas" class="input-field-custom appearance-none bg-slate-950 text-white">
                                        <option value="rendah">Rendah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="tinggi">Tinggi</option>
                                    </select>
                                    @error('prioritas') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
<label class="form-label" for="campus_id">Kampus</label>
                                    <select wire:model="campus_id" id="campus_id" wire:change="$refresh" class="input-field-custom appearance-none bg-slate-950 text-white">
                                        <option value="">Pilih Kampus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('campus_id') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
<label class="form-label" for="branch_id">Gedung/Infrastruktur</label>
                                    <select wire:model="branch_id" id="branch_id" wire:change="$refresh" class="input-field-custom appearance-none bg-slate-950 text-white" {{ !$campus_id ? 'disabled' : '' }}>
                                        <option value="">Pilih Gedung/Infrastruktur</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ ucwords($branch->type) }} - {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                @if($branchType === 'gedung' && $branch_id)
                                    <div class="form-group input-group">
<label class="form-label" for="floor_id">Lantai</label>
                                    <select wire:model="floor_id" id="floor_id" wire:change="$refresh" class="input-field-custom appearance-none bg-slate-950 text-white">
                                        <option value="">Pilih Lantai</option>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('floor_id') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>

                                    @if($floor_id)
                                        <div class="form-group input-group">
                                            <label class="form-label" for="space_id">Ruang/Area</label>
                                            <select wire:model="space_id" id="space_id" class="input-field-custom appearance-none bg-slate-950 text-white">
                                                <option value="">Pilih Ruang/Area</option>
                                                @foreach($spaces as $space)
                                                    <option value="{{ $space->id }}">{{ ucfirst($space->type) }} - {{ $space->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('space_id') <p class="form-error">{{ $message }}</p> @enderror
                                        </div>
                                    @endif
                                @endif

                                <div class="form-group input-group">
                                    <label class="form-label" for="detail_lokasi">Detail Lokasi</label>
                                    <input id="detail_lokasi" type="text" wire:model="detail_lokasi" class="input-field-custom" placeholder="Contoh: Dekat pintu masuk, sudut tangga, dll">
                                    @error('detail_lokasi') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="deskripsi">Deskripsi Temuan</label>
                                    <textarea id="deskripsi" wire:model="deskripsi" rows="5" class="input-field-custom" placeholder="Jelaskan secara detail temuan pelanggaran yang Anda temukan..."></textarea>
                                    @error('deskripsi') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="bukti">Bukti Foto (opsional)</label>
                                    <input id="bukti" type="file" wire:model="bukti" accept="image/*" class="input-field-custom cursor-pointer" />
                                    @error('bukti') <p class="form-error">{{ $message }}</p> @enderror

                                    @if($bukti)
                                        <img src="{{ $bukti->temporaryUrl() }}" alt="Preview"
                                             class="report-preview mt-4 rounded-3xl border border-white/10 shadow-[0_0_15px_rgba(255,255,255,0.08)]" />
                                    @endif
                                </div>

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pt-2">
                                    <button type="button" wire:click="resetForm" class="btn btn-outline btn-lg px-6 py-4 text-center w-full sm:w-auto">Batal</button>
                                    <button type="submit" class="submit-button w-full sm:w-auto py-4 font-semibold">Kirim Laporan Internal</button>
                                </div>
                            </form>
                        </div>

                        <div class="glass-card p-8 border border-white/10 bg-slate-950/70 shadow-[0_30px_90px_rgba(15,23,42,0.3)]">
                            <div class="rounded-[2rem] border border-cyan-500/10 bg-cyan-500/5 p-6 mb-6">
                                <h3 class="text-xl font-semibold text-cyan-200">Ringkasan Internal</h3>
                                <p class="mt-3 text-slate-300 leading-relaxed">Laporan internal diproses prioritas tinggi oleh tim manajemen. Lengkapi lokasi dan prioritas dengan akurat.</p>
                            </div>
                            <div class="space-y-4 text-slate-300 text-sm leading-7">
                                <div>
                                    <span class="inline-flex rounded-full bg-cyan-500/10 px-3 py-1 text-cyan-200 text-xs uppercase tracking-[0.2em]">Pro tip</span>
                                    <p class="mt-3">Prioritas tinggi akan langsung masuk queue manajer. Gunakan dengan tepat.</p>
                                </div>
                                <div>
                                    <span class="inline-flex rounded-full bg-slate-700/80 px-3 py-1 text-slate-200 text-xs uppercase tracking-[0.18em]">Fokus</span>
                                    <p class="mt-3">Lokasi lengkap (lantai/ruang) memastikan respons tim tepat sasaran.</p>
                                </div>
                                <div>
                                    <span class="inline-flex rounded-full bg-slate-700/80 px-3 py-1 text-slate-200 text-xs uppercase tracking-[0.18em]">Keamanan</span>
                                    <p class="mt-3">Laporan dienkripsi dan hanya terlihat oleh tim berwenang.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .public-report-shell {
        min-height: 100vh;
        position: relative;
        background: radial-gradient(circle at top right, rgba(6, 182, 212, 0.18), transparent 28%),
                    radial-gradient(circle at bottom left, rgba(148, 163, 184, 0.12), transparent 22%),
                    linear-gradient(180deg, #020617 0%, #0b1220 100%);
        overflow: hidden;
    }
    .public-report-shell::before,
    .public-report-shell::after {
        content: '';
        position: absolute;
        border-radius: 9999px;
        filter: blur(110px);
        opacity: 0.4;
    }
    .public-report-shell::before {
        width: 540px;
        height: 540px;
        top: -150px;
        right: -120px;
        background: rgba(14, 165, 233, 0.2);
    }
    .public-report-shell::after {
        width: 420px;
        height: 420px;
        bottom: -140px;
        left: -120px;
        background: rgba(15, 23, 42, 0.35);
    }
    .public-report-card {
        border-radius: 2rem;
        background: rgba(10, 20, 34, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 60px 120px rgba(5, 10, 20, 0.55);
        backdrop-filter: blur(22px);
    }
    .public-report-inner {
        padding: 2.5rem;
    }
    .public-report-header h1 {
        font-size: clamp(2.5rem, 4vw, 3.6rem);
        line-height: 1;
        font-weight: 800;
        background: linear-gradient(90deg, #f8fafc, #94a3b8);
        -webkit-background-clip: text;
        color: transparent;
    }
    .public-report-header p {
        margin-top: 0.9rem;
        max-width: 44rem;
        color: #cbd5e1;
    }
    .glass-card {
        border-radius: 2rem;
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 30px 90px rgba(15, 23, 42, 0.3);
        backdrop-filter: blur(18px);
    }
    .input-field-custom {
        width: 100%;
        min-height: 3rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 1.2rem;
        padding: 1rem 1.2rem;
        color: #f8fafc;
        transition: all 0.28s ease;
        backdrop-filter: blur(14px);
    }
    select.input-field-custom {
        background: #0f172a;
        color: #ffffff;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.2em 1.2em;
        padding-right: 2.8rem;
    }
    select.input-field-custom option {
        background-color: #0f172a;
        color: #ffffff;
        padding: 10px;
    }
    .input-field-custom:focus {
        border-color: rgba(34, 211, 238, 0.95);
        box-shadow: 0 0 24px rgba(34, 211, 238, 0.18);
        outline: none;
        transform: translateY(-1px);
    }
    .form-label {
        display: block;
        margin-bottom: 0.65rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #94a3b8;
        transition: color 0.25s ease;
    }
    .form-group:focus-within .form-label {
        color: #38bdf8;
    }
    .report-preview {
        display: block;
        max-width: 100%;
        max-height: 260px;
        border-radius: 1.5rem;
        object-fit: cover;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .submit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 1rem 1.5rem;
        border-radius: 1.25rem;
        border: none;
        color: #ffffff;
        background: linear-gradient(135deg, #06b6d4, #0ea5e9);
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        box-shadow: 0 22px 50px rgba(6, 182, 212, 0.18);
    }
    .submit-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 28px 60px rgba(6, 182, 212, 0.22);
    }
    .submit-button:active {
        transform: translateY(0px);
    }
    .btn-outline {
        border: 1px solid rgba(148, 163, 184, 0.2);
        color: #cbd5e1;
        background: transparent;
        transition: all 0.25s ease;
        border-radius: 1.25rem;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    .btn-outline:hover {
        border-color: rgba(34, 211, 238, 0.4);
        color: #ffffff;
        background: rgba(14, 165, 233, 0.08);
    }
    .form-error {
        margin-top: 0.5rem;
        font-size: 0.92rem;
        color: #f97316;
    }
    .success-card {
        border-radius: 2rem;
        background: rgba(10, 20, 34, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 60px 120px rgba(5, 10, 20, 0.55);
        backdrop-filter: blur(22px);
    }
    .success-panel {
        padding: 3rem 2.5rem;
        text-align: center;
    }
    .success-title {
        font-size: clamp(1.75rem, 3vw, 2.25rem);
        line-height: 1.2;
        font-weight: 800;
        color: #f8fafc;
        margin: 0 0 1rem 0;
    }
    .success-card {
        animation: successSlide 0.6s ease-out;
    }
    @keyframes successSlide {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @media (max-width: 1024px) {
        .lg\\:grid-cols-\\[1\\.1fr_0\\.9fr\\] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const groups = document.querySelectorAll('.input-group');
        if (groups.length && window.anime) {
            anime({
                targets: groups,
                opacity: [0, 1],
                translateY: [24, 0],
                delay: anime.stagger(90),
                easing: 'easeOutExpo'
            });
        }

        const inputs = document.querySelectorAll('.input-field-custom');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                if (window.anime) {
                    anime({
                        targets: input,
                        borderColor: '#22d3ee',
                        boxShadow: '0 0 24px rgba(34, 211, 238, 0.2)',
                        duration: 220,
                        easing: 'easeOutQuad'
                    });
                }
            });
            input.addEventListener('blur', () => {
                if (window.anime) {
                    anime({
                        targets: input,
                        borderColor: 'rgba(255, 255, 255, 0.12)',
                        boxShadow: '0 0 0 rgba(34, 211, 238, 0)',
                        duration: 220,
                        easing: 'easeOutQuad'
                    });
                }
            });
        });
    });
</script>
@endpush>
