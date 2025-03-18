<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JurnalKas extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'nama',
        'kas',
        'neraca',
        'jurnal_khusus',
        'likuiditas',
    ];

    protected $casts = [
        'neraca' => 'boolean',
        'jurnal_khusus' => 'boolean',
        'likuiditas' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari random 6
            if (empty($model->id)) {
                $model->id = strtoupper(Str::random(6));
            }
        });
    }
}
