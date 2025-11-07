<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UsersTable extends Component
{
    public $users = [];

    public function mount()
    {
        // فقط یک بار داده‌ها رو می‌گیریم
        $this->users = User::with(['roles', 'permissions'])->get();
    }

    public function render()
    {
        return view('livewire.users-table');
    }
}
