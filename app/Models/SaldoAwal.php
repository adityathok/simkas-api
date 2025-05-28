<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    protected $fillable = [
        'akun_rekening_id',
        'bulan',
        'tahun',
        'nominal',
    ];

    public function akun_rekening()
    {
        return $this->belongsTo(AkunRekening::class);
    }
}
