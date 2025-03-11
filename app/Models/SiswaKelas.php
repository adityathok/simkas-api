<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SiswaKelas extends Pivot
{

    protected $table = 'siswa_kelas';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('active', true);
    }

    public function pindahkanKelas($kelasBaruId)
    {
        // Menonaktifkan kelas saat ini
        $this->update(['active' => false]);

        // Membuat entri baru untuk kelas baru
        self::create([
            'user_id' => $this->user_id,
            'kelas_id' => $kelasBaruId,
            'active' => true,
        ]);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->active) {
                // Set semua kelas lain menjadi tidak aktif untuk user_id yang sama
                static::where('user_id', $model->user_id)
                    ->update(['active' => false]);
            }
        });

        static::updating(function ($siswaKelas) {
            if ($siswaKelas->isDirty('active') && $siswaKelas->active) {
                // Nonaktifkan semua kelas lain untuk siswa ini
                self::where('user_id', $siswaKelas->user_id)->where('id', '!=', $siswaKelas->id)->update(['active' => false]);
            }
        });
    }
}
