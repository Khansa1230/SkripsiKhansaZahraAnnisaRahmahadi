<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurusanSekolah extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_jursekol'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_jursekol',
        'jurusan_sekolah',
        'kd_jenissekolah',
    ];

    // Definisikan relasi ke tabel jenis sekolah
    public function jenisSekolah()
    {
        return $this->belongsTo(JenisSekolah::class, 'kd_jenissekolah', 'kd_jenissekolah');
    }

    // Definisikan relasi ke tabel mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kd_jursekol', 'kd_jursekol');
    }
}
