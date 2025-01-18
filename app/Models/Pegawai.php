<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
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
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //relasi jabatan di unit sekolah
    public function jabatan()
    {
        return $this->belongsToMany(UnitSekolah::class, 'unit_sekolah_pegawais', 'pegawai_id', 'unit_sekolah_id')
            ->withTimestamps()->withPivot('jabatan');
    }

    /**
     * Boot the model and assign a ULID to the model's ID attribute 
     * when a new instance is being created.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($pegawai) {
            // Menetapkan ID menggunakan ULID jika ID kosong
            if (empty($pegawai->id)) {
                $pegawai->id = Str::ulid();
            }

            // Membuat User baru dan menyimpan user_id di Pegawai
            if (empty($pegawai->user_id)) {
                $user = User::create([
                    'name'      => $pegawai->nama,
                    'email'     => $pegawai->email,
                    'type'      => 'pegawai',
                    'can_login' => false,
                    'password'  => Hash::make($pegawai->email),
                ]);
                $user->assignRole('pegawai');

                // Menyimpan user_id di Pegawai
                $pegawai->user_id = $user->id;

                //buat alamat user
                UserAlamat::create([
                    'user_id' => $user->id,
                ]);
            }
        });

        // Menambahkan event "deleting" untuk menghapus User ketika Pegawai dihapus
        static::deleting(function ($pegawai) {
            // Menghapus User yang terkait dengan Pegawai
            if ($pegawai->user) {
                $pegawai->user->delete();
            }
        });
    }
}
