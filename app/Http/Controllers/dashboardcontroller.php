<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use App\Models\RegMahasiswa;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    // public function index()
    // {
    //     return view('dashboard.dashboard');
    // }
    public function index(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = null;

        if ($user && $user->ni) {
            $mahasiswa = DB::table('users')
                ->join('mahasiswa', 'users.ni', '=', 'mahasiswa.nim')
                ->where('users.ni', $user->ni)
                ->select('mahasiswa.nama')
                ->first();
                //dd($mahasiswa);
        }

        return view('dashboard.dashboard', ['mahasiswa' => $mahasiswa]);
    }
    
    public function tahunAngkatanTerbanyak(Request $request)
    {
    // Query untuk mendapatkan tahun angkatan dengan jumlah mahasiswa terbanyak
    $tahun_angkatan_terbanyak = DB::table('mahasiswa as m')
        ->join('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
        ->select('m.tahun_angkatan')
        ->groupBy('m.tahun_angkatan')
        ->orderBy(DB::raw('COUNT(m.nim)'), 'DESC')
        ->limit(1)
        ->value('m.tahun_angkatan');

    return response()->json([
        'tahun_angkatan_terbanyak' => $tahun_angkatan_terbanyak,
    ]);
    }

    public function tech(Request $request)
    {
        $jumlah_mahasiswa = number_format(RegMahasiswa::count());
        return view('dashboard.dashboard', [
            'jumlah_mahasiswa' => $jumlah_mahasiswa,
        ]);
    }

    public function jumlahMahasiswaAktif(Request $request)
{
    // Menghitung jumlah mahasiswa dengan status "Aktif"
    $jumlah_mahasiswa_aktif = RegMahasiswa::where('status', 'Aktif')->count();

    // Format angka tanpa tanda koma
    $formatted_jumlah = number_format($jumlah_mahasiswa_aktif, 0, '', '');

    return response()->json([
        'jumlah_mahasiswa_aktif' => $formatted_jumlah,
    ]);
}


    public function jumlahMahasiswaLulus(Request $request)
    {
        // Menghitung jumlah mahasiswa dengan status "Aktif"
        $jumlah_mahasiswa_lulus = RegMahasiswa::where('status', 'Lulus')->count();
        return response()->json([
            'jumlah_mahasiswa_lulus' => $jumlah_mahasiswa_lulus,
        ]);
    }

    // Pada fungsi jumlahperangkatan
    public function jumlahperangkatan(Request $request)
    {
        // Mendapatkan tahun-tahun angkatan yang ada
        $years = DB::table('mahasiswa')
        ->select(DB::raw('YEAR(tahun_angkatan) as year'))
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();

        // Mendapatkan nim-nim mahasiswa yang ada
        $nim= DB::table('reg_mahasiswa')
        ->select('nim')
        ->groupBy('nim')
        ->get();

        // Mendapatkan tahun dan nim dari permintaan
        $year = $request->input('year');
        $nim = $request->input('nim');

        // Menghitung jumlah mahasiswa berdasarkan tahun angkatan
        $bar = DB::table('mahasiswa as m')
        ->leftJoin('reg_mahasiswa as r', 'm.nim', '=', 'r.nim')
        ->select(DB::raw('YEAR(m.tahun_angkatan) AS tahun'), DB::raw('COUNT(r.nim) AS jumlah_mahasiswa'))
        // ->whereYear('m.tahun_angkatan', $year)
        ->groupBy('tahun')
        ->get();
        $query = $bar->mapWithKeys(function ($item) {
            return [$item->tahun => $item->jumlah_mahasiswa];
        });

        return ['years' => $years, 'query' => $query];
    }

    public function combined(Request $request)
    {
         // Panggil method index() untuk mendapatkan data mahasiswa
        $indexData = $this->index($request);
        $mahasiswa = isset($indexData['mahasiswa']) ? $indexData['mahasiswa'] : null;
        // Panggil method tech dan jumlahperangkatan
        $techData = $this->tech($request);
        $jumlah_mahasiswa = isset($techData['jumlah_mahasiswa']) ? $techData['jumlah_mahasiswa'] : null;
        
        // Panggil method tahunAngkatanTerbanyak
        $tahun_angkatan_terbanyak = $this->tahunAngkatanTerbanyak($request)->original['tahun_angkatan_terbanyak'];
    
        // Panggil method jumlahMahasiswaAktif untuk mendapatkan jumlah mahasiswa aktif
        $jumlah_mahasiswa_aktif = $this->jumlahMahasiswaAktif($request)->original['jumlah_mahasiswa_aktif'];
    
        // Panggil method jumlahMahasiswaLulus untuk mendapatkan jumlah mahasiswa lulus
        $jumlah_mahasiswa_lulus = $this->jumlahMahasiswaLulus($request)->original['jumlah_mahasiswa_lulus'];
    
        // Access the data returned by jumlahperangkatan differently
        $jumlahperangkatanData = $this->jumlahperangkatan($request);
        $query = isset($jumlahperangkatanData['query']) ? $jumlahperangkatanData['query'] : [];
    
        // Kemudian sertakan variabel $jumlah_mahasiswa dan $query dalam pemanggilan view
        return view('dashboard.dashboard', compact('mahasiswa','jumlah_mahasiswa_lulus', 'jumlah_mahasiswa_aktif', 'jumlah_mahasiswa', 'tahun_angkatan_terbanyak', 'query'));
    }
    



    
}
