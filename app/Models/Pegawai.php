<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['avatar_url'];

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

    public function getAvatarUrlAttribute()
    {
        //ambil avatar url dari user
        return $this->user ? $this->user->avatar_url : null;
    }

    /**
     * Boot the model and assign a ULID to the model's ID attribute 
     * when a new instance is being created.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($pegawai) {

            // Menetapkan NIP jika kosong
            if (empty($pegawai->nip)) {
                $t = time();
                $pegawai->nip = date("y", $t) . rand(10, 99) . $t;
            }

            // Membuat User baru dan menyimpan user_id di Pegawai
            if (empty($pegawai->user_id)) {
                $user = User::create([
                    'name'      => $pegawai->nama,
                    'username'  => $pegawai->nip ?? Str::slug($pegawai->nama),
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
