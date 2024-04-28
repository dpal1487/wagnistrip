<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelOffer extends Model
{
    use HasFactory;
    public $filable=[
        'name',
        'slug',
        'rating',
        'location',
        'price',
        'room_type',
        'images'
    ];
}
