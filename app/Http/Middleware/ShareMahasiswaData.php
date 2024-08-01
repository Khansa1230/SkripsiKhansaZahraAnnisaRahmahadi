<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShareMahasiswaData
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $mahasiswa = null;

        if ($user) {
            $mahasiswa = DB::table('users')
                ->join('mahasiswa', 'users.ni', '=', 'mahasiswa.nim')
                ->where('users.ni', $user->ni)
                ->select('mahasiswa.nama')
                ->first();
                \Log::info('Mahasiswa: ' . json_encode($mahasiswa));
        }

        view()->share('mahasiswa', $mahasiswa);

        return $next($request);
    }
}
