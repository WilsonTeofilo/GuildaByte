<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_column_id',
        'title',
        'description',
        'type',
        'position',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array',
    ];

    public function column()
    {
        return $this->belongsTo(BoardColumn::class, 'board_column_id');
    }

    public function comments()
    {
        return $this->hasMany(CardComment::class)->orderBy('created_at');
    }

    public function logs()
    {
        return $this->hasMany(CardActivityLog::class)->orderBy('created_at', 'desc');
    }
}
