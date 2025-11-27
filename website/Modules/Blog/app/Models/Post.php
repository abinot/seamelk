<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Blog\Database\Factories\PostFactory;
use App\Models\User;
use Modules\Blog\Models\PostMeta;

class Post extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): PostFactory
    // {
    //     // return PostFactory::new();
    // }
    public function meta()
    {
        return $this->hasMany(PostMeta::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'post_user')
        ->withPivot('role')
        ->withTimestamps();
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'hashtag_post');
    }
}
