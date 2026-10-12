<?php

namespace App\Console\Commands;

// use App\Events\DashboardProgressUpdated;
use App\Services\GoogleSheetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

// class CheckDashboardProgress extends Command
// {
//     protected $signature = 'app:check-dashboard-progress';

//     protected $description = 'Memeriksa perubahan progres dashboard dari Google Sheets';

//     public function handle(GoogleSheetService $googleSheet): int
//     {
//         $data = $googleSheet->getDashboardData();

//         $snapshot = md5(json_encode($data));

//         $previousSnapshot = Cache::get('dashboard_progress_snapshot');

//         if ($snapshot === $previousSnapshot) {
//             $this->info('Tidak ada perubahan data.');

//             return self::SUCCESS;
//         }

//         Cache::forever('dashboard_progress_snapshot', $snapshot);

//         if ($previousSnapshot === null) {
//             $this->info('Snapshot awal berhasil disimpan.');

//             return self::SUCCESS;
//         }

//         event(new DashboardProgressUpdated($data));

//         $this->info('Perubahan terdeteksi. Event berhasil dikirim.');

//         return self::SUCCESS;
//     }
// }
class CheckDashboardProgress extends Command
{
    protected $signature = 'app:check-dashboard-progress';

    protected $description = 'Memeriksa perubahan progres dashboard dari Google Sheets';

    public function handle(GoogleSheetService $googleSheet): int
    {
        // 1. Ambil data terbaru dari Google Sheets
        $data = $googleSheet->getDashboardData();
        $pekerjaanFisik = $googleSheet->getDashboardDataPekerjaanFisik();

        // 2. Buat snapshot untuk kedua jenis data
        $snapshot = md5(json_encode([
            'data' => $data,
            'pekerjaanFisik' => $pekerjaanFisik,
        ]));

        $previousSnapshot = Cache::get(
            'dashboard_progress_snapshot'
        );

        // 3. Simpan kedua data ke cache
        Cache::forever('dashboard_progress_data', $data);

        Cache::forever(
            'dashboard_pekerjaan_fisik',
            $pekerjaanFisik
        );

        // 4. Simpan snapshot terbaru
        Cache::forever('dashboard_progress_snapshot', $snapshot);

        // 5. Tampilkan hasil sinkronisasi
        if ($previousSnapshot === $snapshot) {
            $this->info('Tidak ada perubahan data. Cache telah disinkronkan.');

            return self::SUCCESS;
        }

        $this->info('Data berubah. Cache dokumen dan pekerjaan fisik berhasil diperbarui.');

        return self::SUCCESS;
    }
}
