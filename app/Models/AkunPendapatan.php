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
    protected $hidden = ['created_at', 'updated_at'];

    //relasi ke jurnalkas
    public function jurnalkas()
    {
        return $this->belongsTo(JurnalKas::class);
    }

    //relasi ke akun pengeluaran
    public function akunpengeluaran()
    {
        return $this->hasMany(AkunPengeluaran::class);
    }

    //relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari random 6
            if (empty($model->id)) {
                $model->id = 'IN' . strtoupper(Str::random(6));
            }
        });
    }
}
