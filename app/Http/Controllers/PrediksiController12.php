<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    
    public function index(Request $request)
    {
        // Mengambil tahun angkatan yang tersedia
        $years = DB::table('mahasiswa as m')
            ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
            ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
            //->where('r.status', '=', 'Lulus')
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
        $query1 = [];
        $query2 = [];
        $query3 = [];
        $query4 = [];
        $query5 = [];
        $query6 = [];
        $query7 = [];
        $query8 = [];
        $query9 = [];
        $query10 = [];

        // Inisialisasi variabel entropylulus dan entropy_ipk
        $entropylulus = null;
        $entropy_ipk = [];
        $status_ipk = [];
        $entropy_jabo = [];
        $jabodetabek = [];
        $entropy_talus = [];
        $tahun_lulus = [];
        $gain_ipk = [];
        $gain_jabo = [];
        $gain_talus = [];
        $entropy_ipklanjut = [];
        $entropy_ipklanjut1 = [];
        $entropy_jabolanjut = [];
        $entropy_jabolanjut3 = [];
        $gain_ipklanjut = [];
        $gain_ipklanjut1 = [];
        $gain_jabolanjut = [];
        $gain_jabolanjut3 = [];
        $result = []; 
        $precision = [];
        $accuracy = [];
        $recall = [];
        $f1_score = [];

        // Jika tahun dan jurusan dipilih, lakukan query untuk mendapatkan jumlah mahasiswa per kategori kelulusan
        if ($selectedYear && $selectedJurusan) {
            // Query untuk mendapatkan jumlah mahasiswa per kategori kelulusan
            $query1 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->select(
                    DB::raw("CASE
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 'Tidak Tepat Lulus'
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 'Tepat Lulus'
                        ELSE 'Belum Lulus'
                    END AS status_lulus"),
                    DB::raw("SUM(CASE
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1
                        ELSE 0
                    END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1
                        ELSE 0
                    END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1
                        ELSE 0
                    END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                //->where('r.status', '=', 'Lulus')
                ->groupBy('status_lulus')
                ->orderBy('status_lulus', 'desc')
                ->get();

            // Query untuk mendapatkan jumlah mahasiswa per kategori IPK
            $query2 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->select(
                    DB::raw("CASE
                        WHEN r.ipk >= '2,75' AND r.ipk < '3,00' THEN 'Memuaskan'
                        WHEN r.ipk >= '3,00' AND r.ipk < '3,50' THEN 'Sangat Memuaskan'
                        WHEN r.ipk >= '3,50' THEN 'Pujian'
                        ELSE 'Perbaiki'
                    END AS status_ipk"),
                    DB::raw("SUM(CASE WHEN r.ipk >= '2,75' AND r.ipk < '3,00' THEN 1 ELSE 0 END) AS Memuaskan"),
                    DB::raw("SUM(CASE WHEN r.ipk >= '3,00' AND r.ipk < '3,50' THEN 1 ELSE 0 END) AS Sangat_Memuaskan"),
                    DB::raw("SUM(CASE WHEN r.ipk >= '3,50' THEN 1 ELSE 0 END) AS Pujian"),
                    DB::raw("SUM(CASE WHEN r.ipk < '2,75' THEN 1 ELSE 0 END) AS Perbaiki"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                //->where('r.status', '=', 'Lulus')
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->groupBy('status_ipk')
                ->orderBy('status_ipk', 'desc')
                ->get();

            $query3 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE 
                                WHEN k.kd_kota IN (
                                'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                                'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                                'P12000012', 'P12000112', 'P12000016') THEN 'Jabodetabek' 
                                ELSE 'Luar Jabodetabek' 
                            END AS jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016') THEN 1 ELSE 0 END) AS Jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota NOT IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016') THEN 1 ELSE 0 END) AS Luar_Jabodetabek"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->groupBy('jabodetabek')
                ->orderBy('jabodetabek')
                ->get();

            $query4 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->select(
                    DB::raw('YEAR(r.tanggal_lulus) AS tahun_lulus'), 
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) ) THEN 1 ELSE 0 END) AS lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->groupBy('tahun_lulus')
                ->orderBy('tahun_lulus')
                ->get();

            $query5 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE
                        WHEN r.ipk >= '2,75' AND r.ipk < '3,00' THEN 'Memuaskan'
                        WHEN r.ipk >= '3,00' AND r.ipk < '3,50' THEN 'Sangat Memuaskan'
                        WHEN r.ipk >= '3,50' THEN 'Pujian'
                        ELSE 'Perbaiki'
                    END AS status_ipk"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->whereIn('m.kd_kota', [
                    'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                    'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                    'P12000012', 'P12000112', 'P12000016'
                ])
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->groupBy('status_ipk')
                ->orderByDesc('status_ipk')
                ->get();

            $query6 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE
                        WHEN r.ipk >= '2,75' AND r.ipk < '3,00' THEN 'Memuaskan'
                        WHEN r.ipk >= '3,00' AND r.ipk < '3,50' THEN 'Sangat Memuaskan'
                        WHEN r.ipk >= '3,50' THEN 'Pujian'
                        ELSE 'Perbaiki'
                    END AS status_ipk"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 
                        ELSE 0 
                    END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 
                        ELSE 0 
                    END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 
                        ELSE 0 
                    END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->whereNotIn('k.kd_kota', [
                    'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                    'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                    'P12000012', 'P12000112', 'P12000016'
                ])
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->groupBy('status_ipk')
                ->orderByDesc('status_ipk')
                ->get();

            $query7 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE 
                        WHEN k.kd_kota IN (
                            'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                            'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                            'P12000012', 'P12000112', 'P12000016'
                        ) THEN 'Jabodetabek' 
                        ELSE 'Luar Jabodetabek' 
                    END AS jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016'
                    ) THEN 1 ELSE 0 END) AS Jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota NOT IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016'
                    ) THEN 1 ELSE 0 END) AS Luar_Jabodetabek"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 
                    END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 
                    END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 
                    END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->whereYear('m.tahun_angkatan', $selectedYear)
                ->where('j.jurusan', $selectedJurusan)
                ->where('r.ipk', '<', '2,75')
                ->groupBy('jabodetabek')
                ->orderBy('jabodetabek')
                ->get();

            $query8 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE 
                        WHEN k.kd_kota IN (
                            'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                            'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                            'P12000012', 'P12000112', 'P12000016'
                        ) THEN 'Jabodetabek' 
                        ELSE 'Luar Jabodetabek' 
                    END AS jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016'
                    ) THEN 1 ELSE 0 END) AS Jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota NOT IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016') THEN 1 ELSE 0 END) AS Luar_Jabodetabek"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 
                    END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 
                    END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 
                    END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where(function ($query) {
                    $query->where('r.ipk', '>=', '2,75')
                        ->where('r.ipk', '<', '3,00');
                })
                ->groupBy('jabodetabek')
                ->orderBy('jabodetabek')
                ->get();

            $query9 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE 
                        WHEN k.kd_kota IN (
                            'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                            'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                            'P12000012', 'P12000112', 'P12000016'
                        ) THEN 'Jabodetabek' 
                        ELSE 'Luar Jabodetabek' 
                    END AS jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016') THEN 1 ELSE 0 END) AS Jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota NOT IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016') THEN 1 ELSE 0 END) AS Luar_Jabodetabek"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 
                    END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 
                    END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 
                    END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->where('m.tahun_angkatan', '=', $selectedYear)
                ->where('j.jurusan', '=', $selectedJurusan)
                ->where(function ($query) {
                    $query->where('r.ipk', '>=', '3,00')
                        ->where('r.ipk', '<', '3,50');
                })
                ->groupBy('jabodetabek')
                ->orderBy('jabodetabek')
                ->get();

            $query10 = DB::table('reg_mahasiswa as r')
                ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
                ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
                ->join('kota as k', 'm.kd_kota', '=', 'k.kd_kota')
                ->select(
                    DB::raw("CASE 
                        WHEN k.kd_kota IN (
                            'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                            'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                            'P12000012', 'P12000112', 'P12000016'
                        ) THEN 'Jabodetabek' 
                        ELSE 'Luar Jabodetabek' 
                    END AS jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016'
                    ) THEN 1 ELSE 0 END) AS Jabodetabek"),
                    DB::raw("SUM(CASE WHEN k.kd_kota NOT IN (
                        'P11000011', 'P11000012', 'P11000013', 'P11000014', 'P11000015', 
                        'P13000013', 'P13000014', 'P13000018', 'P12000013', 'P12000113', 
                        'P12000012', 'P12000112', 'P12000016') THEN 1 ELSE 0 END) AS Luar_Jabodetabek"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 
                    END) AS Tidak_Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 
                    END) AS Tepat_Lulus"),
                    DB::raw("SUM(CASE 
                        WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 
                    END) AS Belum_Lulus"),
                    DB::raw("COUNT(*) AS jumlah_mahasiswa")
                )
                ->whereYear('m.tahun_angkatan', $selectedYear)
                ->where('j.jurusan', $selectedJurusan)
                ->where('r.ipk', '>=', '3,50')
                ->groupBy('jabodetabek')
                ->orderBy('jabodetabek')
                ->get();

            // Menghitung total jumlah mahasiswa
            $totalMahasiswa1 = $query1->sum('jumlah_mahasiswa');
            $totalMahasiswa2 = $query2->sum('jumlah_mahasiswa');
            $totalMahasiswa3 = $query3->sum('jumlah_mahasiswa');
            $totalMahasiswa4 = $query4->sum('jumlah_mahasiswa');
            $totalMahasiswa5 = $query5->sum('jumlah_mahasiswa');
            $totalMahasiswa6 = $query6->sum('jumlah_mahasiswa');
            $totalMahasiswa7 = $query7->sum('jumlah_mahasiswa');
            $totalMahasiswa8 = $query8->sum('jumlah_mahasiswa');
            $totalMahasiswa9 = $query9->sum('jumlah_mahasiswa');
            $totalMahasiswa10 = $query10->sum('jumlah_mahasiswa');

            // Mengambil data status IPK dari hasil query
            $status_ipk = $query2->pluck('status_ipk')->unique();
            $jabodetabek = $query3->pluck('jabodetabek')->unique(); 
            $tahun_lulus = $query4->pluck('tahun_lulus')->unique();
            $status_ipk = $query5->pluck('status_ipk')->unique();
            $status_ipk = $query6->pluck('status_ipk')->unique();
            $jabodetabek = $query7->pluck('jabodetabek')->unique();
            $jabodetabek = $query8->pluck('jabodetabek')->unique(); 
            $jabodetabek = $query9->pluck('jabodetabek')->unique();    
            $jabodetabek = $query10->pluck('jabodetabek')->unique();  

            //Menghitung entropi lulus berdasarkan status kelulusan
            if ($totalMahasiswa1 > 0) {
                $entropylulus = $query1->sum(function ($item) use ($totalMahasiswa1) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) {
                        return 0;
                    }
                    $probability = $totalStatus / $totalMahasiswa1;
                    
                    return -$probability * log($probability, 2);
                });
            
                $entropylulus = [
                    [
                        'Tidak_Tepat_Lulus' => $query1->sum('Tidak_Tepat_Lulus'),
                        'Tepat_Lulus' => $query1->sum('Tepat_Lulus'),
                        'Belum_Lulus' => $query1->sum('Belum_Lulus'),
                        'jumlah_mahasiswa' => $query1->sum('jumlah_mahasiswa'),
                        'entropy' => $entropylulus
                    ]
                ];
            }
        
            if ($totalMahasiswa2 > 0) {
                $entropy_ipk = $query2->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->status_ipk => 0];
            
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
            
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
                    return [$item->status_ipk => round($entropy, 3)];
                });
            
                // Menghitung gain IPK
                $gain_ipk = $entropylulus[0]['entropy'] ?? 0;
                foreach ($query2 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa2;
                    $gain_ipk -= $probability * ($entropy_ipk[$item->status_ipk] ?? 0);
                }
            
                $entropy_ipk = $query2->map(function ($item) use ($entropy_ipk, $gain_ipk) {
                    return [
                        'status_ipk' => $item->status_ipk,
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_ipk[$item->status_ipk] ?? 'Tidak ada data',
                        'gain_ipk' => $gain_ipk
                    ];
                });
            } else {
                $entropy_ipk = collect([]);
                $gain_ipk = 0;
            }

            if ($totalMahasiswa3 > 0) {
                $entropy_jabo = $query3->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->jabodetabek => 0];
                
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
                
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
                    return [$item->jabodetabek => round($entropy, 3)];
                });
            
                $gain_jabo = $entropylulus[0]['entropy'] ?? 0;
                foreach ($query3 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa3;
                    $gain_jabo -= $probability * ($entropy_jabo[$item->jabodetabek] ?? 0);
                }
                
                $entropy_jabo = $query3->map(function ($item) use ($entropy_jabo, $gain_jabo) {
                    return [
                        'jabodetabek' => $item->jabodetabek,
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_jabo[$item->jabodetabek] ?? 'Tidak ada data',
                        'gain_jabo' => $gain_jabo
                    ];
                });
            } else {
                $entropy_jabo = collect([]);
                $gain_jabo = 0;
            }

            if ($totalMahasiswa4 > 0) {
                $entropy_talus_calculated = $query4->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->tahun_lulus => 0];
                    
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
            
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
            
                    return [$item->tahun_lulus => round($entropy, 3)];
                });
            
                $gain_talus = $entropylulus[0]['entropy'] ?? 0;
                foreach ($query4 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa4;
                    $gain_talus -= $probability * ($entropy_talus_calculated[$item->tahun_lulus] ?? 0);
                }
            
                $entropy_talus = $query4->map(function ($item) use ($entropy_talus_calculated, $gain_talus) {
                    return [
                        'tahun_lulus' => $item->tahun_lulus ?? 'Tidak ada data',
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_talus_calculated[$item->tahun_lulus] ?? 'Tidak ada data',
                        'gain_talus' => $gain_talus
                    ];
                });
            } else {
                $entropy_talus = collect([]);
                $gain_talus = 0;
            }

            if ($totalMahasiswa5 > 0) {
                $entropy_ipklanjut = $query5->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->status_ipk => 0];
            
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
            
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
                    return [$item->status_ipk => round($entropy, 3)];
                });
            
                $entropy_total = 0;
                if ($totalMahasiswa5 > 0) {
                    $statuses_total = [
                        'Tidak_Tepat_Lulus' => array_sum(array_column($query5->toArray(), 'Tidak_Tepat_Lulus')),
                        'Tepat_Lulus' => array_sum(array_column($query5->toArray(), 'Tepat_Lulus')),
                        'Belum_Lulus' => array_sum(array_column($query5->toArray(), 'Belum_Lulus')),
                    ];
            
                    foreach ($statuses_total as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalMahasiswa5;
                            $entropy_total -= $probability * log($probability, 2);
                        }
                    }
                }
            
                $gain_ipklanjut = $entropylulus[0]['entropy'] ?? 0;
                foreach ($query5 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa5;
                    $gain_ipklanjut -= $probability * ($entropy_ipklanjut[$item->status_ipk] ?? 0);
                }
            
                $entropy_ipklanjut = $query5->map(function ($item) use ($entropy_ipklanjut, $gain_ipklanjut) {
                    return [
                        'status_ipk' => $item->status_ipk,
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_ipklanjut[$item->status_ipk] ?? 'Tidak ada data',
                        'gain_ipklanjut' => round($gain_ipklanjut, 11)
                    ];
                });
            } else {
                $entropy_ipklanjut = collect([]);
                $gain_ipklanjut = 0;
            }

            if ($totalMahasiswa6 > 0) {
                $entropy_ipklanjut1 = $query6->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->status_ipk => 0];
                    
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
                    
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
                    return [$item->status_ipk => round($entropy, 3)];
                });
        
                $entropy_total = 0;
                if ($totalMahasiswa6 > 0) {
                    $statuses_total = [
                        'Tidak_Tepat_Lulus' => array_sum(array_column($query6->toArray(), 'Tidak_Tepat_Lulus')),
                        'Tepat_Lulus' => array_sum(array_column($query6->toArray(), 'Tepat_Lulus')),
                        'Belum_Lulus' => array_sum(array_column($query6->toArray(), 'Belum_Lulus')),
                    ];
                    
                    foreach ($statuses_total as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalMahasiswa6;
                            $entropy_total -= $probability * log($probability, 2);
                        }
                    }
                }
            
                $gain_ipklanjut1 = $entropylulus[0]['entropy'] ?? 0;
                foreach ($query6 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa6;
                    $gain_ipklanjut1 -= $probability * ($entropy_ipklanjut1[$item->status_ipk] ?? 0);
                }
            
                $entropy_ipklanjut1 = $query6->map(function ($item) use ($entropy_ipklanjut1, $gain_ipklanjut1) {
                    return [
                        'status_ipk' => $item->status_ipk,
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_ipklanjut1[$item->status_ipk] ?? 'Tidak ada data',
                        'gain_ipklanjut1' => round($gain_ipklanjut1, 11)
                    ];
                });
            } else {
                $entropy_ipklanjut1 = collect([]);
                $gain_ipklanjut1 = 0;
            }

            if ($totalMahasiswa7 > 0) {
                $entropy_jabolanjut = $query7->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->jabodetabek => 0];
                    
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
                    
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
                    return [$item->jabodetabek => round($entropy, 3)];
                });
                
                $entropy_total = $entropylulus[0]['entropy'] ?? 0;
                
                $gain_jabolanjut = $entropy_total;
                foreach ($query7 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa7;
                    $gain_jabolanjut -= $probability * ($entropy_jabolanjut[$item->jabodetabek] ?? 0);
                }
                
                $entropy_jabolanjut = $query7->map(function ($item) use ($entropy_jabolanjut, $gain_jabolanjut) {
                    return [
                        'jabodetabek' => $item->jabodetabek,
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_jabolanjut[$item->jabodetabek] ?? 'Tidak ada data',
                        'gain_jabolanjut' => round($gain_jabolanjut, 11)
                    ];
                });
            } else {
                $entropy_jabolanjut = collect([]);
                $gain_jabolanjut = 0;
            }

            if ($totalMahasiswa9 > 0) {
                $entropy_jabolanjut3 = $query9->mapWithKeys(function ($item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    if ($totalStatus == 0) return [$item->jabodetabek => 0];
                    
                    $entropy = 0;
                    $statuses = [
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                    ];
                    
                    foreach ($statuses as $count) {
                        if ($count > 0) {
                            $probability = $count / $totalStatus;
                            $entropy -= $probability * log($probability, 2);
                        }
                    }
                    return [$item->jabodetabek => round($entropy, 3)];
                });
        
                $entropy_total = $entropylulus[0]['entropy'] ?? 0;
        
                $gain_jabolanjut3 = $entropy_total;
                foreach ($query9 as $item) {
                    $totalStatus = $item->Tidak_Tepat_Lulus + $item->Tepat_Lulus + $item->Belum_Lulus;
                    $probability = $totalStatus / $totalMahasiswa9;
                    $gain_jabolanjut3 -= $probability * ($entropy_jabolanjut3[$item->jabodetabek] ?? 0);
                }
        
                $entropy_jabolanjut3 = $query9->map(function ($item) use ($entropy_jabolanjut3, $gain_jabolanjut3) {
                    return [
                        'jabodetabek' => $item->jabodetabek,
                        'Tidak_Tepat_Lulus' => $item->Tidak_Tepat_Lulus,
                        'Tepat_Lulus' => $item->Tepat_Lulus,
                        'Belum_Lulus' => $item->Belum_Lulus,
                        'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                        'entropy' => $entropy_jabolanjut3[$item->jabodetabek] ?? 'Tidak ada data',
                        'gain_jabolanjut' => round($gain_jabolanjut3, 11)
                    ];
                });
            }

            $max_gain = max($gain_ipk, $gain_talus, $gain_jabo);
