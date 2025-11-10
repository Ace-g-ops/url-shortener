<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'click_count',
    ];

    // Relationships: urls belong to one or one user
    public function user() {

        return $this->belongsTo(User::class);

    }

    // helper method to generate unique short code

    public static function generateUniqueCode($length = 6){
 do {
            $code = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
        } while (self::where('short_code', $code)->exists());

        return $code;
    }

}
