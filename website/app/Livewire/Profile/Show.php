<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class Show extends Component
{
    public $username;
    public $user;

    public function mount($username)
    {
        $this->username = $username;

        // گرفتن کاربر بر اساس username
        $this->user = User::where('id', $username)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.profile.show', [
            'user' => $this->user,
        ]);
    }
}
