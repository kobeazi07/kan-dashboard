<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(GoogleSheetService $googleSheet)
    {
        // $start = microtime(true);
        $data = $googleSheet->getDashboardData();

        // logger('Waktu getDashboardData: ' . round(microtime(true) - $start, 2) . ' detik');

        // $start = microtime(true);
        $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();
        // logger('Waktu getDashboardDataPekerjaanFisik: ' . round(microtime(true) - $start, 2) . ' detik');
        // return view('dashboard', compact('data'));
        return view('pages.dashboard', compact('data', 'pekerjaanFisik'));
    }

    public function halamanlogin()
    {

        return view('layouts.login');
    }
    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => route('HalamanDashboard') // sesuaikan route tujuan
            ]);
        }
        // Password salah
        return response()->json([
            'success' => false,
            'message' => 'Password salah! Silakan coba lagi.'
        ], 401);
    }
    public function user_logout(Request $request)
    {

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
