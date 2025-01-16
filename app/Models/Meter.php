<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'installation_date' => 'date', // casting installation_date as a Carbon date instance
    ];

    public function readings()
    {
        return $this->hasMany(MeterReading::class);
    }
}
