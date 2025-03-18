<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TagihanBatch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'status',
        'tanggal',
        'jumlah',
        'total_nominal',
        'keterangan',
        'expired',
        'pendapatan_id',
        'tahun_ajaran',
        'unit_sekolah_id',
        'kelas_id',
        'user_type',
        'admin_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari date dan random 4
            if (empty($model->id)) {
                $model->id = Carbon::now()->format('ymd') . strtoupper(Str::random(4));
            }
        });
    }
}
