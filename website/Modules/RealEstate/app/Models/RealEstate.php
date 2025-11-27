<?php

namespace Modules\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\RealEstate\Database\Factories\RealEstateFactory;

class RealEstate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    // protected static function newFactory(): RealEstateFactory
    // {
    //     // return RealEstateFactory::new();
    // }
        protected $table = 'real_state';

    protected $fillable = [
        'title','slug','content','excerpt','status',
        'thumbnail_url','banner_url','views_count','comments_count',
        'likes_count','rating_average','rating_count','seo_title',
        'seo_description','seo_keywords','keyword','category_id','tags',
        'deal_type','price','mortgage_price','rent_price','area','land_area',
        'build_year','dimensions','bedrooms','street_width','address_text',
        'address_location','parking','unit','elevator','storage',
        'document_type','balcony','renovated','custom_css','custom_js'
    ];

    protected $casts = [
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'likes_count' => 'integer',
        'rating_average' => 'float',
        'rating_count' => 'integer',
        'parking' => 'boolean',
        'elevator' => 'boolean',
        'storage' => 'boolean',
        'balcony' => 'boolean',
        'renovated' => 'boolean',
    ];

    public function metas() {
        return $this->hasMany(RealStateMeta::class, 'real_state_id');
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'user_real_state')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
