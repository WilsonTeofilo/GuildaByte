<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_card_id',
        'user_id',
        'action',
        'details',
    ];

    public function card()
    {
        return $this->belongsTo(BoardCard::class, 'board_card_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
