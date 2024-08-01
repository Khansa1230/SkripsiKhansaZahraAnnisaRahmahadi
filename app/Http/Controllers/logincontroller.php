<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';
    public function index()
    {
        return view ('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'ni' => ['required', 'string', 'exists:users,ni'], // Pastikan ni ada di users
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'ni' => 'The provided credentials do not match our records.',
        ])->onlyInput('ni');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
