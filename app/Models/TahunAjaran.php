<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
        'nama',
        'mulai',
        'akhir',
        'active'
    ];
}
