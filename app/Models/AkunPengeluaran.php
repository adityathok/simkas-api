<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AkunPengeluaran extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'sumber',
        'pendapatan_id',
        'admin_id',
    ];

    //relasi ke akun pendapatan
    public function akunpendapatan()
    {
        return $this->belongsTo(AkunPendapatan::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari random 6
            if (empty($model->id)) {
                $model->id = 'out' . strtoupper(Str::random(6));
            }
        });
    }
}
