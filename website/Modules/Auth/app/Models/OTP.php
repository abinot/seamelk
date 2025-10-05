<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Auth\Database\Factories\OTPFactory;

class OTP extends Model
{
    use HasFactory;
    protected $table = 'seamelk_auth_otps';
    protected $fillable = ['phone', 'otp', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];
}
