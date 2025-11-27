<?php

namespace Modules\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\RealEstate\Database\Factories\RealEstateMetaFactory;

class RealEstateMeta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */


    // protected static function newFactory(): RealEstateMetaFactory
    // {
    //     // return RealEstateMetaFactory::new();
    // }
    protected $table = 'real_state_metas';

    protected $fillable = ['meta_key','meta_value','meta_note','real_state_id'];

    public function realState() {
        return $this->belongsTo(RealEstateModel::class, 'real_state_id');
    }
}
