<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'show_name_on_landing',
        'user_type',
        'phone',
        // Mahasiswa
        'nim',
        'kelas',
        'gedung',
        'ruangan',
        'tahun_angkatan',
        // Dosen
        'nomor_dosen',
        'jabatan',
        'gedung_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'show_name_on_landing' => 'boolean',
        ];
    }

    // --- Role Helpers ---

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isReporter(): bool
    {
        return $this->role === 'reporter';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isSpmi(): bool
    {
        return $this->role === 'spmi';
    }
    
    public function isPjArea(): bool
    {
        return $this->role === 'pj_area';
    }

    public function isPjAreaOrAbove(): bool
    {
        return in_array($this->role, ['admin', 'pimpinan', 'spmi', 'pj_area']);
    }

    public function canCreateReport(): bool
    {
        return $this->role === 'reporter';
    }

    public function assignedLocations()
    {
        return $this->belongsToMany(Location::class, 'location_user')->withTimestamps();
    }

    public function getAssignedLocationLabelsAttribute(): string
    {
        return $this->assignedLocations->pluck('full_path')->join(', ');
    }

    public function getManagedLocationIds(): array
    {
        if ($this->isAdmin()) {
            return [];
        }

        $assignedIds = $this->assignedLocations()->pluck('locations.id')->all();

        if (empty($assignedIds)) {
            return [-1]; 
        }

        $allIds = $assignedIds;
        $currentIds = $assignedIds;

        while (!empty($currentIds)) {
            $childIds = \App\Models\Location::whereIn('parent_id', $currentIds)->pluck('id')->all();
            $currentIds = array_diff($childIds, $allIds);
            if (empty($currentIds)) break;
            $allIds = array_merge($allIds, $currentIds);
        }

        return array_values(array_unique($allIds));
    }

    // --- Type Helpers ---

    public function isMahasiswa(): bool
    {
        return $this->user_type === 'mahasiswa';
    }

    public function isDosen(): bool
    {
        return $this->user_type === 'dosen';
    }

    public function isUmum(): bool
    {
        return $this->user_type === 'umum';
    }

    public function getUserTypeLabelAttribute(): string
    {
        return match ($this->user_type) {
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'umum' => 'Umum',
            default => $this->user_type,
        };
    }

    // --- Relationships ---

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function reviewedReports()
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }

    public function gedungRelation()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'created_by');
    }

    // --- Point Helpers ---

    public function totalPoints(): int
    {
        return (int) $this->points()->sum('amount');
    }

    public function totalPointsInPeriod(RewardPeriod $period): int
    {
        return (int) $this->points()
            ->whereBetween('created_at', [$period->start_date, $period->end_date])
            ->sum('amount');
    }
}
