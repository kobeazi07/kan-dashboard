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
use App\Models\Kawasan;
use App\Models\Bangunan;
use App\Models\FotoBangunan;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\WebpEncoder;



class PetaController extends Controller
{
    public function index()
    {
        $kawasans = Kawasan::with([
            'bangunan.fotoBangunan'
        ])
            ->orderBy('id')
            ->get();

        return view(
            'pages.peta.index',
            compact('kawasans')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LIHAT PETA KAWASAN
    |--------------------------------------------------------------------------
    */

    public function show(Kawasan $kawasan)
    {
        $kawasan->load([
            'bangunan.fotoBangunan',
        ]);

        return view(
            'peta.show',
            compact('kawasan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL BANGUNAN
    |--------------------------------------------------------------------------
    */

    public function detail(Bangunan $bangunan)
    {
        $bangunan->load([
            'kawasan',
            'fotoBangunan',
        ]);

        return response()->json($bangunan);
    }


    public function halfoto_lapharian($id)
    {
        $bangunan = Bangunan::findorFail($id);
        $foto = FotoBangunan::where('bangunan_id', $id)->get();
        return view('pages.bangunan.foto', compact('bangunan', 'foto'));
    }

    // upload foto
    public function upload_foto(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bangunan_id' => 'required|exists:bangunans,id',
            'foto'          => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $thumbnail = $request->file('foto');
        $thumbnailPath = null;

        try {
            // Ambil nama asli foto
            $originalName = pathinfo(
                $thumbnail->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            // Bersihkan nama file
            $originalName = preg_replace(
                '/[^A-Za-z0-9_-]/',
                '-',
                $originalName
            );

            // Buat nama file unik
            $thumbnailName = uniqid() . '_foto_' . $originalName . '.webp';

            // Folder penyimpanan foto FotoBangunan
            $destination = public_path('inputan/bangunan/detailimg/');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // Inisialisasi Intervention Image
            $manager = new ImageManager(new Driver());

            $image = $manager->decode($thumbnail->getPathname());

            // Resize maksimal 1920 x 1920 px
            $image->scaleDown(
                width: 1920,
                height: 1920
            );

            // Konversi ke WebP kualitas 80
            $encoded = $image->encode(
                new WebpEncoder(quality: 80)
            );

            // Simpan gambar
            $encoded->save($destination . $thumbnailName);

            // Path relatif yang disimpan ke database
            $thumbnailPath = 'inputan/bangunan/detailimg/' . $thumbnailName;

            // Simpan data FotoBangunan
            $FotoBangunan = FotoBangunan::create([
                'bangunan_id' => $request->bangunan_id,
                'foto'          =>  $thumbnailName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto FotoBangunan berhasil diunggah.',
                'data' => [
                    'id'            => $FotoBangunan->id,
                    'bangunan_id' => $FotoBangunan->bangunan_id,
                    'foto'          => $FotoBangunan->foto,
                    'url'           => asset($FotoBangunan->foto),
                ],
            ], 201);
        } catch (\Throwable $e) {
            // Hapus file jika proses penyimpanan gagal
            if ($thumbnailPath && file_exists(public_path($thumbnailPath))) {
                unlink(public_path($thumbnailPath));
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses atau menyimpan foto FotoBangunan.',
            ], 500);
        }
    }

    public function hapus_foto(FotoBangunan $FotoBangunan)
    {
        DB::beginTransaction();

        try {
            // hapus data
            $FotoBangunan->delete();

            DB::commit();

            return response()->json([
                'status'  => 1,
                'message' => 'Data FotoBangunan berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
