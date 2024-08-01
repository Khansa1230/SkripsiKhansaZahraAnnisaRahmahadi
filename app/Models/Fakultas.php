<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_fakultas'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'kd_fakultas',
        'fakultas',
    ];

    // Definisikan relasi ke tabel RegMahasiswa
    public function regMahasiswa()
    {
        return $this->belongsTo(RegMahasiswa::class, 'kd_fakultas', 'kd_fakultas');
    }
}
