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



class PetaController extends Controller
{
    public function index()
    {
        $kawasans = Kawasan::with([
            'bangunan'
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
}
