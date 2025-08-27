<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nama',
        'no_induk',
        'penyimak',
        'juz',
        'kelancaran',
        'fasohah',
        'tajwid',
        'total_kesalahan',
        'nilai',
        'predikat',
    ];

    protected $casts = [
        'juz' => 'integer',
    ];
}
