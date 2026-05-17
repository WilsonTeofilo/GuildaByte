<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityEvent extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
