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
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['nominal_label'];

    //relasi ke akun pendapatan
    public function akunpendapatan()
    {
        return $this->belongsTo(AkunPendapatan::class, 'pendapatan_id');
    }

    //relasi ke akun pengeluaran
    public function akunpengeluaran()
    {
        return $this->belongsTo(AkunPengeluaran::class, 'pendapatan_id');
    }

    //relasi ke akun rekening
    public function akunrekening()
    {
        return $this->belongsTo(AkunRekening::class, 'rekening_id');
    }

    //relasi ke tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    //relasi ke admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    //relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor untuk nominal_label
    public function getNominalLabelAttribute()
    {
        return number_format($this->nominal, 2, ',', '.');
    }

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
