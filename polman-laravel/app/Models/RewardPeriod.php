<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardPeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function isActive(): bool
    {
        $rawStatus = $this->attributes['status'] ?? 'active';
        if ($rawStatus === 'closed') {
            return false;
        }
        return now()->lte($this->end_date ?? now());
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        $rawStatus = $this->attributes['status'] ?? 'active';

        if ($rawStatus === 'closed' || ($this->end_date && $now->gt($this->end_date))) {
            return 'selesai';
        }
        if ($this->start_date && $now->lt($this->start_date)) {
            return 'akan-datang';
        }
        return 'berjalan';
    }

    public function getStatusLabelAttribute(): string
    {
        $status = $this->getStatusAttribute();
        return match ($status) {
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            'akan-datang' => 'Akan Datang',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        $status = $this->getStatusAttribute();
        return match ($status) {
            'berjalan' => 'success',
            'selesai' => 'danger',
            'akan-datang' => 'warning',
            default => 'neutral',
        };
    }

    public function toggleActive(bool $active): void
    {
        $this->attributes['status'] = $active ? 'active' : 'closed';
        $this->save();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    public function scopeClosed($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'closed')
              ->orWhere('end_date', '<', now());
        });
    }
}

