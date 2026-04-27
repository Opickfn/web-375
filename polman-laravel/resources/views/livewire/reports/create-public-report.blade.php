<div class="public-report-shell">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="public-report-card w-full max-w-5xl">
            <div class="public-report-inner">
                <div class="public-report-header mb-10">
                    <h1>Buat Laporan Publik</h1>
                        <p class="text-[#475569] leading-relaxed">
                            Tambahkan temuan lapangan Anda dengan detail, foto, dan prioritas agar tim kampus dapat menindaklanjuti lebih cepat.
                    </p>
                </div>

                @if($showSuccess && $successReport)
                    <div class="success-card">
                        <div class="success-panel">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#E6F4E8] text-[#00334E] mb-6 shadow-[0_20px_60px_rgba(0,51,78,0.12)]">
                                <i data-lucide="check-circle" class="w-10 h-10"></i>
                            </div>
                            <h2 class="success-title">Laporan Berhasil Dikirim!</h2>
                            <p class="text-[#475569] max-w-2xl mx-auto mb-8">Terima kasih atas kontribusi Anda. Laporan telah terkirim dan akan ditinjau oleh tim manajemen kampus.</p>

                            <div class="grid gap-4 sm:grid-cols-2 mb-8 text-left text-[#00334E]">
                                <div class="rounded-3xl border border-[#A0AEC0]/30 bg-white/80 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-[#475569]">Kode Laporan</span>
                                    <p class="mt-3 text-xl font-semibold">{{ $successReport->code }}</p>
                                </div>
                                <div class="rounded-3xl border border-[#A0AEC0]/30 bg-white/80 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-[#475569]">Kategori</span>
                                    <p class="mt-3 text-xl font-semibold">{{ $successReport->kategori }}</p>
                                </div>
                                <div class="rounded-3xl border border-[#A0AEC0]/30 bg-white/80 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-[#475569]">Prioritas</span>
                                    <p class="mt-3 text-xl font-semibold capitalize">{{ $successReport->prioritas }}</p>
                                </div>
                                <div class="rounded-3xl border border-[#A0AEC0]/30 bg-white/80 p-5">
                                    <span class="text-xs uppercase tracking-[0.18em] text-[#475569]">Lokasi</span>
                                    <p class="mt-3 text-xl font-semibold">{{ $successReport->location_breadcrumb }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                                <button wire:click="resetForm" class="submit-button px-6 py-4 w-full sm:w-auto">Buat Laporan Baru</button>
                                <a href="{{ route('home') }}" class="btn btn-outline btn-lg px-6 py-4 text-center w-full sm:w-auto">Kembali ke Beranda</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] items-start">
                        <div class="glass-card p-8">
                            <div class="mb-6 rounded-3xl bg-[#E6F4E8]/70 border border-[#00334E]/10 p-5">
                                <p class="text-[#00334E] font-medium">Form Laporan Publik</p>
                                <p class="mt-2 text-[#475569] text-sm leading-relaxed">Cocok untuk temuan umum dan pelanggaran lingkungan kerja. Laporan ini akan diproses oleh tim manajemen kampus.</p>
                            </div>

                            <form wire:submit.prevent="submit" class="space-y-5">
                                <div class="form-group input-group">
                                    <label class="form-label" for="kategori">Kategori Pelanggaran</label>
                                    <select wire:model="kategori" id="kategori" class="input-field-custom appearance-none bg-white text-[#00334E]">
                                        <option value="">Pilih kategori</option>
                                        <option value="5R">5R (Ringkas, Rapi, Resik, Rawat, Rajin)</option>
                                        <option value="7S">7S (Seiri, Seiton, Seiso, Seiketsu, Shitsuke, Safety, Spirit/Security)</option>
                                        <option value="K3">K3 (Keselamatan dan Kesehatan Kerja)</option>
                                    </select>
                                    @error('kategori') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="campus_id">Kampus</label>
                                    <select wire:model="campus_id" id="campus_id" wire:change="$refresh" class="input-field-custom appearance-none bg-white text-[#00334E]">
                                        <option value="">Pilih kampus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('campus_id') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="branch_id">Gedung/Infrastruktur</label>
                                    <select id="branch_id" wire:key="public-branch-{{ $campus_id ?: 'none' }}" wire:model="branch_id" wire:change="$refresh" class="input-field-custom appearance-none bg-white text-[#00334E]" @disabled(!$campus_id)>
                                        <option value="">Pilih Gedung/Infrastruktur</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" data-type="{{ $branch->type }}">{{ ucfirst($branch->type) }} - {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id') <p class="form-error">{{ $message }}</p> @enderror
                                    @if($branchType === 'gedung')
                                        <p class="text-sm text-[#475569] mt-2">Pilih gedung terlebih dahulu, lalu lanjutkan ke lantai dan ruang.</p>
                                    @elseif($branchType === 'infrastruktur')
                                        <p class="text-sm text-[#475569] mt-2">Infrastruktur umum dipilih langsung sebagai lokasi akhir.</p>
                                    @endif
                                </div>

                                @if($branchType === 'gedung')
                                    <div class="form-group input-group">
                                        <label class="form-label" for="floor_id">Lantai</label>
                                        <select id="floor_id" wire:model="floor_id" wire:change="$refresh" class="input-field-custom appearance-none bg-white text-[#00334E]">
                                            <option value="">Pilih lantai</option>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('floor_id') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>

                                    @if($floor_id)
                                        <div class="form-group input-group">
                                            <label class="form-label" for="space_id">Ruang / Area</label>
                                            <select id="space_id" wire:model="space_id" class="input-field-custom appearance-none bg-white text-[#00334E]">
                                                <option value="">Pilih ruangan atau area</option>
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
                                    <input id="detail_lokasi" type="text" wire:model="detail_lokasi" class="input-field-custom" placeholder="Contoh: Dekat pintu masuk area kerja">
                                    @error('detail_lokasi') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="deskripsi">Deskripsi Temuan</label>
                                    <textarea id="deskripsi" wire:model="deskripsi" rows="5" class="input-field-custom" placeholder="Jelaskan detail temuan yang ditemukan di lapangan..."></textarea>
                                    @error('deskripsi') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="prioritas">Tingkat Prioritas</label>
                                    <select id="prioritas" wire:model="prioritas" class="input-field-custom appearance-none bg-white text-[#00334E]">
                                        <option value="rendah">Rendah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="tinggi">Tinggi</option>
                                    </select>
                                    @error('prioritas') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="form-group input-group">
                                    <label class="form-label" for="bukti">Bukti Foto (opsional)</label>
                                    <input id="bukti" type="file" wire:model="bukti" accept="image/*" class="input-field-custom cursor-pointer" />
                                    @error('bukti') <p class="form-error">{{ $message }}</p> @enderror

                                    @if($bukti)
                                        @php
                                            $previewBorder = $kategori === 'K3'
                                                ? 'border-cyan-400 shadow-[0_0_25px_rgba(34,211,238,0.25)]'
                                                : ($kategori === '7S'
                                                    ? 'border-rose-400 shadow-[0_0_25px_rgba(244,63,94,0.25)]'
                                                    : ($kategori === '5R'
                                                        ? 'border-emerald-400 shadow-[0_0_25px_rgba(34,197,94,0.25)]'
                                                        : 'border-white/10 shadow-[0_0_15px_rgba(255,255,255,0.08)]'));
                                        @endphp
                                        <img src="{{ $bukti->temporaryUrl() }}" alt="Preview"
                                             class="report-preview mt-4 rounded-3xl border {{ $previewBorder }}" />
                                    @endif
                                </div>

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pt-2">
                                    <button type="submit" class="submit-button w-full sm:w-auto py-4 font-semibold">Kirim Laporan Safety</button>
                                    <a href="{{ route('home') }}" class="btn btn-outline btn-lg w-full sm:w-auto text-center py-4">Kembali</a>
                                </div>
                            </form>
                        </div>

                        <div class="glass-card p-8 border border-[#A0AEC0]/30 bg-white/85 shadow-[0_30px_90px_rgba(0,51,78,0.08)]">
                            <div class="rounded-[2rem] border border-[#00334E]/10 bg-[#E6F4E8]/50 p-6 mb-6">
                                <h3 class="text-xl font-semibold text-[#00334E]">Ringkasan</h3>
                                <p class="mt-3 text-[#475569] leading-relaxed">Isi laporan lengkap dengan lokasi, kategori, dan bukti akan membantu tim memproses lebih cepat. Semakin lengkap, semakin baik.</p>
                            </div>
                            <div class="space-y-4 text-[#475569] text-sm leading-7">
                                <div>
                                    <span class="inline-flex rounded-full bg-[#E6F4E8] px-3 py-1 text-[#00334E] text-xs uppercase tracking-[0.2em]">Pro tip</span>
                                    <p class="mt-3">Pilih kategori yang paling relevan dan tambahkan detail lokasi agar tim tidak perlu menebak.</p>
                                </div>
                                <div>
                                    <span class="inline-flex rounded-full bg-[#E6F4E8] px-3 py-1 text-[#00334E] text-xs uppercase tracking-[0.18em]">Fokus</span>
                                    <p class="mt-3">Gunakan foto yang jelas, dan jangan lupa cantumkan lantai atau area agar penanganan lebih cepat.</p>
                                </div>
                                <div>
                                    <span class="inline-flex rounded-full bg-[#E6F4E8] px-3 py-1 text-[#00334E] text-xs uppercase tracking-[0.18em]">Keamanan</span>
                                    <p class="mt-3">Laporan ini akan diproses secara publik oleh tim internal. Pastikan informasi yang dikirim relevan dan sopan.</p>
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
        background: radial-gradient(circle at top right, rgba(0, 51, 78, 0.06), transparent 28%),
                    radial-gradient(circle at bottom left, rgba(160, 174, 192, 0.08), transparent 22%),
                    linear-gradient(180deg, #F0F9F4 0%, #E6F4E8 100%);
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
        background: rgba(0, 51, 78, 0.08);
    }
    .public-report-shell::after {
        width: 420px;
        height: 420px;
        bottom: -140px;
        left: -120px;
        background: rgba(230, 244, 232, 0.5);
    }
    .public-report-card {
        border-radius: 2rem;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(160, 174, 192, 0.35);
        box-shadow: 0 60px 120px rgba(0, 51, 78, 0.1);
        backdrop-filter: blur(22px);
    }
    .public-report-inner {
        padding: 2.5rem;
    }
    .public-report-header h1 {
        font-size: clamp(2.5rem, 4vw, 3.6rem);
        line-height: 1;
        font-weight: 800;
        color: #00334E;
    }
    .public-report-header p {
        margin-top: 0.9rem;
        max-width: 44rem;
        color: #475569;
    }
    .glass-card {
        border-radius: 2rem;
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(160, 174, 192, 0.3);
        box-shadow: 0 30px 90px rgba(0, 51, 78, 0.08);
        backdrop-filter: blur(18px);
    }
    .input-field-custom {
        width: 100%;
        min-height: 3rem;
        background: #FFFFFF;
        border: 1px solid #A0AEC0;
        border-radius: 1.2rem;
        padding: 1rem 1.2rem;
        color: #00334E;
        transition: all 0.28s ease;
    }
    select.input-field-custom {
        background: #FFFFFF;
        color: #00334E;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2300334E' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.2em 1.2em;
        padding-right: 2.8rem;
    }
    select.input-field-custom option {
        background-color: #FFFFFF;
        color: #00334E;
        padding: 10px;
    }
    select {
        color-scheme: light;
    }
    .input-field-custom:focus {
        border-color: #00334E;
        box-shadow: 0 0 24px rgba(0, 51, 78, 0.12);
        outline: none;
        transform: translateY(-1px);
    }
    .form-label {
        display: block;
        margin-bottom: 0.65rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #00334E;
        transition: color 0.25s ease;
    }
    .form-group:focus-within .form-label {
        color: #00334E;
    }
    .report-preview {
        display: block;
        max-width: 100%;
        max-height: 260px;
        border-radius: 1.5rem;
        object-fit: cover;
        border: 1px solid rgba(160, 174, 192, 0.3);
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
        background: #00334E;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        box-shadow: 0 22px 50px rgba(0, 51, 78, 0.18);
    }
    .submit-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 28px 60px rgba(0, 51, 78, 0.25);
        background: #001f35;
    }
    .btn-outline {
        border: 1px solid #A0AEC0;
        color: #00334E;
        background: transparent;
        transition: all 0.25s ease;
        border-radius: 1.25rem;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    .btn-outline:hover {
        border-color: #00334E;
        color: #00334E;
        background: rgba(0, 51, 78, 0.06);
    }
    .form-error {
        margin-top: 0.5rem;
        font-size: 0.92rem;
        color: #f97316;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const groups = document.querySelectorAll('.input-group');
        if (!groups.length) return;

        if (window.anime && typeof anime === 'function') {
            anime({
                targets: groups,
                opacity: [0, 1],
                translateY: [24, 0],
                delay: anime.stagger(90),
                easing: 'easeOutExpo'
            });
        } else {
            groups.forEach((group, index) => {
                setTimeout(() => group.classList.add('visible'), index * 90);
            });
        }

        const inputs = document.querySelectorAll('.input-field-custom');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                if (window.anime && typeof anime === 'function') {
                    anime({
                        targets: input,
                        borderColor: '#00334E',
                        boxShadow: '0 0 24px rgba(0, 51, 78, 0.15)',
                        duration: 220,
                        easing: 'easeOutQuad'
                    });
                }
            });
            input.addEventListener('blur', () => {
                if (window.anime && typeof anime === 'function') {
                    anime({
                        targets: input,
                        borderColor: '#A0AEC0',
                        boxShadow: '0 0 0 rgba(0, 51, 78, 0)',
                        duration: 220,
                        easing: 'easeOutQuad'
                    });
                }
            });
        });
    });
</script>
@endpush
