<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        return cache()->remember("setting_$key", 3600, function () use ($key, $default) {
            return optional(self::where('key', $key)->first())->value ?? $default;
        });
    }
}
