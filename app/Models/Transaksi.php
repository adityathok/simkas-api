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
        'nominal',
        'pendapatan_id',
        'pengeluaran_id',
        'rekening_id',
        'tagihan_id',
        'arus',
        'user_id',
        'admin_id',
        'tanggal'
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
        return $this->belongsTo(AkunPengeluaran::class, 'pengeluaran_id');
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

                // Hitung jumlah transaksi hari ini
                $count = Transaksi::whereDate('created_at', today())->count();
                // Tentukan urutan transaksi hari ini (dimulai dari 0001)
                $sequence = str_pad($count + 1, 5, '0', STR_PAD_LEFT);

                $model->id = Carbon::now()->format('ymd') . $sequence . Str::upper(Str::random(4));
            }

            //jika tanggal kosong, buat tanggal sekarang format Y-m-d H:i:s
            if (empty($model->tanggal)) {
                $model->tanggal = Carbon::now()->format('Y-m-d H:i:s');
            }
        });
    }
}
