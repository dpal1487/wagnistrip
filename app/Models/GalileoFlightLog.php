<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalileoFlightLog extends Model
{
    use HasFactory;
    public $fillable=[
        'session_id',
        'authenticate',
        'availability',
        'pricing',
        'addPassengerDetails',
        'booking',
        'ticket',
        'GetBookingDetails'        
    ];

}
