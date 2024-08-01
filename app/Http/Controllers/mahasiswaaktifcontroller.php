<?php

namespace App\Http\Controllers;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class mahasiswaaktifcontroller extends Controller
{
    // public function  index($request, Closure $next)
    // {
    //         $user = Auth::user();
    //         $mahasiswa = null;
        
    //         $mahasiswa = DB::table('users')
    //         ->join('mahasiswa', 'users.ni', '=', 'mahasiswa.nim')
    //         ->where('users.ni', $user->ni)
    //         ->select('mahasiswa.nama')
    //         ->first();
    
    //     // Debugging
    //     //dd($mahasiswa);
    //     view()->share('mahasiswa', $mahasiswa);

    //     return $next($request);
    //     //return view('include.sidebar', ['mahasiswa' => $mahasiswa]);
    // }
    public function jumlah_mahasiswa_kelamin(Request $request){
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $query = [];
    
        if ($year && $selectedJurusan) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->select(
                    'm.kelamin',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('m.kelamin')
                ->orderBy('m.kelamin')
                ->get();
        }
    
        return view('mahasiswa_aktif.jenis_kelamin', compact('years', 'allJurusan', 'selectedJurusan', 'query'));
    }
    

    public function jurusan(Request $request){
       
        
        $years = DB::table('mahasiswa as m')
        ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
        ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
        ->where('r.status', '=', 'Aktif')
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();


        $jurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();


        $year = $request->input('year');
        $jurusan = $request->input('jurusan');
        $query = [];

        if ($year) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->select(
                    'j.jurusan',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('j.jurusan')
                ->orderBy('j.jurusan')
                ->get();

        

                // dd($query);
        }

        return view('mahasiswa_aktif.jurusan', compact('years', 'query'));
    }

    public function jenisseleksi(Request $request){
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
        
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $query = [];
        
        if ($year && $selectedJurusan) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('jenisseleksi as js', 'r.kd_jenisseleksi', '=', 'js.kd_jenisseleksi')
                ->select(
                    'js.jenis_seleksi',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('js.jenis_seleksi')
                ->orderBy('js.jenis_seleksi')
                ->get();
        }
    
        return view('mahasiswa_aktif.jenis_seleksi', compact('years', 'allJurusan', 'selectedJurusan', 'query'));
    }

    public function jurusansekolah(Request $request){
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
        
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $query = [];
        
        if ($year && $selectedJurusan) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('jurusansekolah as js', 'm.kd_jursekol', '=', 'js.kd_jursekol')
                ->select(
                    'js.jurusan_sekolah',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('js.jurusan_sekolah')
                ->orderBy('js.jurusan_sekolah')
                ->get();
        }
    
        return view('mahasiswa_aktif.jurusan_sekolah', compact('years', 'allJurusan', 'selectedJurusan', 'query'));
    }

    public function jenissekolah(Request $request){
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
        
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $query = [];
        
        if ($year && $selectedJurusan) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('jenissekolah as js', 'm.kd_jenissekolah', '=', 'js.kd_jenissekolah')
                ->select(
                    'js.jenis_sekolah',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('js.jenis_sekolah')
                ->orderBy('js.jenis_sekolah')
                ->get();
        }
    
        return view('mahasiswa_aktif.jenis_sekolah', compact('years', 'allJurusan', 'selectedJurusan', 'query'));
    }

