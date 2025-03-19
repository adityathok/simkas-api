<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class AkunPendapatan extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'neraca',
        'jurnal_khusus',
        'jurnalkas_id',
        'admin_id',
    ];

    protected $casts = [
        'neraca' => 'boolean',
        'jurnal_khusus' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari random 6
            if (empty($model->id)) {
                $model->id = 'in' . strtoupper(Str::random(6));
            }
        });
    }
}
