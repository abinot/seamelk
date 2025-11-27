<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMeta extends Model
{
    /** @use HasFactory<\Database\Factories\UserMetaFactory> */
    use HasFactory;
    use SoftDeletes;

        // ساده‌ترین راه: اجازه‌ی mass assignment برای همه فیلدها
   

    // یا اگر کنترل دقیق‌تر می‌خوای:
    // protected $fillable = [
    //     'user_id', 'meta_key', 'meta_type', 'meta_value',
    //     'verified_at', 'is_active', 'show', 'delete_type', 'meta_note',
    // ];

    protected $casts = [
        'show'       => 'array',   // JSON به آرایه تبدیل شود
        'is_active'  => 'boolean',
        // اگر verified_at واقعاً تاریخ نیست و string نگه می‌داری، کست نکن
        // 'verified_at' => 'datetime',  // فقط اگر ستون واقعاً datetime باشد
    ];

    protected $fillable = [
    'user_id',
    'meta_key',
    'meta_type',
    'meta_value',
    'verified_at',
    'is_active',
    'show',
    'delete_type',
    'meta_note',
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function findUserIdByKeyValue(string $key, string $value)
    {
        $row = self::where('meta_key', $key)->where('meta_value', $value)->first();
        return $row ? $row->user_id : null;
    }


}
