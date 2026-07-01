<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaffleEntry extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
    ];
}
