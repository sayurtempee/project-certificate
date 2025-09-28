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
    ];

    // Relasi ke nilai per surat
    public function surats()
    {
        return $this->hasMany(StudentSurat::class, 'student_id');
    }

    // protected $casts = [
    //     'juz' => 'integer',
    // ];
}
