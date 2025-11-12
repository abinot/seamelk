<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleManagment extends Component
{
    public $roles = [];

    public function mount()
    {
        // همه نقش‌ها همراه با دسترسی‌هایشان
        $this->roles = Role::with('permissions')->get();
    }

    public function render()
    {
        return view('livewire.role-managment');
    }
}
