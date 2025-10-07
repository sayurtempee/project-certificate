<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nama',
        'no_induk',
        'penyimak',
        'juz',
        'tanggal_lulus',
        'tempat_kelulusan',
        'nm_kepsek',
        'nip_kepsek',
    ];

    // Relasi ke nilai per surat
    public function surats()
    {
        return $this->hasMany(StudentSurat::class, 'student_id');
    }

    protected $casts = [
        'tanggal_lulus' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($student) {
            $nipMapping = [
                'Neor Imanah, M.Pd' => '1234567890',
                'Euis Rahmawaty, M.Pd' => '7890123456'
            ];

            if (isset($nipMapping[$student->nm_kepsek])) {
                if ($student->nip_kepsek && $student->nip_kepsek !== $nipMapping[$student->nm_kepsek]) {
                    throw new \Exception(
                        'NIP untuk' . $student->nm_kepsek . ' harus ' . $nipMapping[$student->nm_kepsek]
                    );
                }
                $student->nip_kepsek = $nipMapping[$student->nm_kepsek];
            }
        });
    }
}
