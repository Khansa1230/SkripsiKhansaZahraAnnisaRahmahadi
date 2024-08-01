<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediksiController extends Controller
{

    public function index(Request $request)
{
    
    $selectedPercentage = $request->input('selectedPercentage', 100); // Default 100% jika tidak ada input
    $percentageMultiplier = $selectedPercentage / 100;
    if ($percentageMultiplier) {

        $query5 = DB::table('data_uji as r')
        ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
        ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
        ->select(
            'r.status_lulus as status_lulus',
            'r.prediksi as prediksi',
            DB::raw("SUM(CASE WHEN r.status_lulus = 'Tepat Lulus' THEN 1 ELSE 0 END) AS Tepat_Lulus"),
            DB::raw("SUM(CASE WHEN r.status_lulus = 'Tidak Tepat Lulus' THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
            DB::raw("SUM(CASE WHEN r.status_lulus = 'Belum Lulus' THEN 1 ELSE 0 END) AS Belum_Lulus"),
            DB::raw("SUM(CASE WHEN r.prediksi = 'Tepat Lulus' THEN 1 ELSE 0 END) AS Tepat_Lulus_prediksi"),
            DB::raw("SUM(CASE WHEN r.prediksi = 'Tidak Tepat Lulus' THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus_prediksi"),
            DB::raw("SUM(CASE WHEN r.prediksi = 'Belum Lulus' THEN 1 ELSE 0 END) AS Belum_Lulus_prediksi"),
            DB::raw("COUNT(*) AS jumlah_mahasiswa")
        )
        ->groupBy('status_lulus', 'prediksi')
        ->orderBy('status_lulus', 'desc')
        ->orderBy('prediksi', 'desc')
        ->get();
    
    //dd($query5);

        

    // Query 2: Status IPK
    $query6 = DB::table('data_uji as r')
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
            DB::raw("SUM(IF(r.status_lulus = 'Tepat Lulus', 1, 0)) AS Tepat_Lulus"),
            DB::raw("SUM(IF(r.status_lulus = 'Tidak Tepat Lulus', 1, 0)) AS Tidak_Tepat_Lulus"),
            DB::raw("SUM(IF(r.status_lulus = 'Belum Lulus', 1, 0)) AS Belum_Lulus"),
            DB::raw("SUM(IF(r.prediksi = 'Tepat Lulus', 1, 0)) AS Tepat_Lulus_prediksi"),
            DB::raw("SUM(IF(r.prediksi = 'Tidak Tepat Lulus', 1, 0)) AS Tidak_Tepat_Lulus_prediksi"),
            DB::raw("SUM(IF(r.prediksi = 'Belum Lulus', 1, 0)) AS Belum_Lulus_prediksi"),
            DB::raw("COUNT(*) AS jumlah_mahasiswa")
        )
        ->groupBy('status_ipk')
        ->orderBy('status_ipk', 'desc')
        ->get();
        //dd($query2);


    // Query 3: Status Jabodetabek
    $query7 = DB::table('data_uji as r')
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
                DB::raw("SUM(IF(r.status_lulus = 'Tepat Lulus', 1, 0)) AS Tepat_Lulus"),
                DB::raw("SUM(IF(r.status_lulus = 'Tidak Tepat Lulus', 1, 0)) AS Tidak_Tepat_Lulus"),
                DB::raw("SUM(IF(r.status_lulus = 'Belum Lulus', 1, 0)) AS Belum_Lulus"),
                DB::raw("SUM(IF(r.prediksi = 'Tepat Lulus', 1, 0)) AS Tepat_Lulus_prediksi"),
                DB::raw("SUM(IF(r.prediksi = 'Tidak Tepat Lulus', 1, 0)) AS Tidak_Tepat_Lulus_prediksi"),
                DB::raw("SUM(IF(r.prediksi = 'Belum Lulus', 1, 0)) AS Belum_Lulus_prediksi"),
            DB::raw("COUNT(*) AS jumlah_mahasiswa")
        )
        ->groupBy('jabodetabek')
        ->orderBy('jabodetabek')
        ->get();

    // Query 4: Statistik Tahun Lulus
    $query8 = DB::table('data_uji as r')
        ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
        ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
        ->select(
            DB::raw('YEAR(r.tanggal_lulus) AS tahun_lulus'), 
            DB::raw("SUM(CASE WHEN r.status_lulus = 'Tepat Lulus' THEN 1 ELSE 0 END) AS Tepat_Lulus"),
            DB::raw("SUM(CASE WHEN r.status_lulus = 'Tidak Tepat Lulus' THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
            DB::raw("SUM(CASE WHEN r.status_lulus = 'Belum Lulus' THEN 1 ELSE 0 END) AS Belum_Lulus"),
            DB::raw("SUM(IF(r.prediksi = 'Tepat Lulus', 1, 0)) AS Tepat_Lulus_prediksi"),
            DB::raw("SUM(IF(r.prediksi = 'Tidak Tepat Lulus', 1, 0)) AS Tidak_Tepat_Lulus_prediksi"),
            DB::raw("SUM(IF(r.prediksi = 'Belum Lulus', 1, 0)) AS Belum_Lulus_prediksi"),
            DB::raw("COUNT(*) AS jumlah_mahasiswa")
        )
        ->groupBy('tahun_lulus')
        ->orderBy('tahun_lulus')
        ->get();
        

        }

    $result5 = [
        'query5' => $query5->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
            
            $tidakTepatLulusPrediksi = isset($item->Tidak_Tepat_Lulus_prediksi) ? round($item->Tidak_Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $tepatLulusPrediksi = isset($item->Tepat_Lulus_prediksi) ? round($item->Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $belumLulusPrediksi = isset($item->Belum_Lulus_prediksi) ? round($item->Belum_Lulus_prediksi * $percentageMultiplier) : 0;
            $jumlahMahasiswaPrediksi = $tidakTepatLulusPrediksi + $tepatLulusPrediksi + $belumLulusPrediksi;
    
            $result = [
                'status_lulus' => $item->status_lulus ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa,
                'Tidak_Tepat_Lulus_prediksi' => $tidakTepatLulusPrediksi,
                'Tepat_Lulus_prediksi' => $tepatLulusPrediksi,
                'Belum_Lulus_prediksi' => $belumLulusPrediksi,
                'jumlah_mahasiswa2' => $jumlahMahasiswaPrediksi
            ];
    
            return $result;
        }),
        'entropy' => function ($result5) {
            // Mengambil total mahasiswa dari result1
            $totalMahasiswa1 = $result5['query5']->sum('jumlah_mahasiswa1');
            $totalMahasiswa2 = $result5['query5']->sum('jumlah_mahasiswa2');
    
            if ($totalMahasiswa1 == 0 || $totalMahasiswa2 == 0) {
                return ['entropy1' => 0, 'entropy2' => 0];
            }
    
            // Menghitung probabilitas dari setiap kategori
            $p1 = $result5['query5']->sum('Tidak_Tepat_Lulus') / $totalMahasiswa1;
            $p2 = $result5['query5']->sum('Tepat_Lulus') / $totalMahasiswa1;
            $p3 = $result5['query5']->sum('Belum_Lulus') / $totalMahasiswa1;
            $p4 = $result5['query5']->sum('Tidak_Tepat_Lulus_prediksi') / $totalMahasiswa2;
            $p5 = $result5['query5']->sum('Tepat_Lulus_prediksi') / $totalMahasiswa2;
            $p6 = $result5['query5']->sum('Belum_Lulus_prediksi') / $totalMahasiswa2;
    
            // Inisialisasi entropy
            $entropy1 = 0;
            $entropy2 = 0;
    
            // Hitung entropy dengan formula sum(-Pi * log2(Pi))
            $probabilities1 = [$p1, $p2, $p3];
            $probabilities2 = [$p4, $p5, $p6];
    
            foreach ($probabilities1 as $probability) {
                if ($probability > 0) {
                    $entropy1 -= $probability * log($probability, 2);
                }
            }
    
            foreach ($probabilities2 as $probability2) {
                if ($probability2 > 0) {
                    $entropy2 -= $probability2 * log($probability2, 2);
                }
            }
    
            return [
                'entropy1' => round($entropy1, 3),
                'entropy2' => round($entropy2, 3)
            ];
        }
    ];
    
    // Hitung nilai entropy dengan memanggil fungsi entropy
    $entropies = $result5['entropy']($result5);
    $result5['entropy1'] = $entropies['entropy1'];
    $result5['entropy2'] = $entropies['entropy2'];
    
    // Outputkan hasil
    //dd($result5);

    // Anda memulai dari query5 dan query6 yang telah dihasilkan sebelumnya

    $result6 = [
        'query6' => $query6->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;

            $tidakTepatLulusPrediksi = isset($item->Tidak_Tepat_Lulus_prediksi) ? round($item->Tidak_Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $tepatLulusPrediksi = isset($item->Tepat_Lulus_prediksi) ? round($item->Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $belumLulusPrediksi = isset($item->Belum_Lulus_prediksi) ? round($item->Belum_Lulus_prediksi * $percentageMultiplier) : 0;
            $jumlahMahasiswaPrediksi = $tidakTepatLulusPrediksi + $tepatLulusPrediksi + $belumLulusPrediksi;

            $result = [
                'status_ipk' => $item->status_ipk ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa,
                'Tidak_Tepat_Lulus_prediksi' => $tidakTepatLulusPrediksi,
                'Tepat_Lulus_prediksi' => $tepatLulusPrediksi,
                'Belum_Lulus_prediksi' => $belumLulusPrediksi,
                'jumlah_mahasiswa2' => $jumlahMahasiswaPrediksi
            ];

            // Hitung nilai entropy untuk setiap item
            if ($jumlahMahasiswa > 0 && $jumlahMahasiswaPrediksi > 0) {
                // Menghitung probabilitas dari setiap kategori
                $p1 = $result['Tidak_Tepat_Lulus'] / $jumlahMahasiswa;
                $p2 = $result['Tepat_Lulus'] / $jumlahMahasiswa;
                $p3 = $result['Belum_Lulus'] / $jumlahMahasiswa;
    
                $p4 = $result['Tidak_Tepat_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                $p5 = $result['Tepat_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                $p6 = $result['Belum_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                // Inisialisasi entropy
                $entropy1 = 0;
                $entropy2 = 0;

                // Hitung entropy dengan formula sum(-Pi * log2(Pi))
                $probabilities1 = [$p1, $p2, $p3];
                $probabilities2 = [$p4, $p5, $p6];
                foreach ($probabilities1 as $probability) {
                    if ($probability > 0) {
                        $entropy1 -= $probability * log($probability, 2);
                    }
                }

                foreach ($probabilities2 as $probability2) {
                    if ($probability2 > 0) {
                        $entropy2 -= $probability2 * log($probability2, 2);
                    }
                }

                $result['entropy1'] = round($entropy1, 3);
                $result['entropy2'] = round($entropy2, 3);
            } else {
                $result['entropy1'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
                $result['entropy2'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
            }

            return $result;
        }),
    ];

    $gain_ipk1 =  $result5['entropy1']; // Ambil entropy awal dari $result1
    $gain_ipk2 =  $result5['entropy2'];

    foreach ($result6['query6'] as $item ) {
        $totalMahasiswa1 = $result5['query5']->sum('jumlah_mahasiswa1');
        $totalMahasiswa2 = $result5['query5']->sum('jumlah_mahasiswa2');

        if ($totalMahasiswa1 > 0 && $totalMahasiswa2 > 0) {
            // Menghitung probabilitas gabungan
            $probability1 = $item['jumlah_mahasiswa1'] / $totalMahasiswa1;
            $probability2 = $item['jumlah_mahasiswa2'] / $totalMahasiswa2;
            // Mengurangi entropy untuk item saat ini dari gain_jabodetabek
            if (isset($item['entropy1']) && $probability1 > 0) {
                $gain_ipk1 -= $probability1 * $item['entropy1'];
            }
            if (isset($item['entropy2']) && $probability2 > 0) {
                $gain_ipk2 -= $probability2 * $item['entropy2'];
            }
        }
    }

    // Bulatkan gain_jabodetabek ke 3 angka di belakang koma
    $gain_ipk1 = round($gain_ipk1, 3);
    $gain_ipk2 = round($gain_ipk2, 3);

    // Tambahkan gain_jabodetabek ke dalam $result3
    $result6['gain_ipk1'] = $gain_ipk1;
    $result6['gain_ipk2'] = $gain_ipk2;
    // Outputkan hasil
    //dd($result3, $query3, $totalMahasiswa,$probability ); 
    

    $result7 = [
        'query7' => $query7->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $tidakTepatLulusPrediksi = isset($item->Tidak_Tepat_Lulus_prediksi) ? round($item->Tidak_Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $tepatLulusPrediksi = isset($item->Tepat_Lulus_prediksi) ? round($item->Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $belumLulusPrediksi = isset($item->Belum_Lulus_prediksi) ? round($item->Belum_Lulus_prediksi * $percentageMultiplier) : 0;
            $jumlahMahasiswaPrediksi = $tidakTepatLulusPrediksi + $tepatLulusPrediksi + $belumLulusPrediksi;
    
            $result = [
                'jabodetabek' => $item->jabodetabek ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa,
                'Tidak_Tepat_Lulus_prediksi' => $tidakTepatLulusPrediksi,
                'Tepat_Lulus_prediksi' => $tepatLulusPrediksi,
                'Belum_Lulus_prediksi' => $belumLulusPrediksi,
                'jumlah_mahasiswa2' => $jumlahMahasiswaPrediksi


            ];
    
            // Menghitung nilai entropy untuk setiap item
            if ($jumlahMahasiswa > 0 && $jumlahMahasiswaPrediksi > 0) {
                // Menghitung probabilitas dari setiap kategori
                $p1 = $result['Tidak_Tepat_Lulus'] / $jumlahMahasiswa;
                $p2 = $result['Tepat_Lulus'] / $jumlahMahasiswa;
                $p3 = $result['Belum_Lulus'] / $jumlahMahasiswa;
    
                $p4 = $result['Tidak_Tepat_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                $p5 = $result['Tepat_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                $p6 = $result['Belum_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
    
                // Inisialisasi entropy
                $entropy1 = 0;
                $entropy2 = 0;
    
                // Hitung entropy dengan formula sum(-Pi * log2(Pi))
                $probabilities1 = [$p1, $p2, $p3];
                $probabilities2 = [$p4, $p5, $p6];
                foreach ($probabilities1 as $probability) {
                    if ($probability > 0) {
                        $entropy1 -= $probability * log($probability, 2);
                    }
                }
    
                foreach ($probabilities2 as $probability2) {
                    if ($probability2 > 0) {
                        $entropy2 -= $probability2 * log($probability2, 2);
                    }
                }
    
                $result['entropy1'] = round($entropy1, 3);
                $result['entropy2'] = round($entropy2, 3);
            } else {
                $result['entropy1'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
                $result['entropy2'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
            }
    
            return $result;
        }),
    ];
    
    $gain_jabodetabek1 =  $result5['entropy1']; // Ambil entropy awal dari $result1
    $gain_jabodetabek2 =  $result5['entropy2'];

    foreach ($result7['query7'] as $item ) {
        $totalMahasiswa1 = $result5['query5']->sum('jumlah_mahasiswa1');
        $totalMahasiswa2 = $result5['query5']->sum('jumlah_mahasiswa2');

        if ($totalMahasiswa1 > 0 && $totalMahasiswa2 > 0) {
            // Menghitung probabilitas gabungan
            $probability1 = $item['jumlah_mahasiswa1'] / $totalMahasiswa1;
            $probability2 = $item['jumlah_mahasiswa2'] / $totalMahasiswa2;
            // Mengurangi entropy untuk item saat ini dari gain_jabodetabek
            if (isset($item['entropy1']) && $probability1 > 0) {
                $gain_jabodetabek1 -= $probability1 * $item['entropy1'];
            }
            if (isset($item['entropy2']) && $probability2 > 0) {
                $gain_jabodetabek2 -= $probability2 * $item['entropy2'];
            }
        }
    }

    // Bulatkan gain_jabodetabek ke 3 angka di belakang koma
    $gain_jabodetabek1 = round($gain_jabodetabek1, 3);
    $gain_jabodetabek2 = round($gain_jabodetabek2, 3);

    // Tambahkan gain_jabodetabek ke dalam $result3
    $result7['gain_jabodetabek1'] = $gain_jabodetabek1;
    $result7['gain_jabodetabek2'] = $gain_jabodetabek2;
    // Outputkan hasil
    //dd($result3, $query3, $totalMahasiswa,$probability ); 
    
    
    $result8 = [
        'query8' => $query8->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
            $tidakTepatLulusPrediksi = isset($item->Tidak_Tepat_Lulus_prediksi) ? round($item->Tidak_Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $tepatLulusPrediksi = isset($item->Tepat_Lulus_prediksi) ? round($item->Tepat_Lulus_prediksi * $percentageMultiplier) : 0;
            $belumLulusPrediksi = isset($item->Belum_Lulus_prediksi) ? round($item->Belum_Lulus_prediksi * $percentageMultiplier) : 0;
            $jumlahMahasiswaPrediksi = $tidakTepatLulusPrediksi + $tepatLulusPrediksi + $belumLulusPrediksi;
    
            $result = [
                'tahun_lulus' => $item->tahun_lulus ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa, // Menggunakan 'jumlah_mahasiswa1' sesuai permintaan
                'Tidak_Tepat_Lulus_prediksi' => $tidakTepatLulusPrediksi,
                'Tepat_Lulus_prediksi' => $tepatLulusPrediksi,
                'Belum_Lulus_prediksi' => $belumLulusPrediksi,
                'jumlah_mahasiswa2' => $jumlahMahasiswaPrediksi
            ];
            
    
            if ($jumlahMahasiswa > 0 && $jumlahMahasiswaPrediksi > 0) {
                // Menghitung probabilitas dari setiap kategori
                $p1 = $result['Tidak_Tepat_Lulus'] / $jumlahMahasiswa;
                $p2 = $result['Tepat_Lulus'] / $jumlahMahasiswa;
                $p3 = $result['Belum_Lulus'] / $jumlahMahasiswa;
    
                $p4 = $result['Tidak_Tepat_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                $p5 = $result['Tepat_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
                $p6 = $result['Belum_Lulus_prediksi'] / $jumlahMahasiswaPrediksi;
    
                // Inisialisasi entropy
                $entropy1 = 0;
                $entropy2 = 0;
    
                // Hitung entropy dengan formula sum(-Pi * log2(Pi))
                $probabilities1 = [$p1, $p2, $p3];
                $probabilities2 = [$p4, $p5, $p6];
                foreach ($probabilities1 as $probability) {
                    if ($probability > 0) {
                        $entropy1 -= $probability * log($probability, 2);
                    }
                }
    
                foreach ($probabilities2 as $probability2) {
                    if ($probability2 > 0) {
                        $entropy2 -= $probability2 * log($probability2, 2);
                    }
                }
           
                $result['entropy1'] = round($entropy1, 3);
                $result['entropy2'] = round($entropy2, 3);
            } else {
                $result['entropy1'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
                $result['entropy2'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
            }
    
            return $result;
        }),
    ];

    $gain_tahun_lulus1 =  $result5['entropy1']; // Ambil entropy awal dari $result1
    $gain_tahun_lulus2 =  $result5['entropy2'];

    foreach ($result8['query8'] as $item ) {
        $totalMahasiswa1 = $result5['query5']->sum('jumlah_mahasiswa1');
        $totalMahasiswa2 = $result5['query5']->sum('jumlah_mahasiswa2');

        if ($totalMahasiswa1 > 0 && $totalMahasiswa2 > 0) {
            // Menghitung probabilitas gabungan
            $probability1 = $item['jumlah_mahasiswa1'] / $totalMahasiswa1;
            $probability2 = $item['jumlah_mahasiswa2'] / $totalMahasiswa2;
            // Mengurangi entropy untuk item saat ini dari gain_jabodetabek
            if (isset($item['entropy1']) && $probability1 > 0) {
                $gain_tahun_lulus1 -= $probability1 * $item['entropy1'];
            }
            if (isset($item['entropy2']) && $probability2 > 0) {
                $gain_tahun_lulus2 -= $probability2 * $item['entropy2'];
            }
        }
    }

    

    // Bulatkan gain_jabodetabek ke 3 angka di belakang koma
    $gain_tahun_lulus1 = round($gain_tahun_lulus1, 3);
    $gain_tahun_lulus2 = round($gain_tahun_lulus2, 3);

    // Tambahkan gain_jabodetabek ke dalam $result3
    $result8['gain_tahun_lulus1'] = $gain_tahun_lulus1;
    $result8['gain_tahun_lulus2'] = $gain_tahun_lulus2;
    // Outputkan hasil
    //dd($result3, $query3, $totalMahasiswa,$probability ); 

    $max_gain_asli = max($gain_ipk1, $gain_tahun_lulus1, $gain_jabodetabek1);
    $highest_gain_type_asli = '';
    $highest_gain_value_asli = 0;
    
    if ($max_gain_asli == $gain_ipk1) {
        $highest_gain_type_asli = 'IPK';
        $highest_gain_value_asli = $gain_ipk1;
        $result2['max_gain_asli'] = $highest_gain_value_asli;
    } elseif ($max_gain_asli == $gain_tahun_lulus1) {
        $highest_gain_type_asli = 'Tahun Lulus';
        $highest_gain_value_asli = $gain_tahun_lulus1;
        $result4['max_gain_asli'] = $highest_gain_value_asli;
    } elseif ($max_gain_asli == $gain_jabodetabek1) {
        $highest_gain_type_asli = 'Jabodetabek';
        $highest_gain_value_asli = $gain_jabodetabek1;
        $result3['max_gain_asli'] = $highest_gain_value_asli;
    }
    
    // Inisialisasi TP, FP, TN, FN
   // Inisialisasi TP, FP, TN, FN
   $TP = $FP = $FP1 = $TN = $FN = $FN1 =$r = 0;

   // Mengambil data dari result yang memiliki gain tertinggi
   $data = [];

   if ($highest_gain_type_asli == 'IPK') {
       $data = $result6['query6'];
   } elseif ($highest_gain_type_asli == 'Tahun Lulus') {
       $data = $result8 ['query8'];
   } elseif ($highest_gain_type_asli == 'Jabodetabek') {
       $data = $result7['query7'];
   }
   

   //Iterasi melalui data untuk menghitung TP, FP, TN, FN
   foreach ($data as $item) {
       
       $TP += $item['Tepat_Lulus'];
       $TN +=  $item['Tidak_Tepat_Lulus']+$item['Belum_Lulus']; // FP dihitung sebagai jumlah dari 'Tidak_Tepat_Lulus' dan 'Belum_Lulus'
       $FP1 += ($item['Tidak_Tepat_Lulus']+$item['Belum_Lulus'])-($item['Tidak_Tepat_Lulus_prediksi']); // TN dihitung sebagai nol karena 'Belum_Lulus' termasuk dalam FP
       $FN1 += ($item['Tepat_Lulus']- $item['Tepat_Lulus_prediksi']); // FN dihitung sebagai nol karena tidak ada 'Tepat_Lulus' yang tidak teridentifikasi
       $r +=$item['Tepat_Lulus_prediksi'];
       $FP = abs($FP1);
       $FN = abs($FN1);

   }
  
   if (($TP + $FP) > 0) {
       $precision_prediksi = round(($TP / ($TP + $FP)) * 100, 2);
   } else {
       $precision_prediksi = 0; // Handle division by zero case
   }
   
   // Hitung Recall
   if (($TP + $FN) > 0) {
       $recall_prediksi = round(($TP / ($TP + $FN)) * 100, 2);
   } else {
       $recall_prediksi = 0; // Handle division by zero case
   }
   
   // Hitung Accuracy
   $total_instances = $TP + $FP + $TN + $FN;
   if ($total_instances > 0) {
       $accuracy_prediksi = round((($TP + $TN) / $total_instances) * 100, 2);
   } else {
       $accuracy_prediksi = 0; // Handle division by zero case
   }
   
   // Hitung F1 score
   if (($precision_prediksi + $recall_prediksi) > 0) {
       $f1_score_prediksi = round(2 * (($precision_prediksi * $recall_prediksi) / ($precision_prediksi + $recall_prediksi)), 2);
   } else {
       $f1_score_prediksi = 0; // Handle division by zero case
   }
   
   // Output hasil
  
    // dd($TP , $r, $TN , $FN);
    // dd($precision_prediksi,$accuracy_prediksi,$recall_prediksi,$f1_score_prediksi);
    
    
    // Mengambil tahun angkatan yang tersedia
    $years = DB::table('mahasiswa as m')
        ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
        ->select(DB::raw('YEAR(m.tahun_angkatan) as year'))
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
    $selectedPercentage = $request->input('selectedPercentage', 100); // Default 100% jika tidak ada input
    $percentageMultiplier = $selectedPercentage / 100;
    // Inisialisasi variabel untuk query
    $query1 = collect();
    $query2 = collect();
    $query3 = collect();
    $query4 = collect();

    // Lakukan query sesuai dengan input tahun dan jurusan
    if ($selectedYear && $selectedJurusan &&$percentageMultiplier) {
        // Query 1: Status Kelulusan
        $query1 = DB::table('reg_mahasiswa as r')
            ->join('mahasiswa as m', 'r.nim', '=', 'm.nim')
            ->join('jurusan as j', 'r.kd_jurusan', '=', 'j.kd_jurusan')
            ->select(
                DB::raw("CASE
                    WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 'Tidak Tepat Lulus'
                    WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 'Tepat Lulus'
                    ELSE 'Belum Lulus'
                END AS status_lulus"),
                DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) > 4 THEN 1 ELSE 0 END) AS Tidak_Tepat_Lulus"),
                DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) BETWEEN 3.5 AND 4 THEN 1 ELSE 0 END) AS Tepat_Lulus"),
                DB::raw("SUM(CASE WHEN (YEAR(r.tanggal_lulus) - YEAR(m.tahun_angkatan)) <= 3.5 THEN 1 ELSE 0 END) AS Belum_Lulus"),
                DB::raw("COUNT(*) AS jumlah_mahasiswa")
            )
            ->where('m.tahun_angkatan', '=', $selectedYear)
            ->where('j.jurusan', '=', $selectedJurusan)
            ->groupBy('status_lulus')
            ->orderBy('status_lulus', 'desc')
            ->get();

        // Query 2: Status IPK
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
            ->where('m.tahun_angkatan', '=', $selectedYear)
            ->where('j.jurusan', '=', $selectedJurusan)
            ->groupBy('status_ipk')
            ->orderBy('status_ipk', 'desc')
            ->get();
            //dd($query2);
        

        // Query 3: Status Jabodetabek
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

        // Query 4: Statistik Tahun Lulus
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

           
    }

    
    // Kalikan hasil setiap query dengan selectedPercentage / 100
    
    $percentageMultiplier = $selectedPercentage / 100;
    $sisa = 1 - $percentageMultiplier ;
    // Formatkan hasil sesuai dengan permintaan dan bulatkan ke bilangan bulat
    
    $result1 = [
        'query1' => $query1->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $result = [
                'status_lulus' => $item->status_lulus ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];
    
            return $result;
        }),
        'entropy' => function ($result1) {
            // Mengambil total mahasiswa dari result1
            $totalMahasiswa1 = $result1['query1']->sum('jumlah_mahasiswa1');
    
            if ($totalMahasiswa1 == 0) {
                return 0;
            }
    
            // Menghitung probabilitas dari setiap kategori
            $p1 = $result1['query1']->sum('Tidak_Tepat_Lulus') / $totalMahasiswa1;
            $p2 = $result1['query1']->sum('Tepat_Lulus') / $totalMahasiswa1;
            $p3 = $result1['query1']->sum('Belum_Lulus') / $totalMahasiswa1;
    
            // Inisialisasi entropy
            $entropy = 0;
    
            // Hitung entropy dengan formula sum(-Pi * log2(Pi))
            $probabilities = [$p1, $p2, $p3];
    
            foreach ($probabilities as $probability) {
                if ($probability > 0) {
                    $entropy -= $probability * log($probability, 2);
                }
            }
    
            return round($entropy, 3);
        }
    ];
    
    // Hitung nilai entropy dengan memanggil fungsi entropy
    $result1['entropy'] = $result1['entropy']($result1);
    
    // Outputkan hasil
    // dd($result1);
    
    
    


    $result2 = [
        'query2' => $query2->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $result = [
                'status_ipk' => $item->status_ipk ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];
    
            if ($jumlahMahasiswa == 0) {
                $result['entropy'] = 0;
            } else {
                $p1 = $tidakTepatLulus / $jumlahMahasiswa;
                $p2 = $tepatLulus / $jumlahMahasiswa;
                $p3 = $belumLulus / $jumlahMahasiswa;
                $entropy = 0;
    
                foreach ([$p1, $p2, $p3] as $probability) {
                    if ($probability > 0) {
                        $entropy -= $probability * log($probability, 2);
                    }
                }
    
                $result['entropy'] = round($entropy, 3);
            }
    
            return $result;
        }),
    ];
    
    $gain_ipk = $result1['entropy'] ?? 0;
    
    foreach ($result2['query2'] as $item) {
        $totalMahasiswa = $result1['query1']->sum('jumlah_mahasiswa1');
    
        if ($totalMahasiswa > 0) {
            $probability = $item['jumlah_mahasiswa1'] / $totalMahasiswa;
            if (isset($item['entropy']) && $probability > 0) {
                $gain_ipk -= $probability * $item['entropy'];
            }
        }
    }
    
    $gain_ipk = round($gain_ipk, 3);
    $result2['gain_ipk'] = $gain_ipk;
    
    // Menghitung entropy dan gain untuk Jabodetabek
    $result3 = [
        'query3' => $query3->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $result = [
                'jabodetabek' => $item->jabodetabek ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];
    
            if ($jumlahMahasiswa == 0) {
                $result['entropy'] = 0;
            } else {
                $p1 = $tidakTepatLulus / $jumlahMahasiswa;
                $p2 = $tepatLulus / $jumlahMahasiswa;
                $p3 = $belumLulus / $jumlahMahasiswa;
                $entropy = 0;
    
                foreach ([$p1, $p2, $p3] as $probability) {
                    if ($probability > 0) {
                        $entropy -= $probability * log($probability, 2);
                    }
                }
    
                $result['entropy'] = round($entropy, 3);
            }
    
            return $result;
        }),
    ];
    
    $gain_jabodetabek = $result1['entropy'] ?? 0;
    
    foreach ($result3['query3'] as $item) {
        $totalMahasiswa = $result1['query1']->sum('jumlah_mahasiswa1');
    
        if ($totalMahasiswa > 0) {
            $probability = $item['jumlah_mahasiswa1'] / $totalMahasiswa;
            if (isset($item['entropy']) && $probability > 0) {
                $gain_jabodetabek -= $probability * $item['entropy'];
            }
        }
    }
    
    $gain_jabodetabek = round($gain_jabodetabek, 3);
    $result3['gain_jabodetabek'] = $gain_jabodetabek;
    
    // Menghitung entropy dan gain untuk Tahun Lulus
    $result4 = [
        'query4' => $query4->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round($item->Tidak_Tepat_Lulus * $percentageMultiplier) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round($item->Tepat_Lulus * $percentageMultiplier) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round($item->Belum_Lulus * $percentageMultiplier) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $result = [
                'tahun_lulus' => $item->tahun_lulus ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];
    
            if ($jumlahMahasiswa == 0) {
                $result['entropy'] = 0;
            } else {
                $p1 = $tidakTepatLulus / $jumlahMahasiswa;
                $p2 = $tepatLulus / $jumlahMahasiswa;
                $p3 = $belumLulus / $jumlahMahasiswa;
                $entropy = 0;
    
                foreach ([$p1, $p2, $p3] as $probability) {
                    if ($probability > 0) {
                        $entropy -= $probability * log($probability, 2);
                    }
                }
    
                $result['entropy'] = round($entropy, 3);
            }
    
            return $result;
        }),
    ];
    
    $gain_tahun_lulus = $result1['entropy'] ?? 0;
    
    foreach ($result4['query4'] as $item) {
        $totalMahasiswa = $result1['query1']->sum('jumlah_mahasiswa1');
    
        if ($totalMahasiswa > 0) {
            $probability = $item['jumlah_mahasiswa1'] / $totalMahasiswa;
            if (isset($item['entropy']) && $probability > 0) {
                $gain_tahun_lulus -= $probability * $item['entropy'];
            }
        }
    }
    
    $gain_tahun_lulus = round($gain_tahun_lulus, 3);
    $result4['gain_tahun_lulus'] = $gain_tahun_lulus;
    
    // Menentukan gain tertinggi
    $max_gain_latih = max($gain_ipk, $gain_tahun_lulus, $gain_jabodetabek);
    $highest_gain_type_latih = '';
    $highest_gain_value_latih = 0;
    
    if ($max_gain_latih == $gain_ipk) {
        $highest_gain_type_latih = 'IPK';
        $highest_gain_value_latih = $gain_ipk;
        $result2['max_gain_latih'] = $highest_gain_value_latih;
    } elseif ($max_gain_latih == $gain_tahun_lulus) {
        $highest_gain_type_latih = 'Tahun Lulus';
        $highest_gain_value_latih = $gain_tahun_lulus;
        $result4['max_gain_latih'] = $highest_gain_value_latih;
    } elseif ($max_gain_latih == $gain_jabodetabek) {
        $highest_gain_type_latih = 'Jabodetabek';
        $highest_gain_value_latih = $gain_jabodetabek;
        $result3['max_gain_latih'] = $highest_gain_value_latih;
    }

// Tampilkan hasil dengan benar

    
    // Inisialisasi TP, FP, TN, FN
   // Inisialisasi TP, FP, TN, FN
   $TP = $FP = $TN = $FN = 0;

   // Mengambil data dari result yang memiliki gain tertinggi
   $data = [];

   if ($highest_gain_type_latih == 'IPK') {
       $data = $result2['query2'];
   } elseif ($highest_gain_type_latih == 'Tahun Lulus') {
       $data = $result4['query4'];
   } elseif ($highest_gain_type_latih == 'Jabodetabek') {
       $data = $result3['query3'];
   }
   //dd($highest_gain_type_latih, $highest_gain_value_latih);

   //Iterasi melalui data untuk menghitung TP, FP, TN, FN
   foreach ($data as $item) {
       $TP += $item['Tepat_Lulus'];
       $TN += $item['Tidak_Tepat_Lulus'] + $item['Belum_Lulus']; // FP dihitung sebagai jumlah dari 'Tidak_Tepat_Lulus' dan 'Belum_Lulus'
       $FP += 0; // TN dihitung sebagai nol karena 'Belum_Lulus' termasuk dalam FP
       $FN += 0; // FN dihitung sebagai nol karena tidak ada 'Tepat_Lulus' yang tidak teridentifikasi
   }
   //dd($TN);

   // Perhitungan Precision, Accuracy, Recall, dan F1 Score
   // $accuracy = (($TP + $TN) / ($TP + $FP + $TN + $FN)* 100);
   // $precision = (($TP) / ($TP + $FP)* 100);
   // $recall = (($TP) / ($TP + $FN)* 100);
   // $f1_score = (2 * (($precision * $recall) / ($precision + $recall)));

   if (($TP + $FP) > 0) {
       $precision_latih = ($TP / ($TP + $FP)) * 100;
   } else {
       $precision_latih = 0; // Handle division by zero case
   }
   //dd($precision_latih);
   if (($TP + $FN) > 0) {
       $recall_latih = ($TP / ($TP + $FN)) * 100;
   } else {
       $recall_latih = 0; // Handle division by zero case
   }
   
   // Calculate accuracy
   $total_instances = $TP + $FP + $TN + $FN;
   if ($total_instances > 0) {
       $accuracy_latih = (($TP + $TN) / $total_instances) * 100;
   } else {
       $accuracy_latih = 0; // Handle division by zero case
   }
   
   // Calculate F1 score
   if (($precision_latih + $recall_latih) > 0) {
       $f1_score_latih = (2 * (($precision_latih * $recall_latih) / ($precision_latih + $recall_latih)));
   } else {
       $f1_score_latih = 0; // Handle division by zero case
   }
    
    //dd($precision_latih,$accuracy_latih,$recall_latih,$f1_score_latih);

  
    $resultsisa1 = [
        'query1' => $query1->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round(abs(($item->Tidak_Tepat_Lulus * $percentageMultiplier) - $item->Tidak_Tepat_Lulus)) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round(abs(($item->Tepat_Lulus * $percentageMultiplier) - $item->Tepat_Lulus)) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round(abs(($item->Belum_Lulus * $percentageMultiplier) - $item->Belum_Lulus)) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;

            $result = [
                'status_lulus' => $item->status_lulus ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];

            return $result;
        }),
        'entropy' => function ($resultsisa1) {
            // Mengambil total mahasiswa dari result1
            $totalMahasiswa1 = $resultsisa1['query1']->sum('jumlah_mahasiswa1');

            if ($totalMahasiswa1 == 0) {
                return 0;
            }

            // Menghitung probabilitas dari setiap kategori
            $p1 = $resultsisa1['query1']->sum('Tidak_Tepat_Lulus') / $totalMahasiswa1;
            $p2 = $resultsisa1['query1']->sum('Tepat_Lulus') / $totalMahasiswa1;
            $p3 = $resultsisa1['query1']->sum('Belum_Lulus') / $totalMahasiswa1;

            // Inisialisasi entropy h
            $entropy = 0;

            // Hitung entropy dengan formula sum(-Pi * log2(Pi))
            $probabilities = [$p1, $p2, $p3];

            foreach ($probabilities as $probability) {
                if ($probability > 0) {
                    $entropy -= $probability * log($probability, 2);
                }
            }

            return round($entropy, 3);
        }
    ];

    // Hitung nilai entropy dengan memanggil fungsi entropy
    $resultsisa1['entropy'] = $resultsisa1['entropy']($resultsisa1);

    // Outputkan hasil
    //dd($resultsisa1);


    $resultsisa2 = [
        'query2' => $query2->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round(abs(($item->Tidak_Tepat_Lulus * $percentageMultiplier) - $item->Tidak_Tepat_Lulus)) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round(abs(($item->Tepat_Lulus * $percentageMultiplier) - $item->Tepat_Lulus)) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round(abs(($item->Belum_Lulus * $percentageMultiplier) - $item->Belum_Lulus)) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $result = [
                'status_ipk' => $item->status_ipk ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];
    
            // Hitung nilai entropy untuk setiap item
            if ($jumlahMahasiswa == 0) {
                $result['entropy'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
            } else {
                // Menghitung probabilitas dari setiap kategori
                $p1 = $result['Tidak_Tepat_Lulus'] / $jumlahMahasiswa;
                $p2 = $result['Tepat_Lulus'] / $jumlahMahasiswa;
                $p3 = $result['Belum_Lulus'] / $jumlahMahasiswa;
    
                // Inisialisasi entropy
                $entropy = 0;
    
                // Hitung entropy dengan formula sum(-Pi * log2(Pi)), pastikan Pi > 0
                $probabilities = [$p1, $p2, $p3];
                foreach ($probabilities as $probability) {
                    if ($probability > 0) {
                        $entropy -= $probability * log($probability, 2);
                    }
                }
    
                $result['entropy'] = round($entropy, 3); // Bulatkan entropy ke 3 angka di belakang koma
            }
    
            return $result;
        }),
    ];
    
    $gain_ipk_sisa = $resultsisa1['entropy'] ?? 0; // Ambil entropy awal dari $result1
    
    foreach ($resultsisa2['query2'] as $item) {
        $totalMahasiswa = $resultsisa1['query1']->sum('jumlah_mahasiswa1');   // Ambil total mahasiswa dari $result1
    
        if ($totalMahasiswa > 0) {
            // Menghitung probabilitas gabungan
            $probability = $item['jumlah_mahasiswa1'] / $totalMahasiswa;
    
            // Mengurangi entropy untuk item saat ini dari gain_ipk_sisa
            if (isset($item['entropy']) && $probability > 0) {
                $gain_ipk_sisa -= $probability * $item['entropy'];
            }
        }
    }
    
    // Bulatkan gain_ipk_sisa ke 3 angka di belakang koma
    $gain_ipk_sisa = round($gain_ipk_sisa, 3);
    
    // Tambahkan gain_ipk_sisa ke dalam $result2
    $resultsisa2['gain_ipk_sisa'] = $gain_ipk_sisa;
    
    // Outputkan hasil
    //dd($resultsisa2,$result2,$query2);

    $resultsisa3 = [
        'query3' => $query3->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round(abs(($item->Tidak_Tepat_Lulus * $percentageMultiplier) - $item->Tidak_Tepat_Lulus)) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round(abs(($item->Tepat_Lulus * $percentageMultiplier) - $item->Tepat_Lulus)) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round(abs(($item->Belum_Lulus * $percentageMultiplier) - $item->Belum_Lulus)) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
        
            $result = [
                'jabodetabek' => $item->jabodetabek ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa
            ];

            // Menghitung nilai entropy untuk setiap item
            if ($jumlahMahasiswa == 0) {
                $result['entropy'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
            } else {
                // Menghitung probabilitas dari setiap kategori
                $p1 = $result['Tidak_Tepat_Lulus'] / $jumlahMahasiswa;
                $p2 = $result['Tepat_Lulus'] / $jumlahMahasiswa;
                $p3 = $result['Belum_Lulus'] / $jumlahMahasiswa;

                // Inisialisasi entropy
                $entropy = 0;

                // Hitung entropy dengan formula sum(-Pi * log2(Pi)), pastikan Pi > 0
                $probabilities = [$p1, $p2, $p3];
                foreach ($probabilities as $probability) {
                    if ($probability > 0) {
                        $entropy -= $probability * log($probability, 2);
                    }
                }

                $result['entropy'] = round($entropy, 3); // Bulatkan entropy ke 3 angka di belakang koma
            }

            return $result;
        }),
    ];

    $gain_jabodetabek_sisa = $resultsisa1['entropy'] ?? 0; // Ambil entropy awal dari $result1

    foreach ($resultsisa3['query3'] as $item) {
        $totalMahasiswa = $resultsisa1['query1']->sum('jumlah_mahasiswa1'); // Ambil total mahasiswa dari $result1

        if ($totalMahasiswa > 0) {
            // Menghitung probabilitas gabungan
            $probability = $item['jumlah_mahasiswa1'] / $totalMahasiswa;

            // Mengurangi entropy untuk item saat ini dari gain_jabodetabek
            if (isset($item['entropy']) && $probability > 0) {
                $gain_jabodetabek_sisa -= $probability * $item['entropy'];
            }
        }
    }

    // Bulatkan gain_jabodetabek_sisa ke 3 angka di belakang koma
    $gain_jabodetabek_sisa = round($gain_jabodetabek_sisa, 3);

    // Tambahkan gain_jabodetabek_sisa ke dalam $result3
    $resultsisa3['gain_jabodetabek_sisa'] = $gain_jabodetabek_sisa;

    // Outputkan hasil
    //dd($resultsisa3, $query3); 

    $resultsisa4 = [
        'query4' => $query4->map(function ($item) use ($percentageMultiplier) {
            $tidakTepatLulus = isset($item->Tidak_Tepat_Lulus) ? round(abs(($item->Tidak_Tepat_Lulus * $percentageMultiplier) - $item->Tidak_Tepat_Lulus)) : 0;
            $tepatLulus = isset($item->Tepat_Lulus) ? round(abs(($item->Tepat_Lulus * $percentageMultiplier) - $item->Tepat_Lulus)) : 0;
            $belumLulus = isset($item->Belum_Lulus) ? round(abs(($item->Belum_Lulus * $percentageMultiplier) - $item->Belum_Lulus)) : 0;
            $jumlahMahasiswa = $tidakTepatLulus + $tepatLulus + $belumLulus;
    
            $result = [
                'tahun_lulus' => $item->tahun_lulus ?? 'Tidak ada data',
                'Tidak_Tepat_Lulus' => $tidakTepatLulus,
                'Tepat_Lulus' => $tepatLulus,
                'Belum_Lulus' => $belumLulus,
                'jumlah_mahasiswa1' => $jumlahMahasiswa // Menggunakan 'jumlah_mahasiswa1' sesuai permintaan
            ];
    
            // Hitung nilai entropy untuk setiap item
            if ($jumlahMahasiswa == 0) {
                $result['entropy'] = 0; // Jika tidak ada data atau jumlah mahasiswa nol, entropy dianggap nol
            } else {
                // Menghitung probabilitas dari setiap kategori
                $p1 = $result['Tidak_Tepat_Lulus'] / $jumlahMahasiswa;
                $p2 = $result['Tepat_Lulus'] / $jumlahMahasiswa;
                $p3 = $result['Belum_Lulus'] / $jumlahMahasiswa;
    
                // Inisialisasi entropy
                $entropy = 0;
    
                // Hitung entropy dengan formula sum(-Pi * log2(Pi)), pastikan Pi > 0
                $probabilities = [$p1, $p2, $p3];
                foreach ($probabilities as $probability) {
                    if ($probability > 0) {
                        $entropy -= $probability * log($probability, 2);
                    }
                }
    
                $result['entropy'] = round($entropy, 3); // Bulatkan entropy ke 3 angka di belakang koma
            }
    
            return $result;
        }),
    ];
    
    $gain_tahun_lulus_sisa = $resultsisa1['entropy'] ?? 0; // Ambil entropy awal dari $result1
    
    foreach ($resultsisa4['query4'] as $item) {
        $totalMahasiswa = $resultsisa1['query1']->sum('jumlah_mahasiswa1'); // Ambil total mahasiswa dari $result1
    
        if ($totalMahasiswa > 0) {
            // Menghitung probabilitas gabungan
            $probability = $item['jumlah_mahasiswa1'] / $totalMahasiswa;
    
            // Mengurangi entropy untuk item saat ini dari gain_tahun_lulus
            if (isset($item['entropy']) && $probability > 0) {
                $gain_tahun_lulus_sisa -= $probability * $item['entropy'];
            }
        }
    }
    
    // Bulatkan gain_tahun_lulus ke 3 angka di belakang koma
    $gain_tahun_lulus_sisa = round($gain_tahun_lulus_sisa, 3);
    
    // Tambahkan gain_tahun_lulus ke dalam $result4
    $resultsisa4['gain_tahun_lulus_sisa'] = $gain_tahun_lulus_sisa;
    
    // Outputkan hasil
     //dd($resultsisa4);

     $max_gain = max($gain_ipk_sisa, $gain_tahun_lulus_sisa, $gain_jabodetabek_sisa);
     $highest_gain_type = '';
     $highest_gain_value = 0;
     
     if ($max_gain == $gain_ipk_sisa) {
         $highest_gain_type = 'IPK';
         $highest_gain_value = $gain_ipk_sisa;
         $result2['max_gain'] = $highest_gain_value;
     } elseif ($max_gain == $gain_tahun_lulus_sisa) {
         $highest_gain_type = 'Tahun Lulus';
         $highest_gain_value = $gain_tahun_lulus_sisa;
         $result4['max_gain'] = $highest_gain_value;
     } elseif ($max_gain == $gain_jabodetabek_sisa) {
         $highest_gain_type = 'Jabodetabek';
         $highest_gain_value = $gain_jabodetabek_sisa;
         $result3['max_gain'] = $highest_gain_value;
     }
     
     // Inisialisasi TP, FP, TN, FN
    // Inisialisasi TP, FP, TN, FN
    $TP = $FP = $TN = $FN = 0;

    // Mengambil data dari result yang memiliki gain tertinggi
    $data = [];

    if ($highest_gain_type == 'IPK') {
        $data = $resultsisa2['query2'];
    } elseif ($highest_gain_type == 'Tahun Lulus') {
        $data = $resultsisa4['query4'];
    } elseif ($highest_gain_type == 'Jabodetabek') {
        $data = $resultsisa3['query3'];
    }
    

    //Iterasi melalui data untuk menghitung TP, FP, TN, FN
    foreach ($data as $item) {
        $TP += $item['Tepat_Lulus'];
        $TN += $item['Tidak_Tepat_Lulus'] + $item['Belum_Lulus']; // FP dihitung sebagai jumlah dari 'Tidak_Tepat_Lulus' dan 'Belum_Lulus'
        $FP += 0; // TN dihitung sebagai nol karena 'Belum_Lulus' termasuk dalam FP
        $FN += 0; // FN dihitung sebagai nol karena tidak ada 'Tepat_Lulus' yang tidak teridentifikasi
    }

    // Perhitungan Precision, Accuracy, Recall, dan F1 Score
    // $accuracy = (($TP + $TN) / ($TP + $FP + $TN + $FN)* 100);
    // $precision = (($TP) / ($TP + $FP)* 100);
    // $recall = (($TP) / ($TP + $FN)* 100);
    // $f1_score = (2 * (($precision * $recall) / ($precision + $recall)));

    if (($TP + $FP) > 0) {
        $precision = ($TP / ($TP + $FP)) * 100;
    } else {
        $precision = 0; // Handle division by zero case
    }
    
    if (($TP + $FN) > 0) {
        $recall = ($TP / ($TP + $FN)) * 100;
    } else {
        $recall = 0; // Handle division by zero case
    }
    
    // Calculate accuracy
    $total_instances = $TP + $FP + $TN + $FN;
    if ($total_instances > 0) {
        $accuracy = (($TP + $TN) / $total_instances) * 100;
    } else {
        $accuracy = 0; // Handle division by zero case
    }
    
    // Calculate F1 score
    if (($precision + $recall) > 0) {
        $f1_score = (2 * (($precision * $recall) / ($precision + $recall)));
    } else {
        $f1_score = 0; // Handle division by zero case
    }
     
     //dd($precision,$accuracy,$recall,$f1_score);

     

    
    
    

   
    
   

    // Mengembalikan hasil dalam bentuk view dengan menggunakan compact
    return view('prediksi.prediksi', compact(
        'years', 'allJurusan', 'selectedYear', 'selectedJurusan', 'selectedPercentage', 
        'result1', 'result2', 'result3', 'result4' , 'result5', 'result6', 'result7', 'result8' ,
        'resultsisa1', 'resultsisa2', 'resultsisa3', 'resultsisa4', 'highest_gain_type',
        'highest_gain_value','precision','accuracy','recall','f1_score',
        'precision_latih','accuracy_latih', 'recall_latih','f1_score_latih', 'highest_gain_type_latih',
        'highest_gain_value_latih','precision_prediksi','accuracy_prediksi', 'recall_prediksi','f1_score_prediksi',
        'highest_gain_type_asli','highest_gain_value_asli',
        //'result'
    ));
}

    
   

}


