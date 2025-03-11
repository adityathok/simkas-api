<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Faker\Core\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes, Notifiable, HasRoles;


    // Non-incrementing ID karena UUID
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'can_login',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'can_login'         => 'boolean',
        ];
    }

    // Relasi ke tabel user_meta 
    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id')->select('meta_key', 'meta_value', 'user_id');
    }

    public function alamat()
    {
        return $this->hasOne(UserAlamat::class, 'user_id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id');
    }

    public function walikelas()
    {
        return $this->hasOne(Kelas::class, 'wali_id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function avatarFile()
    {
        return $this->belongsTo(FileUploadMan::class, 'avatar', 'id');
    }

    public function unit()
    {
        return $this->belongsToMany(UnitSekolah::class, 'unit_sekolah_pegawais', 'user_id', 'unit_sekolah_id')
            ->withPivot('jabatan');
    }

    // Relasi ke SiswaKelas
    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    // Relasi ke Kelas melalui SiswaKelas
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas')
            ->withPivot('active')
            ->withTimestamps();
    }

    // Mendapatkan kelas saat ini
    public function kelasAktif()
    {
        return $this->kelas()->wherePivot('active', true)->first();
    }

    public function getRolesy()
    {
        $roles = $this->roles()->get();
        $result = [];
        foreach ($roles as $role) {
            $result[] = $role->name;
        }
        return $result;
    }

    // Accessor untuk avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatarFile && $this->avatarFile->url) {
            return $this->avatarFile->stream;
        }
        return asset('assets/images/default-avatar.jpg');
    }

    // Accessor untuk usermeta
    public function getMetaAttribute()
    {
        $userMeta = UserMeta::where('user_id', $this->id)->get();
        $metaValues = [];

        foreach ($userMeta as $meta) {
            $metaValues[$meta->meta_key] = $meta->meta_value;
        }
        return $metaValues;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::ulid();
        });

        // Menambahkan event "deleting" untuk menghapus avatar ketika User dihapus
        static::deleting(function ($model) {
            //jika ada avatar
            if ($model->avatar) {
                $file = FileUploadMan::where('id', $model->avatar)->first();
                $file->delete();
            }
            //hapus data avatar dari user
            $model->avatar = null;
        });
    }
}
