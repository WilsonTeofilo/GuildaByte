<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Registra o aceite digital de uma proposta/contrato.
 * Imutável por design: nunca atualiza, só cria.
 */
class ContractAcceptance extends Model
{
    public const UPDATED_AT = null; // Aceite é imutável — sem updated_at

    protected $fillable = [
        'project_id',
        'user_id',
        'contract_version',
        'ip_address',
        'user_agent',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return ['accepted_at' => 'datetime'];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
