<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['name' , 'phone' , 'email' , 'eventdate' , 'eventtype' , 'eventcity'];

    protected static function boot() {
        parent::boot();
        static::deleting(function($event) { // before delete() method call this
            $event->image()->delete();
       });
    }
    public function image() {
        return $this->hasOne(Image::class ,'entity_id' , 'id')->where('entity_type', 'event');
    }
}
