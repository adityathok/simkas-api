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

    //ambil setting, dari array key
    public static function gets($keys = null)
    {
        // Jika $keys diberikan dan tidak dalam bentuk array, konversi ke array 
        if ($keys !== null && !is_array($keys)) {
            $keys = [$keys];
        }

        // Ambil setting berdasarkan keys yang diberikan 
        $query = self::query();
        if ($keys !== null) {
            $query->whereIn('key', $keys);
        }
        $settings = $query->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }

        return $result;
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

    //update setting multiple
    public static function sets(array $settings)
    {
        $result = [];
        foreach ($settings as $key => $value) {
            $set = self::set($key, $value);
            $result[$key] = $set->value;
        }
        return $result;
    }

    //accessor
    public function getValueAttribute($value)
    {
        //jika is_array true, konversi ke array
        if ($this->is_array) {
            return json_decode($value, true);
        }
        return $value;
    }

    //hapus setting by key
    public static function del($key)
    {
        $setting = self::where('key', $key)->delete();
        return $setting;
    }
}
