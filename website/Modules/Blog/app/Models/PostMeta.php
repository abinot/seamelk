<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Blog\Database\Factories\PostMetaFactory;
use Modules\Blog\Models\Post;
class PostMeta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): PostMetaFactory
    // {
    //     // return PostMetaFactory::new();
    // }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
