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
    public $incrementing = false;

    protected $fillable = [
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
            //jika id kosong, buat id dari date dan random 4
            if (empty($model->id)) {

                // Mendapatkan key cache berdasarkan tanggal hari ini
                $cacheKey = date('ymd') . '_tagihancounter';

                // Mendapatkan nilai counter dari cache, default ke 0 jika belum ada
                $counter = Cache::get($cacheKey, 0) + 1;

                // Simpan kembali counter ke cache
                Cache::put($cacheKey, $counter, now()->endOfDay());

                $counter = str_pad($counter, 4, '0', STR_PAD_LEFT);

                $model->id = 'INV' . Carbon::now()->format('ymd') . $counter . strtoupper(Str::random(4));
            }
            if (empty($model->status)) {
                $model->status = 'belum';
            }
        });
    }
}
