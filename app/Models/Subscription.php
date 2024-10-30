<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('status_id')
            ->with(['status']);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withTimestamps();
    }
}
