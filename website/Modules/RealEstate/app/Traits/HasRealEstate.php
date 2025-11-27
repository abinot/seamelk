<?php

namespace Modules\RealEstate\Traits;

use Modules\RealEstate\Entities\RealEstate;

trait HasRealEstate
{
    public function realStates()
    {
        return $this->belongsToMany(
            RealEstate::class,
            'user_real_state'
        )->withPivot('role')
         ->withTimestamps();
    }
}
