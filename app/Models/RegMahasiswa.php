<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'reg_mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'status',
        'kd_fakultas',
        'kd_jurusan',
        'jenjang',
        'program',
        'kd_jenisseleksi',
        'tanggal_lulus',
        'ipk',
        'etic',
        'toefl',
        'toafl',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'kd_fakultas', 'kd_fakultas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'kd_jurusan', 'kd_jurusan');
    }

    public function jenisseleksi()
    {
        return $this->belongsTo(JenisSeleksi::class, 'kd_jenisseleksi', 'kd_jenisseleksi');
    }

}