$highest_gain_type = '';
$highest_gain_value = 0;
$data = [];

if ($max_gain == $gain_ipk) {
    $highest_gain_type = 'IPK';
    $highest_gain_value = $gain_ipk;
    $data = $query2; // $query2 sesuai dengan data yang diharapkan
} elseif ($max_gain == $gain_talus) {
    $highest_gain_type = 'Tahun Lulus';
    $highest_gain_value = $gain_talus;
    $data = $query4; // $query4 sesuai dengan data yang diharapkan
} elseif ($max_gain == $gain_jabo) {
    $highest_gain_type = 'Jabodetabek';
    $highest_gain_value = $gain_jabo;
    $data = $query3; // $query3 sesuai dengan data yang diharapkan
}

$TP = $FP = $TN = $FN = 0;

foreach ($data as $item) {
    $TP += $item->Tepat_Lulus;
    $FP += $item->Tidak_Tepat_Lulus;
    $TN += $item->Belum_Lulus;
    $FN += $item->Belum_Lulus;
}

$precision = ($TP + $FP) > 0 ? ($TP / ($TP + $FP)) * 100 : 0;
$accuracy = ($TP + $TN + $FP + $FN) > 0 ? (($TP + $TN) / ($TP + $TN + $FP + $FN)) * 100 : 0;
$recall = ($TP + $FN) > 0 ? ($TP / ($TP + $FN)) * 100 : 0;
$f1_score = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;

$result = [
    'highest_gain_type' => $highest_gain_type,
    'highest_gain_value' => $highest_gain_value,
    'precision' => round($precision, 2) . '%',
    'accuracy' => round($accuracy, 2) . '%',
    'recall' => round($recall, 2) . '%',
    'f1_score' => round($f1_score, 2) . '%',
];

        }

        return view('prediksi.prediksi', compact(
            'years', 'allJurusan', 'selectedYear', 'selectedJurusan',
            'query1', 'query2', 'query3', 'query4', 'query5', 'query6', 'query7',
            'entropylulus', 'entropy_ipk', 'entropy_talus', 'entropy_ipklanjut', 'entropy_ipklanjut1',
            'entropy_jabo', 'entropy_jabolanjut', 'entropy_jabolanjut3',
            'jabodetabek', 'status_ipk', 'tahun_lulus',
            'gain_ipk', 'gain_ipklanjut', 'gain_talus', 'gain_ipklanjut1',
            'gain_jabo', 'gain_jabolanjut', 'gain_jabolanjut3', 'result',
        ));
    }
}
