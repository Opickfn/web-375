<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gedung extends Model
{
    protected $table = 'gedungs';

    protected $fillable = ['kode', 'nama', 'nama_en', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function ruangans(): HasMany
    {
        return $this->hasMany(Ruangan::class);
    }

    public function activeRuangan(): HasMany
    {
        return $this->hasMany(Ruangan::class)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute(): string
    {
        return $this->nama_en
            ? "{$this->nama_en} / {$this->nama}"
            : $this->nama;
    }
}
