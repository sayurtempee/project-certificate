<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSurat extends Model
{
    protected $fillable = [
        'student_id',
        'surat_ke',
        'nama_surat',
        'ayat',
        'kelancaran',
        'fasohah',
        'tajwid',
        'total_kesalahan',
        'nilai',
        'predikat',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
