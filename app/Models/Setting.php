<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'is_array',
        'file_id',
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

        //jika value mengandung string 'fileuploadman:'
        $file_id = null;
        if (strpos($value, 'fileuploadman:') === 0) {

            //ambil value sebelumnya,dapatkan file_id
            $setting = self::where('key', $key)->first();
            if ($setting) {
                $file_id = $setting->file_id;
                //hapus file sebelumnya
                FileUploadMan::findOrFail($file_id)->delete();
            }

            $file_id = substr($value, strlen('fileuploadman:'));
            $file_id = (int) $file_id;
        }

        //createorupdate by key
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value, 'is_array' => $is_array, 'file_id' => $file_id]);
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
        //jika file_id ada, ambil file
        if ($this->file_id) {
            $file = FileUploadMan::findOrFail($this->file_id);
            return $file->getUrlAttribute();
        }
        return $value;
    }

    //hapus setting by key
    public static function del($key)
    {
        $setting = self::where('key', $key)->delete();
        return $setting;
    }

    //join file
    public function file()
    {
        return $this->belongsTo(FileUploadMan::class, 'file_id');
    }
}
