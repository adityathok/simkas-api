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

            // Membuat User baru dan menyimpan user_id di Pegawai
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
