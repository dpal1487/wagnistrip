<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'slug' , 'content' , 'is_published'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });

        static::deleting(function($blog) { // before delete() method call this
            $blog->image()->delete();
       });
    }
    public function image() {
        return $this->hasOne(Image::class ,'entity_id' , 'id')->where('entity_type', 'blog');
    }
}
