<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    
    public function index()
    {
        return view ('auth.register');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'ni' => 'required|string|max:255|exists:mahasiswa,nim|unique:users,ni',
            'password' => 'required|string|min:10|confirmed',
        ]);

        // Cek apakah pengguna sudah terdaftar
        $existingUser = User::where('ni', $request->ni)->first();
        if ($existingUser) {
            return back()->with('registerError', 'NIM sudah terdaftar')->withInput();
        }

        try {
            // Buat pengguna baru
            $user = new User();
            $user->ni = $request->ni;
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('login');
        } catch (\Exception $e) {
            return back()->with('registerError', 'Terjadi kesalahan, silakan coba lagi.')->withInput();
        }
    }
}
