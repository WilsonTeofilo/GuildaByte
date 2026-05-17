<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'start_time',
        'is_booked',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'is_booked' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
