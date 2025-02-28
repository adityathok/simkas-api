<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    public $incrementing = false;

    protected $hidden = ['created_at', 'updated_at'];

    //append
    protected $appends = ['tahun_mulai', 'tahun_akhir'];

    protected $fillable = [
        'id',
        'nama',
        'mulai',
        'akhir',
        'active'
    ];

    //dapatkan tahun ajaran aktif
    public static function getActive()
    {
        return self::where('active', true)->first();
    }

    public function getTahunMulaiAttribute()
    {
        //ambil dari nama
        $nama = $this->nama;
        $nama = explode('/', $nama);
        return $nama[0];
    }

    public function getTahunAkhirAttribute()
    {
        //ambil dari nama
        $nama = $this->nama;
        $nama = explode('/', $nama);
        return $nama[1];
    }
}
