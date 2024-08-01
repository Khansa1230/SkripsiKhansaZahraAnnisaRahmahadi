<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSekolah extends Model
{
    use HasFactory;

    protected $table = 'jenissekolah'; // Nama tabel di database

    protected $primaryKey = 'kd_jenissekolah'; // Primary key tabel

    protected $fillable = [
        'kd_jenissekolah',
        'jenis_sekolah',
    ];

    // Definisikan relasi ke tabel mahasiswa
    public function mahasiswa()
    {
        return $this->hbelongsTo(Mahasiswa::class, 'kd_jenissekolah', 'kd_jenissekolah');
    }
}
