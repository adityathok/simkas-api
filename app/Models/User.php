<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
            'password' => 'hashed',
        ];
    }

    // Relasi ke tabel user_meta 
    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    public function alamat()
    {
        return $this->hasOne(UserAlamat::class, 'user_id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id');
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
                //hapus avatar
                if ($model->avatar && Storage::disk('public')->exists($model->avatar)) {
                    Storage::disk('public')->delete($model->avatar);
                }
            }
        });
    }
}
