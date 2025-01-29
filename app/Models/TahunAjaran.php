<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
        'nama',
        'mulai',
        'akhir',
        'active'
    ];

    //dapatkan tahun ajaran aktif
    public static function getActive()
    {
        return self::where('active', true)->first();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->active) {
                self::where('active', true)->update(['active' => false]);
            }
        });
    }
}
