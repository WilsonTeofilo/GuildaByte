<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = ['code', 'description', 'discount_type', 'discount_value', 'starts_at', 'ends_at', 'is_active'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'promotion_packages');
    }
}
