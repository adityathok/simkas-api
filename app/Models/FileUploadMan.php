<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadMan extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide',
        'path',
        'extension',
        'size',
        'user_id',
    ];

    // Menambahkan akses ke atribut url 
    protected $appends = ['url'];

    // Mutator untuk mendapatkan file_url 
    public function getUrlAttribute()
    {
        // return asset('storage/' . $this->path);
        return asset('filestream/' . $this->guide);
    }

    // Fungsi untuk menyimpan file 
    public static function saveFile($file, $collection = null, $user_id = null)
    {
        //ambil user_id dari user yang sedang login
        $user_id = $user_id ? $user_id : auth()->user()->id;

        $collection = $collection ? $collection . '/' . date('Y/m') : 'upload' . date('Y/m');

        $path = $file->store($collection, 'public');
        return self::create([
            'path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'user_id' => $user_id,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Menetapkan guide menggunakan ulid
            if (empty($model->guide)) {
                $model->guide = Str::ulid();
            }
        });

        // Menambahkan event "deleting" untuk menghapus file
        static::deleting(function ($model) {
            //jika ada path
            if ($model->path && Storage::disk('public')->exists($model->path)) {
                Storage::disk('public')->delete($model->path);
            }
        });
    }
}
