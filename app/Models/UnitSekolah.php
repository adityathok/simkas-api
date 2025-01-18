<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UnitSekolah extends Model
{
    use SoftDeletes;

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
    public function pegawais()
    {
        return $this->belongsToMany(User::class, 'unit_sekolah_pegawais', 'unit_sekolah_id', 'user_id')
            ->using(UnitSekolahPegawai::class);
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
