<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TagihanMaster extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'nominal',
        'type',
        'total_tagihan',
        'total_nominal',
        'keterangan',
        'due_date',
        'periode_start',
        'periode_end',
        'akun_pendapatan_id',
        'tahun_ajaran',
        'unit_sekolah_id',
        'kelas_id',
        'user_type',
        'admin_id',
    ];
    protected $appends = ['nominal_label'];

    //relasi ke tagihan
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    //relasi ke akun pendapatan
    public function akunpendapatan()
    {
        return $this->belongsTo(AkunPendapatan::class, 'akun_pendapatan_id');
    }


    // Accessor untuk nominal_label
    public function getNominalLabelAttribute()
    {
        return number_format($this->nominal, 2, ',', '.');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari date dan random 4
            if (empty($model->id)) {
                $model->id = 'TM' . Carbon::now()->format('ymd') . strtoupper(Str::random(4));
            }
        });
    }
}
