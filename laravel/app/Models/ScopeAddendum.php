<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScopeAddendum extends Model
{
    use SoftDeletes;
    
    protected $table = 'scope_addendums';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'additional_cost',
        'additional_days',
        'status',
        'accepted_at',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'additional_cost' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
