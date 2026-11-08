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
use App\Models\Bangunan;
use App\Models\Kawasan;
use App\Models\FotoBangunan;
use Illuminate\Support\Facades\Storage;


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
        // $hasil = app(\App\Services\GoogleSheetService::class)
        //     ->getDashboardDataTimeline();

        // dd([
        //     'jumlah_periode' => count($hasil['periode']),
        //     'periode_awal' => array_slice($hasil['periode'], 0, 4),
        //     'jumlah_data' => count($hasil['data']),
        //     'contoh_pekerjaan' => collect($hasil['data'])
        //         ->firstWhere('jenis', 'pekerjaan'),
        // ]);
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
        // Ambil data timeline dari cache.
        $timeline = Cache::get('dashboard_timeline');
        // dd($timeline);

        if ($timeline === null) {
            $timeline = $googleSheet->getDashboardDataTimeline();

            Cache::forever('dashboard_timeline', $timeline);
        }

        // maps
        $maps = Maps::orderByDesc('id')->first();

        // Tampilkan dashboard menggunakan cache
        return view(
            'pages.dashboard',
            compact('data', 'pekerjaanFisik', 'timeline', 'maps')
        );
    }


    public function getProgressData()
    {
        return response()->json([
            'data' => Cache::get('dashboard_progress_data', []),
            'pekerjaanFisik' => Cache::get('dashboard_pekerjaan_fisik', []),
            'timeline' => Cache::get('dashboard_timeline', [
                'periode' => [],
                'data' => [],
            ]),
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
    public function admin_kawasan()
    {
        $kawasan = Kawasan::get();

        return view('pages.kawasan', compact(
            'kawasan',
        ));
    }

    public function tambah_kawasan(Request $request)
    {

        DB::beginTransaction();

        try {

            // dd($request->all());    
            $kawasan = Kawasan::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'konsultan_pengawas_id' => $request->konsultan_pengawas_id,
                'owner_id' => $request->owner_id,
                'kontraktor_pelaksana_id' => $request->kontraktor_pelaksana_id,
            ]);

            DB::commit();
            return response()->json([
                'status' => 1,
                'message' => 'kawasan berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit_kawasan(Request $request, $id)
    {

        $partner = Kawasan::find($id);
        $data = [
            'nama' => $request->kode_kawasan,
            'nama_kawasan' => $request->nama_kawasan,
            'konsultan_pengawas_id' => $request->konsultan_pengawas_id,
            'owner_id' => $request->owner_id,
            'kontraktor_pelaksana_id' => $request->kontraktor_pelaksana_id,

        ];

        Kawasan::where('id', $id)->update($data);
        return response()->json([
            'status'  => 1,
            'message' => 'Data kawasan berhasil diupdate'
        ]);
    }
    public function kawasan_destroy(Kawasan $kawasan)
    {
        DB::beginTransaction();

        try {
            // hapus data
            $kawasan->delete();

            DB::commit();

            return response()->json([
                'status'  => 1,
                'message' => 'Data kawasan berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // controller admin
    // public function kawasanIndex()
    // {
    //     $kawasans = Kawasan::withCount('bangunan')
    //         ->latest()
    //         ->get();

    //     return view('pages.kawasan.index', compact('kawasans'));
    // }

    // public function kawasanCreate()
    // {
    //     return view('pages.kawasan.create');
    // }

    // public function kawasanStore(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'deskripsi' => 'nullable|string',
    //         'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
    //     ]);

    //     $gambar = $request->file('gambar')
    //         ->store('kawasan', 'public');

    //     Kawasan::create([
    //         'nama' => $request->nama,
    //         'deskripsi' => $request->deskripsi,
    //         'gambar' => $gambar,
    //     ]);

    //     return redirect()
    //         ->route('kawasan.index')
    //         ->with('success', 'Kawasan berhasil ditambahkan.');
    // }

    // public function kawasanEdit(Kawasan $kawasan)
    // {
    //     return view(
    //         'pages.kawasan.edit',
    //         compact('kawasan')
    //     );
    // }

    // public function kawasanUpdate(
    //     Request $request,
    //     Kawasan $kawasan
    // ) {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'deskripsi' => 'nullable|string',
    //         'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    //     ]);

    //     $data = [
    //         'nama' => $request->nama,
    //         'deskripsi' => $request->deskripsi,
    //     ];

    //     if ($request->hasFile('gambar')) {

    //         if (
    //             $kawasan->gambar &&
    //             Storage::disk('public')->exists($kawasan->gambar)
    //         ) {
    //             Storage::disk('public')
    //                 ->delete($kawasan->gambar);
    //         }

    //         $data['gambar'] = $request
    //             ->file('gambar')
    //             ->store('kawasan', 'public');
    //     }

    //     $kawasan->update($data);

    //     return redirect()
    //         ->route('kawasan.index')
    //         ->with('success', 'Kawasan berhasil diperbarui.');
    // }

    // public function kawasanDestroy(Kawasan $kawasan)
    // {
    //     if (
    //         $kawasan->gambar &&
    //         Storage::disk('public')->exists($kawasan->gambar)
    //     ) {
    //         Storage::disk('public')
    //             ->delete($kawasan->gambar);
    //     }

    //     $kawasan->delete();

    //     return redirect()
    //         ->route('kawasan.index')
    //         ->with('success', 'Kawasan berhasil dihapus.');
    // }


    /*
    |--------------------------------------------------------------------------
    | BANGUNAN
    |--------------------------------------------------------------------------
    */

    public function bangunanIndex(Kawasan $kawasan)
    {
        $bangunans = $kawasan
            ->bangunan()
            ->with('fotoBangunan')
            ->get();

        return view(
            'pages.bangunan.index',
            compact('kawasan', 'bangunans')
        );
    }

    public function bangunanCreate(Kawasan $kawasan)
    {
        return view(
            'pages.bangunan.create',
            compact('kawasan')
        );
    }

    public function bangunanStore(
        Request $request,
        Kawasan $kawasan
    ) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',

            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $bangunan = $kawasan->bangunan()->create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'posisi_x' => 50,
            'posisi_y' => 50,
        ]);

        if ($request->hasFile('foto')) {

            foreach ($request->file('foto') as $file) {

                $path = $file->store(
                    'bangunan',
                    'public'
                );

                $bangunan->fotoBangunan()->create([
                    'foto' => $path,
                ]);
            }
        }

        return redirect()
            ->route(
                'bangunan.index',
                $kawasan->id
            )
            ->with(
                'success',
                'Bangunan berhasil ditambahkan.'
            );
    }

    public function bangunanEdit(Bangunan $bangunan)
    {
        $bangunan->load([
            'kawasan',
            'fotoBangunan',
        ]);

        return view(
            'pages.bangunan.edit',
            compact('bangunan')
        );
    }

    public function bangunanUpdate(
        Request $request,
        Bangunan $bangunan
    ) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $bangunan->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route(
                'bangunan.index',
                $bangunan->kawasan_id
            )
            ->with(
                'success',
                'Bangunan berhasil diperbarui.'
            );
    }

    public function bangunanDestroy(Bangunan $bangunan)
    {
        foreach ($bangunan->fotoBangunan as $foto) {

            if (
                Storage::disk('public')
                ->exists($foto->foto)
            ) {
                Storage::disk('public')
                    ->delete($foto->foto);
            }
        }

        $kawasanId = $bangunan->kawasan_id;

        $bangunan->delete();

        return redirect()
            ->route('bangunan.index', $kawasanId)
            ->with(
                'success',
                'Bangunan berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE POSISI PIN
    |--------------------------------------------------------------------------
    */

    public function updatePosition(
        Request $request,
        Bangunan $bangunan
    ) {
        $request->validate([
            'posisi_x' => 'required|numeric|min:0|max:100',
            'posisi_y' => 'required|numeric|min:0|max:100',
        ]);

        $bangunan->update([
            'posisi_x' => $request->posisi_x,
            'posisi_y' => $request->posisi_y,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posisi pin berhasil diperbarui.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FOTO BANGUNAN
    |--------------------------------------------------------------------------
    */

    public function storeFoto(
        Request $request,
        Bangunan $bangunan
    ) {
        $request->validate([
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        foreach ($request->file('foto') as $file) {

            $path = $file->store(
                'bangunan',
                'public'
            );

            $bangunan->fotoBangunan()->create([
                'foto' => $path,
            ]);
        }

        return back()->with(
            'success',
            'Foto berhasil ditambahkan.'
        );
    }

    public function destroyFoto(
        FotoBangunan $fotoBangunan
    ) {
        if (
            Storage::disk('public')
            ->exists($fotoBangunan->foto)
        ) {
            Storage::disk('public')
                ->delete($fotoBangunan->foto);
        }

        $fotoBangunan->delete();

        return back()->with(
            'success',
            'Foto berhasil dihapus.'
        );
    }
}
