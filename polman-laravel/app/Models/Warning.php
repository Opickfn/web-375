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
        $default = 'https://images.unsplash.com/photo-1519337265831-281ec6cc8514?auto=format&fit=crop&w=1200&q=80';

        if ($this->image_source === 'report' && $this->report && $this->report->bukti) {
            return $this->report->bukti;
        }

        if ($this->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists('warnings/' . $this->image_path)) {
            return \Illuminate\Support\Facades\Storage::url('warnings/' . $this->image_path);
        }

        return $default;
    }
}

