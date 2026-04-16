<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'auditor_id',
        'pj_area_id',
        'location_id',
        'title',
        'findings',
        'recommendations',
        'audit_date',
        'status',
        'pdf_path',
    ];

    protected $casts = [
        'audit_date' => 'date',
    ];

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function pjArea()
    {
        return $this->belongsTo(User::class, 'pj_area_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
