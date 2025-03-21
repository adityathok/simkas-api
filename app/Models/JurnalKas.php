<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class JurnalKas extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena CHAR
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama',
        'kas',
        'neraca',
        'jurnal_khusus',
        'likuiditas',
    ];

    protected $casts = [
        'neraca' => 'boolean',
        'jurnal_khusus' => 'boolean',
        'likuiditas' => 'boolean',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // relasi ke akun pendapatan
    public function akunpendapatan()
    {
        return $this->hasMany(AkunPendapatan::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            //jika id kosong, buat id dari random 6
            Log::info('ID sebelum diset:', [$model]);
            if (!$model->id) {
                $model->id = 'J' . strtoupper(Str::random(6));
            }
            Log::info('ID setelah diset:', ['id' => $model->id]);
        });
    }
}
