<?php

namespace Modules\RealEstate\Traits;

use Modules\RealEstate\Models\RealEstate;

trait HasRealEstate
{
    // املاک مالکیت مستقیم
    public function ownedRealEstates()
    {
        return $this->hasMany(RealEstate::class, 'owner_id');
    }

    // املاکی که کاربر در آن‌ها نقش دارد (مشاور/ارائه‌دهنده/…)
    public function realEstates()
    {
        return $this->belongsToMany(RealEstate::class, 'user_real_estate')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
