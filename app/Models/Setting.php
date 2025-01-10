<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'is_array'
    ];

    //ambil setting
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    //update setting
    public static function set($key, $value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
            $is_array = true;
        } else {
            $is_array = false;
        }

        //createorupdate by key
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value, 'is_array' => $is_array]);
        return $setting;
    }
}
