<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\RegMahasiswa;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        return view ('test');
    }

    public function iindex()
    {
        return view ('3test');
    }
    // public function index(Request $request)
    // {
    //     // Ambil data tahun dari tabel mahasiswa
    //     $years = DB::table('mahasiswa')
    //         ->select(DB::raw('YEAR(tahun_angkatan) as year'))
    //         ->groupBy('year')
    //         ->orderBy('year', 'DESC')
    //         ->get();

    //     // Ambil nim dari tabel reg_mahasiswa
    //     $nim = DB::table('reg_mahasiswa')
    //         ->select('nim')
    //         ->groupBy('nim')
    //         ->get();

    //     // Ambil jurusan dari tabel jurusan
    //     $jurusan = DB::table('jurusan')
    //         ->select('kd_jurusan', 'jurusan')
    //         ->get();

    //     $year = $request->input('year');
    //     $jurusan = $request->input('jurusan');
    //     $nim = $request->input('nim');

    //     // Query untuk mendapatkan jumlah mahasiswa berdasarkan jurusan dan tahun angkatan
    //     $bar = DB::table('jurusan as j')
    //         ->leftJoin('reg_mahasiswa as r', 'j.kd_jurusan', '=', 'r.kd_jurusan')
    //         ->leftJoin('mahasiswa as m', 'r.nim', '=', 'm.nim')
    //         ->select('j.jurusan', DB::raw('YEAR(m.tahun_angkatan) AS tahun'), DB::raw('COUNT((m.nim)) as jumlah_mahasiswa'))
    //         ->whereYear('m.tahun_angkatan', $year)
    //         ->groupBy('j.jurusan', 'tahun')
    //         ->get();

    //     $query = $bar->mapWithKeys(function ($item) {
    //         return [$item->jurusan => $item->jumlah_mahasiswa];
    //     });

    //     return view('test', compact('years', 'query'));
    // }
//     public function jumlahperangkatan(Request $request)
//     {
//         // Mendapatkan tahun-tahun angkatan yang ada
//         $years = DB::table('mahasiswa')
//         ->select(DB::raw('YEAR(tahun_angkatan) as year'))
//         ->groupBy('year')
//         ->orderBy('year', 'DESC')
//         ->get();

//         // Mendapatkan nim-nim mahasiswa yang ada
//         $nim= DB::table('reg_mahasiswa')
//         ->select('nim')
//         ->groupBy('nim')
//         ->get();

//         // Mendapatkan tahun dan nim dari permintaan
//         $year = $request->input('year');
//         $nim = $request->input('nim');

//         // Menghitung jumlah mahasiswa berdasarkan tahun angkatan
//         $bar = DB::table('mahasiswa as m')
//         ->leftJoin('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
//         ->select(DB::raw('YEAR(m.tahun_angkatan) AS tahun'), DB::raw('COUNT(r.nim) AS jumlah_mahasiswa'))
//         // ->whereYear('m.tahun_angkatan', $year)
//         ->groupBy('tahun')
//         ->get();
//         $query = $bar->mapWithKeys(function ($item) {
//             return [$item->tahun => $item->jumlah_mahasiswa];
//         });

//         return ['years' => $years, 'query' => $query];
//     }

// public function combined(Request $request)
// {
//     // Panggil method tech dan jumlahperangkatan
//     $techData = $this->tech($request);
//     $jumlah_mahasiswa = isset($techData['jumlah_mahasiswa']) ? $techData['jumlah_mahasiswa'] : null;
//     // Panggil method tahunAngkatanTerbanyak
//     $tahun_angkatan_terbanyak = $this->tahunAngkatanTerbanyak($request)->original['tahun_angkatan_terbanyak'];

//     // Panggil method jumlahMahasiswaAktif untuk mendapatkan jumlah mahasiswa aktif
//     $jumlah_mahasiswa_aktif = $this->jumlahMahasiswaAktif($request)->original['jumlah_mahasiswa_aktif'];
//    // Panggil method jumlahMahasiswaLulus untuk mendapatkan jumlah mahasiswa lulus
//    $jumlah_mahasiswa_lulus = $this->jumlahMahasiswaLulus($request)->original['jumlah_mahasiswa_lulus'];


//     // Access the data returned by jumlahperangkatan differently
//     $jumlahperangkatanData = $this->jumlahperangkatan($request);
//     $query = isset($jumlahperangkatanData['query']) ? $jumlahperangkatanData['query'] : [];

//     // Pastikan variabel yang digunakan adalah $query, bukan $jumlah_angkatan

//     // Kemudian sertakan variabel $jumlah_mahasiswa dan $query dalam pemanggilan view
//     return view('dashboard.dashboard', compact('jumlah_mahasiswa_lulus','jumlah_mahasiswa_aktif','jumlah_mahasiswa','tahun_angkatan_terbanyak', 'query'));
// }


    

}

    

