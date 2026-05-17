<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialEvent extends Model
{
    protected $fillable = [
        'project_id',
        'event_type',
        'old_value',
        'new_value',
        'reason',
        'created_by'
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
