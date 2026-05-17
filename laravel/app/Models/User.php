<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->user_type, ['root', 'admin', 'employee']);
    }

    /** Verifica se o usuário é um cliente */
    public function isClient(): bool
    {
        return $this->user_type === 'client';
    }

    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    public function clientBusinesses()
    {
        return $this->hasMany(ClientBusiness::class, 'client_id');
    }

    public function projects()
    {
        return $this->hasMany(\App\Models\Project::class, 'client_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'client_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(\App\Models\SupportTicket::class, 'client_id');
    }
}
