<?php

namespace Modules\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealEstate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'code',
        'address',
        'location',
        'owner_id',
        'since_date',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'custom_css',
        'custom_js',
        'profile_picture_url',
        'thumbnail_url',
        'banner_url',
        'slug',
        'views_count',
        'comments_count',
        'likes_count',
        'rating_average',
        'rating_count',
        'home_posts_count',
        'all_posts_count',
        'ads_posts_count',
        'customers_count',
        'workers_count',
        'is_active',
        'verified_at',
    ];

    // اگر میخوای همه چیز قابل mass-assignment باشه، می‌تونی guarded خالی بذاری
    protected $guarded = [];

    // نوع داده‌های timestamp
    protected $dates = [
        'since_date',
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];




    // صاحب اصلی
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // متاها
    public function metas()
    {
        return $this->hasMany(RealEstateMeta::class);
    }
}
