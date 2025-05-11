<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Tagihan extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    // public $incrementing = false;

    protected $fillable = [
        'nomor',
        'nama',
        'tanggal',
        'tagihan_master_id',
        'status',
        'user_id',
        'keterangan',
    ];

    //relasi ke tagihan_masters
    public function tagihan_master()
    {
        return $this->belongsTo(TagihanMaster::class);
    }

    //relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            //jika nomor kosong, buat id dari date dan random 4
            if (empty($model->nomor)) {
                // Mendapatkan key cache berdasarkan tanggal hari ini
                $model->nomor = 'INV' . Str::ulid();
            }
            if (empty($model->status)) {
                $model->status = 'belum';
            }
        });
    }
}
