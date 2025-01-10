<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UnitSekolah extends Model
{
    // Non-incrementing ID karena ULID
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'jenjang',
        'alamat',
        'desa',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'status',
        'tanggal_berdiri',
        'kepala_sekolah_id',
        'whatsapp',
        'telepon',
        'email',
        'logo'
    ];

    //relasi jabatan pegawai di unit sekolah
    public function pegawai()
    {
        return $this->belongsToMany(Pegawai::class, 'unit_sekolah_pegawai', 'unit_sekolah_id', 'user_id')
            ->withTimestamps()->withPivot('jabatan');
    }

    /**
     * Boot the model and assign a ULID to the model's ID attribute 
     * when a new instance is being created.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Menetapkan ID menggunakan ULID jika ID kosong
            if (empty($model->id)) {
                $model->id = Str::ulid();
            }
        });
    }
}
