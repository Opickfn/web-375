<div class="report-creation-shell">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto">
            {{-- Header Section --}}
            <div class="mb-8 animate-in">
                <h1 class="text-3xl font-black text-white mb-2 flex items-center gap-3">
                    <i data-lucide="plus-circle" class="text-[#f59e0b]"></i>
                    Buat Temuan Internal
                </h1>
                <p class="text-slate-400">Kontribusi Anda adalah langkah awal menuju lingkungan kerja yang lebih aman dan efisien.</p>
            </div>

            @if($showSuccess && $successReport)
                {{-- Success State --}}
                <div class="glass-card overflow-hidden animate-in">
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-green-500/20 border border-green-500/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="check-circle-2" class="w-10 h-10 text-green-500"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Temuan Berhasil Terkirim!</h2>
                        <p class="text-slate-400 mb-8">Terima kasih atas kontribusi Anda. Tim manajemen akan segera meninjau temuan ini.</p>
                        
                        <div class="grid sm:grid-cols-2 gap-4 mb-8 text-left">
                            <div class="p-4 rounded-xl bg-[#00263a] border border-white/5">
                                <span class="text-[10px] uppercase tracking-widest text-slate-500 block mb-1">Kode Temuan</span>
                                <span class="text-lg font-bold text-[#f59e0b]">{{ $successReport->code }}</span>
                            </div>
                            <div class="p-4 rounded-xl bg-[#00263a] border border-white/5">
                                <span class="text-[10px] uppercase tracking-widest text-slate-500 block mb-1">Status</span>
                                <span class="text-lg font-bold text-white capitalize">{{ $successReport->status }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button wire:click="resetForm" class="btn-auth-primary" style="margin-top:0;">
                                <span>Buat Temuan Lagi</span>
                                <i data-lucide="refresh-cw"></i>
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn-secondary">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- Form Section --}}
                <div class="glass-card animate-in">
                    <form wire:submit="submit" class="p-8 space-y-8">
                        {{-- Row 1: Kategori, Judul (Detail), Prioritas --}}
                        <div class="grid lg:grid-cols-3 gap-6">
                            <div class="form-field">
                                <label class="field-label flex items-center gap-2">
                                    <i data-lucide="tag" class="w-3.5 h-3.5 text-[#f59e0b]"></i>
                                    Kategori Temuan
                                </label>
                                <div class="input-container">
                                    <select wire:model="kategori" class="modern-input appearance-none text-slate-900 bg-white" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="K3">K3 - Keselamatan</option>
                                        <option value="5R">5R - Ringkas, Rapi...</option>
                                        <option value="7S">7S - Budaya Industri</option>
                                    </select>
                                </div>
                                @error('kategori') <p class="field-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-field lg:col-span-1">
                                <label class="field-label flex items-center gap-2">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-[#f59e0b]"></i>
                                    Judul / Ringkasan
                                </label>
                                <div class="input-container">
                                    <input type="text" wire:model="detail_lokasi" class="modern-input text-slate-900 bg-white" placeholder="Contoh: Lampu Rusak..." required>
                                </div>
                                @error('detail_lokasi') <p class="field-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-field">
                                <label class="field-label flex items-center gap-2">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-[#f59e0b]"></i>
                                    Prioritas
                                </label>
                                <div class="input-container">
                                    <select wire:model="prioritas" class="modern-input appearance-none text-slate-900 bg-white" required>
                                        <option value="rendah">Rendah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="tinggi">Tinggi</option>
                                    </select>
                                </div>
                                @error('prioritas') <p class="field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Row 2: Location Selection --}}
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-white/50 uppercase tracking-[0.2em] flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                Detail Lokasi
                            </h3>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="form-field">
                                    <select wire:model="campus_id" wire:change="$refresh" class="modern-input appearance-none text-slate-900 bg-white" required>
                                        <option value="">Pilih Kampus</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-field">
                                    <select wire:model="branch_id" wire:change="$refresh" class="modern-input appearance-none text-slate-900 bg-white" {{ !$campus_id ? 'disabled' : '' }} required>
                                        <option value="">Pilih Gedung</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ ucwords($branch->type) }} - {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($branchType === 'gedung')
                                    <div class="form-field">
                                        <select wire:model="floor_id" wire:change="$refresh" class="modern-input appearance-none text-slate-900 bg-white" required>
                                            <option value="">Pilih Lantai</option>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-field">
                                        <select wire:model="space_id" class="modern-input appearance-none text-slate-900 bg-white" required>
                                            <option value="">Pilih Ruang</option>
                                            @foreach($spaces as $space)
                                                <option value="{{ $space->id }}">{{ ucfirst($space->type) }} - {{ $space->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Row 3: Description --}}
                        <div class="form-field">
                            <label class="field-label flex items-center gap-2">
                                <i data-lucide="align-left" class="w-3.5 h-3.5 text-[#f59e0b]"></i>
                                Deskripsi Kejadian / Temuan
                            </label>
                            <div class="input-container">
                                <textarea wire:model="deskripsi" rows="5" class="modern-input text-slate-900 bg-white min-h-[120px]" placeholder="Jelaskan detail temuan Anda di sini..."></textarea>
                            </div>
                            @error('deskripsi') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Row 4: Evidence & Solution --}}
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div class="form-field">
                                <label class="field-label flex items-center gap-2">
                                    <i data-lucide="camera" class="w-3.5 h-3.5 text-[#f59e0b]"></i>
                                    Bukti Visual
                                </label>
                                <div class="upload-area" onclick="document.getElementById('bukti').click()">
                                    <input type="file" id="bukti" wire:model="bukti" accept="image/*" class="hidden">
                                    @if($bukti)
                                        <div class="relative w-full h-full p-2">
                                            <img src="{{ $bukti->temporaryUrl() }}" class="w-full h-48 object-cover rounded-xl shadow-lg">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition-opacity rounded-xl">
                                                <p class="text-white text-xs font-bold">Klik untuk ganti foto</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center py-10 px-4 text-center cursor-pointer">
                                            <div class="w-12 h-12 rounded-full bg-[#f59e0b]/10 flex items-center justify-center mb-3">
                                                <i data-lucide="image-plus" class="w-6 h-6 text-[#f59e0b]"></i>
                                            </div>
                                            <p class="text-white text-sm font-bold">Klik atau seret foto ke sini</p>
                                            <p class="text-slate-500 text-[10px] mt-1">PNG, JPG (Maks. 5MB)</p>
                                        </div>
                                    @endif
                                </div>
                                @error('bukti') <p class="field-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-field">
                                <label class="field-label flex items-center gap-2">
                                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#f59e0b]"></i>
                                    Saran Solusi (Opsional)
                                </label>
                                <div class="input-container h-full">
                                    <textarea wire:model="solusi" class="modern-input text-slate-900 bg-white h-[200px]" placeholder="Apa langkah perbaikan yang Anda sarankan?"></textarea>
                                </div>
                                @if($solusi)
                                    <p class="mt-2 text-[10px] text-green-400 flex items-center gap-1">
                                        <i data-lucide="star" class="w-3 h-3"></i>
                                        Poin tambahan +5 akan diberikan untuk saran solusi.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-white/5">
                            <button type="button" wire:click="resetForm" class="btn-secondary flex-1">Batal</button>
                            <button type="submit" class="btn-auth-primary flex-[2]" style="margin-top:0;">
                                <span>Kirim Temuan Internal</span>
                                <i data-lucide="send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .report-creation-shell {
        min-height: 100vh;
        background-color: #001524;
        background-image: 
            radial-gradient(at 0% 0%, rgba(0, 51, 78, 0.4) 0, transparent 50%), 
            radial-gradient(at 100% 100%, rgba(0, 51, 78, 0.2) 0, transparent 50%);
    }
    .glass-card {
        background: #00334E;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .modern-input {
        width: 100%;
        background: #f8fafc;
        border: 2px solid transparent;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        color: #1e293b;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .modern-input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }
    .field-error {
        color: #f87171;
        font-size: 0.7rem;
        margin-top: 0.3rem;
        font-weight: 600;
    }
    .upload-area {
        background: #00263a;
        border: 2px dashed rgba(245, 158, 11, 0.3);
        border-radius: 1rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .upload-area:hover {
        border-color: #f59e0b;
        background: #002e47;
    }
    .btn-auth-primary {
        background: #f59e0b;
        color: #001524;
        font-weight: 800;
        padding: 1rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
    }
    .btn-auth-primary:hover {
        background: #fbbf24;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);
    }
    .btn-secondary {
        background: rgba(255, 255, 255, 0.05);
        color: white;
        font-weight: 700;
        padding: 1rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .animate-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    select.modern-input option {
        color: #1e293b;
        background: white;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();
    });
    
    // Refresh icons after Livewire updates
    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            if (window.lucide) lucide.createIcons();
        });
    });
</script>
@endpush>
