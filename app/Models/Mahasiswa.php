<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'nim',
        'nama',
        'tahun_angkatan',
        'kelamin',
        'wni_wna',
        'kd_provinsi',
        'kd_kota',
        'kd_jenissekolah',
        'kd_jursekol',
        'tgl_lahir',
    ];

    // Relasi dengan tabel provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kd_provinsi', 'kd_provinsi');
    }

    // Relasi dengan tabel kota
    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kd_kota', 'kd_kota');
    }

    // Relasi dengan tabel jenis sekolah
    public function jenisSekolah()
    {
        return $this->belongsTo(JenisSekolah::class, 'kd_jenissekolah', 'kd_jenissekolah');
    }

    // Relasi dengan tabel jurusan sekolah
    public function jurusanSekolah()
    {
        return $this->belongsTo(JurusanSekolah::class, 'kd_jursekol', 'kd_jursekol');
    }

    public function regmahasiswa()
    {
        return $this->belongsTo(RegMahasiswa::class, 'nim', 'nim');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'ni');
    }
}
