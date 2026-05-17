<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_card_id',
        'user_id',
        'content',
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
