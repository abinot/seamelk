<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Auth\Database\Factories\AuthUserMetaFactory;

class AuthUserMeta extends Model
{
    use HasFactory;

    protected $table = 'seamelk_auth_user_meta';

    protected $fillable = ['user_id', 'key', 'value'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public static function findUserIdByKeyValue(string $key, string $value)
    {
        $row = self::where('key', $key)->where('value', $value)->first();
        return $row ? $row->user_id : null;
    }
}
