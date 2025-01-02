<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAlamat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'jenis_tinggal',
        'transportasi',
        'jarak',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
