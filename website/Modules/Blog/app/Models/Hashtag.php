<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Blog\Database\Factories\HashtagFactory;

class Hashtag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): HashtagFactory
    // {
    //     // return HashtagFactory::new();
    // }
    // Hashtag.php
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'hashtag_post');
    }

}
