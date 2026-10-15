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
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use App\Models\User;
use App\Models\Maps;
use App\Models\Setting;

class DashboardController extends Controller
{
    // public function index(GoogleSheetService $googleSheet)
    // {
    //     // $start = microtime(true);
    //     // Ambil data progres dari cache
    //     $data = Cache::get('dashboard_progress_data');

    //     // Fallback jika cache belum tersedia
    //     if ($data === null) {
    //         $data = $googleSheet->getDashboardData();
    //     }


    //     // logger('Waktu getDashboardData: ' . round(microtime(true) - $start, 2) . ' detik');

    //     // $start = microtime(true);
    //     $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();
    //     // logger('Waktu getDashboardDataPekerjaanFisik: ' . round(microtime(true) - $start, 2) . ' detik');
    //     // return view('dashboard', compact('data'));
    //     return view('pages.dashboard', compact('data', 'pekerjaanFisik'));
    // }
    // public function index(GoogleSheetService $googleSheet)
    // {
    //     $start = microtime(true);

    //     $data = Cache::get('dashboard_progress_data');

    //     if ($data === null) {
    //         $data = $googleSheet->getDashboardData();
    //     }

    //     logger('Waktu data progres: ' . round(microtime(true) - $start, 2) . ' detik');

    //     $start = microtime(true);

    //     $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();

    //     logger('Waktu pekerjaan fisik: ' . round(microtime(true) - $start, 2) . ' detik');

    //     return view('pages.dashboard', compact('data', 'pekerjaanFisik'));
    // }
    // public function index(GoogleSheetService $googleSheet)
    // {
    //     // Ambil data progres dari cache
    //     // $data = Cache::get('dashboard_progress_data');
    //     $data = $googleSheet->getDashboardData();
    //     // Fallback jika cache belum tersedia
    //     if ($data === null) {
    //         $data = $googleSheet->getDashboardData();
    //     }

    //     // Ambil data pekerjaan fisik dari cache
    //     // $pekerjaanFisik = Cache::get('dashboard_pekerjaan_fisik');
    //     $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();
    //     // Fallback jika cache pekerjaan fisik belum tersedia
    //     if ($pekerjaanFisik === null) {
    //         $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();

    //         Cache::forever(
    //             'dashboard_pekerjaan_fisik',
    //             $pekerjaanFisik
    //         );
    //     }

    //     return view(
    //         'pages.dashboard',
    //         compact('data', 'pekerjaanFisik')
    //     );
    // }


    public function index(GoogleSheetService $googleSheet)
    {
        // dd([
        //     'cache_store' => config('cache.default'),
        //     'cache_path' => config('cache.stores.file.path'),
        //     'cache_data' => Cache::get('dashboard_progress_data'),
        // ]);
        // Ambil data dokumen dari cache
        $data = Cache::get('dashboard_progress_data');

        // Ambil langsung dari Google Sheets hanya jika cache kosong
        if ($data === null) {
            $data = $googleSheet->getDashboardData();

            Cache::forever('dashboard_progress_data', $data);
        }

        // Ambil data pekerjaan fisik dari cache
        $pekerjaanFisik = Cache::get('dashboard_pekerjaan_fisik');

        // Ambil dari Google Drive hanya jika cache kosong
        if ($pekerjaanFisik === null) {
            $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();

            Cache::forever(
                'dashboard_pekerjaan_fisik',
                $pekerjaanFisik
            );
        }

        // maps
        $maps = Maps::orderByDesc('id')->first();

        // Tampilkan dashboard menggunakan cache
        return view(
            'pages.dashboard',
            compact('data', 'pekerjaanFisik', 'maps')
        );
    }


    public function getProgressData()
    {
        return response()->json([
            'data' => Cache::get('dashboard_progress_data', []),
            'pekerjaanFisik' => Cache::get('dashboard_pekerjaan_fisik', []),
        ]);
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

    // maps
    public function admin_maps()
    {
        $maps = Maps::get();
        return view('pages.maps', compact('maps'));
    }
    public function tambah_maps(Request $request)
    {

        DB::beginTransaction();

        try {


            $maps = Maps::create([
                'date' => $request->date,
                'embed' => $request->embed,
            ]);

            DB::commit();
            return response()->json([
                'status' => 1,
                'message' => 'maps berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function edit_maps(Request $request, $id)
    {

        $maps = Maps::findOrFail($id);
        $data = [
            'date' => $request->date,
            'embed' => $request->embed,
        ];

        Maps::where('id', $id)->update($data);
        return response()->json([
            'status'  => 1,
            'message' => 'Data maps berhasil diupdate'
        ]);
    }
    public function maps_destroy(Maps $maps)
    {
        DB::beginTransaction();

        try {

            // hapus file gambar jika ada
            if ($maps->logo && file_exists(public_path($maps->logo))) {
                unlink(public_path($maps->logo));
            }

            // hapus data
            $maps->delete();

            DB::commit();

            return response()->json([
                'status'  => 1,
                'message' => 'Data maps berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // settings

    public function admin_setting()
    {
        $setting = Setting::first();
        return view('pages.setting', compact('setting'));
        // return view('pages.setting');
    }

    public function edit_setting(Request $request, $id)
    {
        $setting =   Setting::find($id);
        $data = [

            'link_jetty' => $request->link_jetty,
            'link_refinery' => $request->link_refinery,
            'link_power_plant' => $request->link_power_plant,
            'link_redmud' => $request->link_redmud,
            // Otomatis tambahkan class rounded-image

            'link_jembatan' => $request->link_jembatan,

        ];
        Setting::where('id', $id)->update($data);

        return response()->json([
            'status' => 1,
            'message' => 'Portfolio berhasil diupdate'
        ]);
    }
}
