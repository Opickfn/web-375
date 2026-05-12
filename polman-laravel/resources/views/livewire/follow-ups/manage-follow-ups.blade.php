<div x-data="fuAnim()" x-init="init()">
    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Tindak Lanjut</h1>
            <p class="pm-sub">Rencana aksi dan status penyelesaian temuan</p>
        </div>
        <div style="display:flex;gap:0.6rem;">
            <button wire:click="exportFU" class="pm-btn pm-btn-ghost">
                <i data-lucide="download"></i> Export
            </button>

            {{-- HANYA ADMIN & PJ AREA --}}
            @if(!Auth::user()->isPimpinan())
            <button wire:click="openForm" class="pm-btn pm-btn-primary">
                <i data-lucide="plus"></i> Buat Tindak Lanjut
            </button>
            @endif
        </div>
    </div>

    @if($showForm)
    <div class="pm-card" style="margin-bottom:1.25rem;">
        <div class="pm-card-header">
            <h3>Buat Tindak Lanjut Baru</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon"><i data-lucide="x"></i></button>
        </div>
        <div class="pm-card-body">
            <form wire:submit="save">
                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Temuan</label>
                        <select wire:model="reportId" class="pm-select-full">
                            <option value="">Pilih temuan...</option>
                            @foreach($approvedReports as $r)
                            <option value="{{ $r->id }}">{{ $r->code }} — {{ Str::limit($r->lokasi, 35) }}</option>
                            @endforeach
                        </select>
                        @error('reportId') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Ditugaskan Kepada</label>
                        <input type="text" wire:model="assignedTo" class="pm-input" placeholder="Nama penanggung jawab">
                        @error('assignedTo') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Rencana Tindakan</label>
                    <textarea wire:model="actionPlan" class="pm-textarea" rows="3" placeholder="Deskripsikan rencana..."></textarea>
                    @error('actionPlan') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Target Selesai</label>
                    <input type="date" wire:model="targetDate" class="pm-input" min="{{ now()->format('Y-m-d') }}">
                    @error('targetDate') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div style="display:flex;gap:0.6rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="pm-card">
        <div class="pm-table-wrap">
            <table class="pm-table">
                <thead>
                    <tr>
                        <th>Temuan</th>
                        <th>Ditugaskan</th>
                        <th>Rencana</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($followUps as $fu)
                    <tr>
                        <td>
                            <button wire:click="openReportDetail({{ $fu->report->id }})" 
                                    style="color: #3b82f6; font-weight: 600; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                {{ $fu->report->code }}
                            </button>
                        </td>
                        <td>{{ $fu->assigned_to_name }}</td>
                        <td>{{ Str::limit($fu->action_plan, 50) }}</td>
                        <td>{{ $fu->target_date->format('d M Y') }}</td>
                        <td>
                            <div style="margin-bottom: 6px;">
                                <span class="pm-badge {{ $fu->status == 'completed' ? 'pm-badge-success' : 'pm-badge-warning' }}">
                                    {{ ucfirst($fu->status) }}
                                </span>
                            </div>
                            <!-- Progress Bar Animasi -->
                            <div style="width: 100%; max-width: 120px; background-color: #e2e8f0; border-radius: 9999px; height: 6px; overflow: hidden;">
                                <div class="progress-bar-fill" 
                                    style="height: 100%; border-radius: 9999px; background-color: {{ $fu->status == 'completed' ? '#10b981' : '#f59e0b' }}; width: 0%;" 
                                    x-init="setTimeout(() => { $el.style.width = '{{ $fu->status == 'completed' ? '100%' : '50%' }}' }, 150)"></div>
                            </div>
                        </td>
                        <td>
                            @if($fu->status !== 'completed')
                                {{-- HANYA ADMIN & PJ AREA --}}
                                @if(!Auth::user()->isPimpinan())
                                <button wire:click="complete({{ $fu->id }})" class="pm-btn pm-btn-success pm-btn-sm" onclick="return confirm('Tandai selesai?')">
                                    Selesai
                                </button>
                                @else
                                <span class="pm-badge pm-badge-warning" style="font-size: 0.8rem;">Menunggu Eksekusi</span>
                                @endif
                            @else
                                <span class="pm-badge pm-badge-success" style="font-size: 0.8rem;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-8">
                            Belum ada tindak lanjut
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $followUps->links() }}
        </div>
    </div>
    {{-- Container Utama Alpine untuk menampung state Lightbox --}}
    <div x-data="{ lightboxOpen: false, lightboxImage: '' }">

        {{-- MODAL DETAIL --}}
        @if($showModal && $selectedReport)
        <div class="pm-modal-backdrop" 
            style="display: flex; align-items: flex-start; justify-content: center; background: rgba(0, 51, 78, 0.6); backdrop-filter: blur(6px); z-index: 9999; padding-top: 80px; overflow-y: auto;"
            x-data 
            @click.self="$wire.set('showModal', false)">
            
            <div class="pm-modal-card" style="max-width: 400px; width: 90%; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,51,78,0.25); margin-bottom: 40px; background: #fff;">
                
                {{-- Header --}}
                <div class="pm-modal-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fff;">
                    <div>
                        <h2 class="pm-h2" style="font-size: 1rem; margin-bottom: 0;">Detail Temuan</h2>
                        <p style="font-size: 0.7rem; color: #94a3b8;">{{ $selectedReport->code }}</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="pm-btn-close" style="font-size: 1.2rem; color: #94a3b8; line-height: 1;">&times;</button>
                </div>
                
                <div class="pm-modal-body" style="padding: 1.25rem;">
                    
                    {{-- Info Row: Pengusul & Prioritas --}}
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.75rem; color: #64748b; padding-bottom: 0.75rem; border-bottom: 1px solid #f8fafc;">
                        <span>Oleh: <strong>{{ $selectedReport->user->full_name ?? 'Publik' }}</strong></span>
                        <span style="text-transform: uppercase; font-weight: 700; color: #3b82f6;">{{ $selectedReport->prioritas }}</span>
                    </div>

                    {{-- LOKASI (Tambahan Baru agar tidak terpotong) --}}
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 4px;">
                            <i data-lucide="map-pin" style="width: 10px; height: 10px;"></i> Lokasi Temuan
                        </label>
                        <div style="padding: 0.75rem; background: #f1f5f9; border-radius: 8px; font-size: 0.8rem; color: #1e293b; line-height: 1.5;">
                            {{-- Panggil fungsi location_breadcrumb yang sama dengan di tabel --}}
                            <div style="font-weight: 600;">
                                {{ $selectedReport->location_breadcrumb ?? 'Lokasi tidak ditemukan' }}
                            </div>
                            
                            {{-- Jika ada detail tambahan seperti nomor meja atau pojok ruangan --}}
                            @if($selectedReport->detail_lokasi)
                                <div style="margin-top: 4px; padding-top: 4px; border-top: 1px solid #e2e8f0; font-style: italic; font-size: 0.75rem; color: #64748b;">
                                    Catatan: {{ $selectedReport->detail_lokasi }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Deskripsi --}}
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 4px;">Deskripsi</label>
                        <div style="padding: 0.75rem; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 8px; font-size: 0.8rem; color: #334155; line-height: 1.4;">
                            {{ $selectedReport->deskripsi }}
                        </div>
                    </div>

                    {{-- Solusi --}}
                    @if($selectedReport->solusi)
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: #10b981; text-transform: uppercase; margin-bottom: 4px;">
                            <i data-lucide="lightbulb" style="width: 10px; height: 10px;"></i> Solusi Disarankan
                        </label>
                        <div style="padding: 0.75rem; background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 8px; font-size: 0.8rem; font-style: italic; color: #166534;">
                            "{{ $selectedReport->solusi }}"
                        </div>
                    </div>
                    @endif

                    {{-- Bukti Foto (Klik untuk Zoom) --}}
                    @if($selectedReport->bukti)
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Bukti Foto (Klik gambar)</label>
                        <div style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; cursor: pointer;" 
                            @click="lightboxImage = '{{ asset('storage/uploads/' . $selectedReport->bukti) }}'; lightboxOpen = true">
                            <img src="{{ asset('storage/uploads/' . $selectedReport->bukti) }}" 
                                style="width: 100%; max-height: 160px; object-fit: cover; display: block; transition: 0.3s;"
                                onmouseover="this.style.transform='scale(1.05)'" 
                                onmouseout="this.style.transform='scale(1)'">
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pm-modal-footer" style="padding: 0.85rem 1.25rem; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                    <button wire:click="$set('showModal', false)" 
                            class="pm-btn pm-btn-primary" 
                            style="width: 100%; padding: 0.5rem; border-radius: 8px; font-size: 0.85rem;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- MODAL LIGHTBOX (Zoom Foto) --}}
        <template x-if="lightboxOpen">
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 11000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(8px);"
                @click.self="lightboxOpen = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">
                
                <div style="position: relative; max-width: 95%; max-height: 90vh;">
                    <button @click="lightboxOpen = false" 
                            style="position: absolute; top: -45px; right: 0; color: white; font-size: 2.5rem; background: none; border: none; cursor: pointer;">
                        &times;
                    </button>
                    <img :src="lightboxImage" 
                        style="max-width: 100%; max-height: 85vh; border-radius: 4px; box-shadow: 0 0 30px rgba(0,0,0,0.5); border: 3px solid white;">
                </div>
            </div>
        </template>

    </div>
</div>

@push('scripts')
<script>
function fuAnim() {
    return {
        init() {},
        animRows() {}
    };
}
</script>
@endpush
