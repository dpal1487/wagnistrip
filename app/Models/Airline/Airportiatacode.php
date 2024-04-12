<?php

namespace App\Models\Airline;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airportiatacode extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'iata', 'airport', 'city','state', 'country'
    ];

}
