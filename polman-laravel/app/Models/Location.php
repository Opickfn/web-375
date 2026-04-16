<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'type',
        'category',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function getLabelAttribute(): string
    {
        return $this->code
            ? "{$this->code} - {$this->name}"
            : $this->name;
    }

    public function getFullPathAttribute(): string
    {
        $segments = [];
        $current = $this;

        while ($current) {
            $segments[] = $current->name;
            $current = $current->parent;
        }

        return implode(' > ', array_reverse($segments));
    }
}
