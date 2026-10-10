<?php

namespace App\Console\Commands;

use App\Events\DashboardProgressUpdated;
use App\Services\GoogleSheetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckDashboardProgress extends Command
{
    protected $signature = 'app:check-dashboard-progress';

    protected $description = 'Memeriksa perubahan progres dashboard dari Google Sheets';

    public function handle(GoogleSheetService $googleSheet): int
    {
        $data = $googleSheet->getDashboardData();

        $snapshot = md5(json_encode($data));

        $previousSnapshot = Cache::get('dashboard_progress_snapshot');

        if ($snapshot === $previousSnapshot) {
            $this->info('Tidak ada perubahan data.');

            return self::SUCCESS;
        }

        Cache::forever('dashboard_progress_snapshot', $snapshot);

        if ($previousSnapshot === null) {
            $this->info('Snapshot awal berhasil disimpan.');

            return self::SUCCESS;
        }

        event(new DashboardProgressUpdated($data));

        $this->info('Perubahan terdeteksi. Event berhasil dikirim.');

        return self::SUCCESS;
    }
}
