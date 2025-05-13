<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Transaksi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nomor',
        'nominal',
        'jenis',
        'tanggal',
        'akun_rekening_id',
        'akun_rekening_tujuan_id',
        'user_id',
        'metode_pembayaran',
        'status',
        'catatan',
        'admin_id',
        'ref_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['nominal_label'];

    //relasi ke akun rekening
    public function akun_rekening()
    {
        return $this->belongsTo(AkunRekening::class, 'akun_rekening_id');
    }

    //relasi many ke Transaksi Item    
    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    //relasi ke admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    //relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

            //jika nomor kosong, buat nomor
            if (empty($model->nomor)) {

                $today = now()->toDateString();

                // Hitung jumlah invoice hari ini
                $count  = self::whereDate('created_at', $today)->count();
                $count  =  $count + 1;
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);

                $model->nomor = Carbon::now()->format('ymd') . $number . Str::upper(Str::random(3)) . Carbon::now()->format('s');
            }

            //jika metode_pembayaran kosong, buat metode_pembayaran dari akun_rekening_id
            if (empty($model->metode_pembayaran)) {
                $rek = $model->akun_rekening_id;
                //jika akun_pendapatan_id kosong atau = CASH atau mengandung kata 'cash'
                if (empty($rek) || $rek == 'CASH' || strpos($rek, 'cash') !== false) {
                    $model->metode_pembayaran = 'cash';
                } else {
                    $model->metode_pembayaran = 'transfer';
                }
            }

            //jika tanggal kosong, buat tanggal sekarang format Y-m-d H:i:s
            if (empty($model->tanggal)) {
                $model->tanggal = Carbon::now()->format('Y-m-d H:i:s');
            }
        });
    }
}
