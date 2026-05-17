<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['slug', 'name', 'is_active', 'is_visible'];

    public function versions()
    {
        return $this->hasMany(PackageVersion::class);
    }

    public function currentVersion()
    {
        return $this->hasOne(PackageVersion::class)->where('is_current', true);
    }
}
