<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'call_slot_id',
        'type',
        'status',
        'scheduled_at',
        'agenda',
        'rejection_reason',
        'cancellation_reason',
        'admin_notes',
        'checklist',
        'meet_link',
        'duration_minutes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'checklist' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function slot()
    {
        return $this->belongsTo(CallSlot::class, 'call_slot_id');
    }
}
