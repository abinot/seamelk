<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($username)
    {
        // اینجا می‌تونی از مدل User هم استفاده کنی
        // ولی فعلاً فقط یوزرنیم رو پاس می‌دیم
        return view('profile.show', [
            'username' => $username,
        ]);
    }
}
