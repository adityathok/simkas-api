<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $fillable = [
        'user_id',
        'meta_key',
        'meta_value',
    ];

    //update meta
    public static function updateUserMetas($userId, array $metaData)
    {
        $result = [];
        foreach ($metaData as $key => $value) {
            if (empty($value)) {
                self::where('user_id', $userId)->where('meta_key', $key)->delete();
                $result[$key] = null;
            } else {
                $meta = self::updateOrCreate(
                    ['user_id' => $userId, 'meta_key' => $key],
                    ['meta_value' => $value]
                );
                $result[$key] = $meta->meta_value;
            }
        }
        return $result;
    }


    //get meta value
    public static function getMetaValue($userId, $key)
    {
        $userMeta = self::where('user_id', $userId)->where('meta_key', $key)->first();
        return $userMeta ? $userMeta->meta_value : null;
    }

    public static function getMetaValues($userId, array $keys = null)
    {
        $userMeta = self::where('user_id', $userId)->whereIn('meta_key', $keys)->get();
        $metaValues = array_fill_keys($keys, null);

        foreach ($userMeta as $meta) {
            $metaValues[$meta->meta_key] = $meta->meta_value;
        }
        return $metaValues;
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
