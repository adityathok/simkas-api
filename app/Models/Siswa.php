<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    // Non-incrementing ID karena ULID
    public $incrementing = false;

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['avatar_url'];

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama',
        'nama_panggilan',
        'status',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_masuk',
        'jenis_kelamin',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Kelas melalui Tabel Pivot siswa_kelas
    public function kelas()
    {
        return $this->belongsToMany(
            Kelas::class,      // Model yang direlasikan
            'siswa_kelas',     // Tabel pivot
            'user_id',         // Foreign key di tabel pivot (mengacu ke user_id)
            'kelas_id',        // Related key di tabel pivot (mengacu ke kelas_id)
            'user_id',         // Local key di model Siswa
            'id'               // Local key di model Kelas (default 'id')
        )->withPivot('active')->withTimestamps();
    }

    // Mendapatkan Kelas Aktif
    public function kelasAktif()
    {
        // return $this->kelas()->wherePivot('active', true)->first();
        return $this->belongsToMany(
            Kelas::class,
            'siswa_kelas',
            'user_id',
            'kelas_id',
            'user_id',
        )->wherePivot('active', true)->first();
    }

    public function getAvatarUrlAttribute()
    {
        //ambil avatar url dari user
        return $this->user ? $this->user->avatar_url : null;

        // if ($this->avatarFile && $this->avatarFile->url) {
        //     return $this->avatarFile->stream;
        // }
        // return asset('assets/images/default-avatar.jpg');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // Menetapkan ID menggunakan ULID jika ID kosong
            if (empty($model->id)) {
                $model->id = Str::ulid();
            }

            // Menetapkan NIS jika kosong
            if (empty($model->nis)) {
                $t = time();
                $model->nis = date("y", $t) . rand(10, 99) . $t;
            }

            //jika email kosong
            if (empty($model->email)) {
                $model->email = $model->nis . '@sekolah.com';
            }

            // Membuat User baru dan menyimpan user_id di Siswa
            if (empty($model->user_id)) {
                $user = User::create([
                    'name'      => $model->nama,
                    'email'     => $model->email,
                    'type'      => 'siswa',
                    'can_login' => false,
                    'password'  => Hash::make($model->email),
                ]);
                $user->assignRole('siswa');
                $model->user_id = $user->id;

                //buat alamat user
                UserAlamat::create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
