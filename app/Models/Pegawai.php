<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class Pegawai extends Model
{
    use HasFactory;

    // Non-incrementing ID karena ULID
    public $incrementing = false;

    protected $fillable = [
        'nip',
        'nama',
        'status',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_masuk',
        'jenis_kelamin',
        'foto',
        'nik',
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alamat()
    {
        return $this->hasOne(PegawaiAlamat::class, 'pegawai_id');
    }

    public function meta()
    {
        return $this->hasMany(PegawaiMeta::class, 'pegawai_id');
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
