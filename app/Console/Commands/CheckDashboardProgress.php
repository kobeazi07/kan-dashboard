<?php

namespace App\Console\Commands;

use App\Events\DashboardProgressUpdated;
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

        // 2. Buat snapshot untuk mendeteksi perubahan
        $snapshot = md5(json_encode($data));

        $previousSnapshot = Cache::get(
            'dashboard_progress_snapshot'
        );

        // 3. Jika tidak ada perubahan, hentikan proses
        if ($snapshot === $previousSnapshot) {
            $this->info('Tidak ada perubahan data.');

            return self::SUCCESS;
        }

        // 4. Simpan isi data spreadsheet ke cache
        Cache::forever('dashboard_progress_data', $data);

        // 5. Simpan snapshot terbaru
        Cache::forever('dashboard_progress_snapshot', $snapshot);

        // 6. Snapshot pertama hanya menyimpan data
        if ($previousSnapshot === null) {
            $this->info('Data awal berhasil disimpan ke cache.');

            return self::SUCCESS;
        }

        // 7. Kirim event setelah cache diperbarui
        event(new DashboardProgressUpdated($data));

        $this->info('Data berubah. Cache diperbarui dan event dikirim.');

        return self::SUCCESS;
    }
}
