<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'project_id',
        'client_id',
        'scope',
        'proposed_value',
        'proposed_deadline_days',
        'includes',
        'excludes',
        'status',
    ];

    protected $casts = [
        'proposed_value' => 'decimal:2',
        'includes' => 'json',
        'excludes' => 'json',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
