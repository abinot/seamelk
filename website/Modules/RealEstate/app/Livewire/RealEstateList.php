<?php
namespace Modules\RealEstate\Livewire;

use Livewire\Component;
use Modules\RealEstate\Models\RealEstate;
use Illuminate\Support\Facades\Auth;

class RealEstateList extends Component
{
    public $items;

    public function mount()
    {
        // همه بنگاه‌های مالک فعلی
        $this->items = RealEstate::where('owner_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('realestate::livewire.real-estate-list');
    }
}
