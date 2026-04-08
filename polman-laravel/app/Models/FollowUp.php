<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'report_id',
        'created_by',
        'assigned_to_name',
        'action_plan',
        'target_date',
        'status',
        'completed_at',
        'completion_notes',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'planned' => 'Direncanakan',
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            default => $this->status,
        };
    }
}
