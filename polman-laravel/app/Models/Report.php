<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'kategori',
        'lokasi',
        'deskripsi',
        'prioritas',
        'status',
        'bukti',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    // --- Relationships ---

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    // --- Scopes ---

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('deskripsi', 'ILIKE', "%{$keyword}%")
              ->orWhere('lokasi', 'ILIKE', "%{$keyword}%");
        });
    }

    // --- Helpers ---

    public function getCodeAttribute(): string
    {
        return 'LP' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'in_progress' => 'Dalam Proses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getPrioritasLabelAttribute(): string
    {
        return match ($this->prioritas) {
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            default => $this->prioritas,
        };
    }
}
