<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Transaksi extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'sumber',
        'pendapatan_id',
        'admin_id',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari ULID
            if (empty($model->id)) {
                $model->id = Str::ulid();
            }
            //jika tanggal kosong, buat tanggal sekarang format Y-m-d H:i:s
            if (empty($model->tanggal)) {
                $model->tanggal = Carbon::now()->format('Y-m-d H:i:s');
            }
        });
    }
}
