<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UnitSekolah extends Model
{
    use SoftDeletes;

    // Non-incrementing ID karena ULID
    public $incrementing = false;

    protected $appends = ['logo_url'];

    protected $fillable = [
        'id',
        'nama',
        'jenjang',
        'alamat',
        'desa',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'status',
        'tanggal_berdiri',
        'kepala_sekolah_id',
        'whatsapp',
        'telepon',
        'email',
        'logo',
        'tingkat',
        'rombel',
    ];

    // Menentukan kolom yang akan didekode
    protected $casts = [
        'tingkat' => 'json',
        'rombel' => 'json',
    ];

    // Jika Anda ingin menggunakan accessor secara manual
    public function getTingkatAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getRombelAttribute($value)
    {
        return json_decode($value, true);
    }

    public function logoFile()
    {
        return $this->belongsTo(FileUploadMan::class, 'logo', 'id');
    }

    // Accessor untuk logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->logoFile && $this->logoFile->url) {
            return $this->logoFile->stream;
        }
        return null;
    }

    //relasi jabatan pegawai di unit sekolah
    public function pegawais()
    {
        return $this->belongsToMany(User::class, 'unit_sekolah_pegawais', 'unit_sekolah_id', 'user_id')
            ->withPivot('jabatan');
    }

    public function kepalaSekolah()
    {
        $user = $this->pegawais()->wherePivot('jabatan', 'Kepala Sekolah')->first();
        if ($user && $user->pegawai) {
            return [
                'user_id'       => $user->id,
                'pegawai_id'    => $user->pegawai->id,
                'nama'          => $user->pegawai->nama,
                'jabatan'       => $user->pivot->jabatan,
                'avatar'        => $user->avatar
            ];
        } else {
            return null;
        }
    }

    public function semuaPegawai()
    {
        return $this->pegawais()->with('pegawai')->get()->map(function ($user) {
            return [
                'user_id'       => $user->id,
                'pegawai_id'    => $user->pegawai->id,
                'nama'          => $user->pegawai->nama,
                'jabatan'       => $user->pivot->jabatan,
                'avatar'        => $user->avatar
            ];
        });
    }

    //relasi ke kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
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

        //jika hapus
        static::deleting(function ($model) {
            //jika ada logo, hapus
            if ($model->logo) {
                $file = FileUploadMan::where('id', $model->logo)->first();
                $file->delete();
            }
            //hapus kolom logo
            $model->logo = null;

            //hapus data pegawai di unit sekolah
            $model->pegawais()->detach();
        });
    }
}
