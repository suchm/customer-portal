<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'chat_id',
    ];

    protected function casts(): array
    {
        return [
            'last_read' => 'datetime',
        ];
    }
}
