<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);

    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants')
        ->withPivot('last_read')
        ->withTimestamps();
    }
}
