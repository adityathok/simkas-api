<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kelas extends Model
{
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'nama',
        'tingkat',
        'tahun_ajaran',
        'unit_sekolah_id',
        'wali_id',
    ];

    //relasi ke unit sekolah
    public function unitSekolah()
    {
        return $this->belongsTo(UnitSekolah::class, 'unit_sekolah_id');
    }

    //relasi ke wali kelas
    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_kelas', 'kelas_id', 'user_id')
            ->withPivot('id'); // Jika ingin mengakses kolom lain di pivot
    }

    // Relasi ke SiswaKelas
    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    // Relasi ke User melalui SiswaKelas
    public function users()
    {
        return $this->belongsToMany(User::class, 'siswa_kelas')
            ->withPivot('active')
            ->withTimestamps();
    }

    //dapatkan daftar siswa
    public function siswa_kelas()
    {
        $siswa = $this->siswaKelas();

        return $siswa;
    }

    /**
     * Boot the model and assign a ULID to the model's ID attribute 
     * when a new instance is being created.
     */
    // public static function boot()
    // {
    // parent::boot();
    // static::creating(function ($model) {
    // Menetapkan ID menggunakan ULID jika ID kosong
    //     if (empty($model->id)) {
    //         $model->id = Str::ulid();
    //     }
    // });
    //}
}
