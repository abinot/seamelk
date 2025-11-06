<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Ad\Database\Factories\PostMetaFactory;

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
}
