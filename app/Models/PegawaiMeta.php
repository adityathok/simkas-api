<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PegawaiMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'key',
        'value',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

}
