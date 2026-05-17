<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageVersion extends Model
{
    protected $fillable = [
        'package_id',
        'version_number',
        'base_price',
        'setup_deadline_days',
        'description',
        'included_items',
        'excluded_items',
        'is_current',
        'valid_from',
        'valid_until',
        'created_by'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'included_items' => 'json',
        'excluded_items' => 'json',
        'is_current' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
