<?php

namespace App\Models;

use App\Casts\EncryptedString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'business_type',
        'business_name',
        'niche',
        'instagram',
        'website',
        'marketing_email',
        'marketing_whatsapp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'phone' => EncryptedString::class,
            // 'instagram' => \App\Casts\EncryptedString::class,
        ];
    }
}
