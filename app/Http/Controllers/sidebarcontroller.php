<?php
namespace App\Http\Controllers;
use Closure;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SidebarController extends Controller
{
    public function index()
    {
        return view('include.sidebar');
    }
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
}
