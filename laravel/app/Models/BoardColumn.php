<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'guilda_board_id',
        'title',
        'position',
    ];

    public function board()
    {
        return $this->belongsTo(GuildaBoard::class, 'guilda_board_id');
    }

    public function cards()
    {
        return $this->hasMany(BoardCard::class)->orderBy('position');
    }
}
