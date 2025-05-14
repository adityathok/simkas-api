<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'nama',
        'qty',
        'nominal_item',
        'nominal',
        'akun_pendapatan_id',
        'akun_pengeluaran_id',
        'tagihan_id',
    ];

    //relasi ke transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    //relasi ke akun pendapatan
    public function akun_pendapatan()
    {
        return $this->belongsTo(AkunPendapatan::class, 'akun_pendapatan_id');
    }

    //relasi ke akun pengeluaran
    public function akun_pengeluaran()
    {
        return $this->belongsTo(AkunPengeluaran::class, 'akun_pengeluaran_id');
    }

    //relasi ke tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}
