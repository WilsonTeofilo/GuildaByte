<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBusiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'cnpj',
        'address',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
