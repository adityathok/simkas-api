<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AkunRekening extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'keterangan',
        'saldo',
        'admin_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari random 4
            if (empty($model->id)) {
                $model->id = 'REK' . strtoupper(Str::random(4));
            }
            //jika saldo kosong, buat saldo 0
            if (empty($model->saldo)) {
                $model->saldo = 0;
            }
        });
    }
}
