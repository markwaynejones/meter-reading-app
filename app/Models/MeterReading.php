<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'reading_date' => 'date', // casting reading_date as a Carbon date instance
    ];
}
