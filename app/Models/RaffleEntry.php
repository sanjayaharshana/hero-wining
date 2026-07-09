<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RaffleEntry extends Model
{
    protected $fillable = [
        'name',
        'favourite_dip',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
        'won_at' => 'datetime',
    ];

    public function scopeEligible(Builder $query): Builder
    {
        return $query->where('is_winner', false);
    }

    public function scopeWinners(Builder $query): Builder
    {
        return $query->where('is_winner', true)->orderByDesc('won_at');
    }
}
