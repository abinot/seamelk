<?php

namespace Modules\RealEstate\Livewire;

use Livewire\Component;
use Modules\RealEstate\Models\RealEstate;

class RealEstateShow extends Component
{
    public $real_estate;

    public function mount($id)
    {
        $this->real_estate = RealEstate::find($id);

        if (!$this->real_estate) {
            abort(404);
        }
    }

    public function render()
    {
        return view('realestate::livewire.real-estate-show');
    }
}
