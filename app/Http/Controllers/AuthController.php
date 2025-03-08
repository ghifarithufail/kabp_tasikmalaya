<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('layouts.login');
    }

   public function store(Request $request)
    {
        // Mengambil username dan password dari permintaan
        $credentials = $request->only('username', 'password');

        // Mencoba untuk mengautentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Mendapatkan pengguna yang sedang diautentikasi
            $user = Auth::user();

            // Memeriksa apakah status pengguna adalah 1
            if ($user->status === 1) {
                // Menggunakan session untuk mencegah serangan CSRF dan regenerasi session
                $request->session()->regenerate();
                return redirect()->intended('/');
            } else {
                // Jika status pengguna bukan 1, logout dan arahkan kembali ke login
                Auth::logout(); // Logout pengguna
                return redirect('/login')->with('statusError', 'Akun anda tidak aktif.');
            }
        }

        // Jika autentikasi gagal, arahkan kembali ke login dengan pesan kesalahan
        return redirect('/login')->with('loginError', 'Username atau Password salah.');
    }


    public function logout(){
        Auth::logout();
 
        request()->session()->invalidate();
     
        request()->session()->regenerateToken();
     
        return redirect('/login');
    }
}
