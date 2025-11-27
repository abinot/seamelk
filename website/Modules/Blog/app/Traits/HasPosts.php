<?php

namespace Modules\Blog\Traits;
use Modules\Blog\Models\Post;
trait HasPosts {
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_user')
        ->withPivot('role')
        ->withTimestamps();
    }
}
