<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    protected $fillable = [
        'report_id',
        'created_by',
        'title',
        'description',
        'severity',
        'status',
        'is_public',
        'expires_at',
        'image_path',
        'image_source',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    // --- Relationships ---

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // --- Helpers ---

    public function getSeverityLabelAttribute(): string
    {
        return match ($this->severity) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            default => $this->severity,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'expired' => 'Expired',
            default => $this->status,
        };
    }

    public function getImageUrlAttribute(): string
    {
        // Jika sumbernya report, pastikan report_id dan file bukti ada[cite: 3]
        if ($this->image_source === 'report' && $this->report_id && $this->report && $this->report->bukti) {
            return asset('storage/uploads/' . $this->report->bukti);
        } 
        
        // Jika manual, pastikan image_path tidak kosong[cite: 3]
        if ($this->image_source === 'manual' && $this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        // Fallback: Jika data lama tidak punya 'image_source', coba deteksi otomatis
        if ($this->report_id && $this->report?->bukti) {
            return asset('storage/uploads/' . $this->report->bukti);
        }

        return asset('images/polman.png');
    }
}