    public function nilaibahasa(Request $request)
    {
        // Mengambil daftar tahun angkatan
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();

        // Mengambil daftar jurusan
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();

        // Mengambil input dari request
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $nilaiBahasa = $request->input('nilai_bahasa');
        $query = [];

        // Jika tahun, jurusan, dan nilai bahasa dipilih, lakukan query untuk mendapatkan data
        if ($year && $selectedJurusan && $nilaiBahasa) {
            if ($nilaiBahasa == 'toefl') {
                $query = DB::table('reg_mahasiswa as r')
                    ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                    ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                    ->select(
                        DB::raw('COUNT(CASE WHEN r.toefl >= 450 THEN 1 ELSE NULL END) AS jumlah_mahasiswa_lulus'),
                        DB::raw('COUNT(CASE WHEN r.toefl <= 449 THEN 1 ELSE NULL END) AS jumlah_mahasiswa_tidak_lulus')
                    )
                    ->whereYear('m.tahun_angkatan', '=', $year)
                    ->where('j.jurusan', '=', $selectedJurusan)
                    ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                    ->first();
                    //dd($query);
                    } else if ($nilaiBahasa == 'toafl') {
                        $query = DB::table('reg_mahasiswa as r')
                            ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                            ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                            ->select(
                                DB::raw('COUNT(CASE WHEN r.toafl >= 375 THEN 1 ELSE NULL END) AS jumlah_mahasiswa_lulus'),
                                DB::raw('COUNT(CASE WHEN r.toafl <= 374 THEN 1 ELSE NULL END) AS jumlah_mahasiswa_tidak_lulus')
                            )
                            ->whereYear('m.tahun_angkatan', '=', $year)
                            ->where('j.jurusan', '=', $selectedJurusan)
                            ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                            ->first();
            }
        }

        // Mengembalikan view dengan data yang dibutuhkan
        return view('mahasiswa_aktif.nilai_bahasa', compact('years', 'allJurusan', 'selectedJurusan', 'nilaiBahasa', 'query'));
    }


public function kategoriipk(Request $request)
{
    // Mengambil tahun angkatan yang tersedia
    $years = DB::table('mahasiswa as m')
        ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
        ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
        ->where('r.status', '=', 'Aktif')
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();

    // Mengambil daftar jurusan
    $allJurusan = DB::table('jurusan')
        ->select('jurusan')
        ->orderBy('jurusan')
        ->get();

    // Mengambil input tahun dan jurusan dari request
    $selectedYear = $request->input('year');
    $selectedJurusan = $request->input('jurusan');

    // Inisialisasi variabel query
    $query = [];

    // Jika tahun dan jurusan dipilih, lakukan query untuk mendapatkan jumlah mahasiswa per kategori IPK
    if ($selectedYear && $selectedJurusan) {
        $query = DB::table('reg_mahasiswa as r')
            ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
            ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                DB::raw("
                    CASE
                        WHEN r.ipk >= '2,75' AND r.ipk < '3,00' THEN 'Memuaskan'
                        WHEN r.ipk >= '3,00' AND r.ipk < '3,50' THEN 'Sangat Memuaskan'
                        WHEN r.ipk >= '3,50' THEN 'Pujian'
                        ELSE 'Perbaiki'
                    END AS kategoriipk
                "),
                DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
            )
            ->whereYear('m.tahun_angkatan', '=', $selectedYear)
            ->where('j.jurusan', '=', $selectedJurusan)
            ->where('r.status', '=', 'Aktif')
            ->groupBy('kategoriipk')
            ->orderBy('kategoriipk')
            ->get();
    }

    // Mengembalikan view dengan data yang diperlukan
    return view('mahasiswa_aktif.indeks_predikat_kumulatif', compact('years', 'allJurusan', 'selectedYear', 'selectedJurusan', 'query'));
}


    public function kota(Request $request){
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
        
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $query = [];
        
        if ($year && $selectedJurusan) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    'k.kota',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('k.kota')
                ->orderBy('k.kota')
                ->get();
        }
    
        return view('mahasiswa_aktif.kota', compact('years', 'allJurusan', 'selectedJurusan', 'query'));
    }

    public function kategorikota(Request $request)
{
    // Mengambil tahun angkatan yang tersedia
    $years = DB::table('mahasiswa as m')
        ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
        ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
        ->where('r.status', '=', 'Aktif')
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();

    // Mengambil daftar jurusan
    $allJurusan = DB::table('jurusan')
        ->select('jurusan')
        ->orderBy('jurusan')
        ->get();

    // Mengambil input tahun dan jurusan dari request
    $selectedYear = $request->input('year');
    $selectedJurusan = $request->input('jurusan');

    // Inisialisasi variabel query
    $query = [];

    // Jika tahun dan jurusan dipilih, lakukan query untuk mendapatkan jumlah mahasiswa per kategori kota
    if ($selectedYear && $selectedJurusan) {
        $query = DB::table('reg_mahasiswa as r')
            ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
            ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                DB::raw("
                    CASE
                        WHEN m.kd_kota IN ('P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 'P12000012', 'P12000112', 'P12000016') THEN 'Jabodetabek'
                        ELSE 'Luar Jabodetabek'
                    END AS kategori_kota
                "),
                DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
            )
            ->whereYear('m.tahun_angkatan', '=', $selectedYear)
            ->where('j.jurusan', '=', $selectedJurusan)
            ->where('r.status', '=', 'Aktif')
            ->groupBy('kategori_kota')
            ->get();
    }

    // Mengembalikan view dengan data yang diperlukan
    return view('mahasiswa_aktif.kategori_kota', compact('years', 'allJurusan', 'selectedYear', 'selectedJurusan', 'query'));
}

    public function provinsi(Request $request){
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            ->where('r.status', '=', 'Aktif')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
        
        $allJurusan = DB::table('jurusan')
            ->select('jurusan')
            ->orderBy('jurusan')
            ->get();
    
        $year = $request->input('year');
        $selectedJurusan = $request->input('jurusan');
        $query = [];
        
        if ($year && $selectedJurusan) {
            $query = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('provinsi as p', 'm.kd_provinsi', '=', 'p.kd_provinsi')
                ->select(
                    'p.provinsi',
                    DB::raw('COUNT(r.nim) as jumlah_mahasiswa')
                )
                ->whereYear('m.tahun_angkatan', '=', $year)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where('r.status', '=', 'Aktif')  // Memfilter berdasarkan status 'Lulus'
                ->groupBy('p.provinsi')
                ->orderBy('p.provinsi')
                ->get();
        }
    
        return view('mahasiswa_aktif.provinsi', compact('years', 'allJurusan', 'selectedJurusan', 'query'));
    }
    
}

