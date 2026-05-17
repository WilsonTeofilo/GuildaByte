<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractAcceptance extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'ip_address',
        'user_agent',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
