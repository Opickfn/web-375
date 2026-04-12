<?php

namespace App\Models;

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

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isReporter(): bool
    {
        return $this->role === 'reporter';
    }

    public function isManagerOrAbove(): bool
    {
        return in_array($this->role, ['manager', 'admin']);
    }

    public function canCreateReport(): bool
    {
        return $this->role === 'reporter';
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
