<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $fillable = ['kode', 'nama', 'nama_en', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function programStudis(): HasMany
    {
        return $this->hasMany(ProgramStudi::class);
    }

    public function activeProdi(): HasMany
    {
        return $this->hasMany(ProgramStudi::class)->where('is_active', true);
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
