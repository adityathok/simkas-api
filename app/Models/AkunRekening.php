<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AkunRekening extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'keterangan',
        'saldo',
        'tipe',
    ];

    protected $hidden = [
        'saldo',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    //relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika saldo kosong, buat saldo 0
            if (empty($model->saldo)) {
                $model->saldo = 0;
            }
        });
    }
}
