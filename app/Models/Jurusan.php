<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_jurusan'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_jurusan',
        'jurusan',
        'kd_fakultas',
    ];

    // Definisikan relasi ke tabel Fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'kd_fakultas', 'kd_fakultas');
    }
    public function regMahasiswa()
    {
        return $this->belongsTo(RegMahasiswa::class, 'kd_jurusan', 'kd_jurusan');
    }
}
