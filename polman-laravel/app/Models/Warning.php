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
        $url = asset('images/polman.png'); // Verified fallback

        if ($this->report_id && $this->report && $this->report->bukti) {
            $url = asset('storage/uploads/' . $this->report->bukti);
        } elseif ($this->image_path) {
            $url = asset('storage/warnings/' . $this->image_path);
        }

        // Handle spaces and ensure it's an absolute URL with correct port
        return str_replace(' ', '%20', $url);
    }
}

