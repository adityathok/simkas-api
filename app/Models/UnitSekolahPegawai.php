<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitSekolahPegawai extends Model
{
    protected $fillable = [
        'unit_sekolah_id',
        'user_id',
        'jabatan',
    ];
}
