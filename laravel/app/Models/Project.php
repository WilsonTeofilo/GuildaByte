<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'package_id',
        'package_version_id',
        'promotion_id',
        // Snapshot financeiro imutável
        'agreed_package_name',
        'agreed_package_version',
        'agreed_base_value',
        'agreed_discount_value',
        'agreed_final_value',
        'guildabyte_fee_percent',
        'guildabyte_fee_value',
        'team_net_value',
        'agreed_deadline_days',
        // Status
        'status',
        'financial_status',
        // Timestamps de ciclo de vida
        'accepted_at',
        'started_at',
        'delivered_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'agreed_base_value'      => 'decimal:2',
            'agreed_discount_value'  => 'decimal:2',
            'agreed_final_value'     => 'decimal:2',
            'guildabyte_fee_percent' => 'decimal:2',
            'guildabyte_fee_value'   => 'decimal:2',
            'team_net_value'         => 'decimal:2',
            'accepted_at'            => 'datetime',
            'started_at'             => 'datetime',
            'delivered_at'           => 'datetime',
            'closed_at'              => 'datetime',
        ];
    }

    // ── Relacionamentos ──────────────────────────────────────────

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function packageVersion()
    {
        return $this->belongsTo(PackageVersion::class);
    }

    public function financialEvents()
    {
        return $this->hasMany(FinancialEvent::class);
    }

    public function board()
    {
        return $this->hasOne(Board::class);
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function contractAcceptances()
    {
        return $this->hasMany(\App\Models\ContractAcceptance::class);
    }

    public function addendums()
    {
        return $this->hasMany(\App\Models\ScopeAddendum::class);
    }

    /** Percentual de progresso do projeto baseado no status */
    public function getProgressPercentAttribute(): int
    {
        return match($this->status) {
            'received'           => 10,
            'in_analysis'        => 20,
            'proposal_sent'      => 30,
            'awaiting_approval'  => 40,
            'in_development'     => 65,
            'in_review'          => 80,
            'delivered'          => 90,
            'maintenance'        => 100,
            default              => 0,
        };
    }

    /** Scopes de status como strings (campo enum/string no banco) */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'archived']);
    }

    // ── Boot ─────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::observe(\App\Observers\ProjectObserver::class);
    }
}
