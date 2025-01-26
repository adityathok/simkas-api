<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kelas extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena ULID
    public $incrementing = false;

    protected $fillable = [
        'id',
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
