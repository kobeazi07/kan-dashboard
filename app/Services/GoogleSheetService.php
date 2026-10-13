<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\DB;

class GoogleSheetService
{
    public function readRange(
        string $spreadsheetId,
        string $range
    ): array {
        $client = new Client();

        $client->setApplicationName('KAN Dashboard');

        $client->setAuthConfig(
            storage_path('app/private/google-service-account.json')
        );

        $client->setScopes([
            Sheets::SPREADSHEETS_READONLY,
        ]);

        $service = new Sheets($client);

        $response = $service->spreadsheets_values->get(
            $spreadsheetId,
            $range,
            ['valueRenderOption' => 'UNFORMATTED_VALUE']
        );

        return $response->getValues() ?? [];
    }

    public function readDashboardLinks(
        string $spreadsheetId,
        string $range
    ): array {
        $client = new \Google\Client();

        $client->setApplicationName('KAN Dashboard');

        $client->setAuthConfig(
            storage_path('app/private/google-service-account.json')
        );

        $client->setScopes([
            \Google\Service\Sheets::SPREADSHEETS_READONLY,
        ]);

        $service = new \Google\Service\Sheets($client);

        $response = $service->spreadsheets->get($spreadsheetId, [
            'ranges' => [$range],
            'includeGridData' => true,
            'fields' => 'sheets(data(rowData(values(formattedValue,hyperlink,userEnteredValue,textFormatRuns))))',
        ]);

        $result = [];

        foreach ($response->getSheets() ?? [] as $sheet) {
            foreach ($sheet->getData() ?? [] as $grid) {
                foreach ($grid->getRowData() ?? [] as $rowIndex => $row) {
                    foreach ($row->getValues() ?? [] as $colIndex => $cell) {
                        $url = $cell->getHyperlink();

                        foreach ($cell->getTextFormatRuns() ?? [] as $run) {
                            $link = $run->getFormat()?->getLink();

                            if ($link?->getUri()) {
                                $url = $link->getUri();
                                break;
                            }
                        }

                        $entered = $cell->getUserEnteredValue();

                        $result[] = [
                            'row' => $rowIndex,
                            'column' => $colIndex,
                            'value' => $cell->getFormattedValue(),
                            'url' => $url,
                            'formula' => $entered?->getFormulaValue(),
                        ];
                    }
                }
            }
        }

        return $result;
    }


    public function getDashboardData(): array
    {
        $spreadsheetId = '1s7HqchxwI99SLmercIENi7DWRZZbjaM3Tbkvt_EDi9k';

        $cells = $this->readDashboardLinks(
            $spreadsheetId,
            'Dashboard!K12:R28'
        );

        // Susun hasil berdasarkan nomor baris dan kolom.
        $grid = [];

        foreach ($cells as $cell) {
            $grid[$cell['row']][$cell['column']] = $cell;
        }

        // Ambil nama, URL, dan progres dari baris tertentu.
        $getDocument = function (
            int $row,
            int $nameColumn,
            ?int $progressColumn = null
        ) use (&$grid): array {
            $nameCell = $grid[$row][$nameColumn] ?? [];
            $progressCell = $progressColumn !== null
                ? ($grid[$row][$progressColumn] ?? [])
                : [];

            $progressText = $progressCell['value'] ?? null;

            // Contoh: "63%" menjadi 63.
            $progress = is_string($progressText)
                && preg_match('/^\s*(-?\d+(?:[.,]\d+)?)\s*%\s*$/', $progressText, $matches)
                ? (float) str_replace(',', '.', $matches[1])
                : null;

            return [
                'name' => $nameCell['value'] ?? null,
                'url' => $nameCell['url'] ?? null,
                'progress' => $progress,
            ];
        };


        return [
            'Dermaga Jetty' => [
                $getDocument(3, 0, 2), // Baris 15
                $getDocument(4, 0, 2), // Baris 16
                $getDocument(5, 0, 2), // Baris 17
                // data dokumentasi
                // $getDocument(6, 0, 2), // Baris 18
                // akhir data dokumentasi

                // $getDocument(0, 0, 2), // Tambahan: KLM baris 12-14

                array_merge($getDocument(0, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Master Schedule',
                ]),

                array_merge($getDocument(6, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Data Dokumentasi',
                ]),
            ],

            'Refinery' => [
                $getDocument(3, 5, 7), // Baris 15
                $getDocument(4, 5, 7), // Baris 16
                $getDocument(5, 5, 7), // Baris 17
                // data dokumentasi
                // $getDocument(6, 5, 7), // Baris 18
                // akhir data dokumentasi

                $getDocument(7, 5, 7), // Baris 19
                // $getDocument(0, 5, 7), // Tambahan: PQR baris 12-14

                array_merge($getDocument(0, 5, 7), [
                    'type' => 'additional',
                    'label' => 'Master Schedule',
                ]),

                array_merge($getDocument(6, 5, 7), [
                    'type' => 'additional',
                    'label' => 'Data Dokumentasi',
                ]),
            ],

            'Power Plant' => [
                $getDocument(11, 0, 2), // Baris 23
                $getDocument(12, 0, 2), // Baris 24
                $getDocument(13, 0, 2), // Baris 25
                // data dokumentasi
                // $getDocument(14, 0, 2), // Baris 26
                // akhir data dokumentasi

                $getDocument(15, 0, 2), // Baris 27
                // $getDocument(8, 0, 2),  // Tambahan: KLM baris 20-22

                array_merge($getDocument(8, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Master Schedule',
                ]),

                array_merge($getDocument(14, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Data Dokumentasi',
                ]),
            ],
        ];
    }

    // public function getDashboardDataPekerjaanFisik(): array
    // {
    //     $spreadsheetId = '1QivYB914J3OR6wdnj9Sb4y0SNYYRzT6jQ4590X-e-Bo';

    //     $cells = $this->readDashboardLinks(
    //         $spreadsheetId,
    //         "'PROGRESS PEKERJAAN'!D3:G7"
    //     );
    //     return $cells;
    // }

    // public function getDashboardDataPekerjaanFisik(): array
    // {
    //     $spreadsheetId = '1QivYB914J3OR6wdnj9Sb4y0SNYYRzT6jQ4590X-e-Bo';

    //     $cells = $this->readDashboardLinks(
    //         $spreadsheetId,
    //         "'PROGRESS PEKERJAAN'!D3:G7"
    //     );

    //     $grid = [];

    //     foreach ($cells as $cell) {
    //         $grid[$cell['row']][$cell['column']] = trim(
    //             (string) ($cell['value'] ?? '')
    //         );
    //     }

    //     // Label berdasarkan baris spreadsheet
    //     $labels = [
    //         0 => 'Kawasan Jetty',       // D3
    //         1 => 'Kawasan Power Plant', // D4
    //         2 => 'Refinery',            // D5
    //         3 => 'Redmud',              // D6
    //     ];

    //     $data = [];

    //     foreach ($labels as $row => $label) {
    //         $progress = $grid[$row][3] ?? '';

    //         $data[] = [
    //             'nama' => $label,
    //             'progress' => $progress !== '' ? $progress : '-',
    //         ];
    //     }

    //     // Progress keseluruhan dari baris D7:G7
    //     $data[] = [
    //         'nama' => 'Progress Keseluruhan',
    //         'progress' => $grid[4][3] ?? '-',
    //     ];

    //     return $data;
    // }

    // public function getDashboardDataPekerjaanFisik(): array
    // {
    //     $fileId = '1ecvWfIpjhryFk_i0VQo0bL3AmySO4Q-R';


    //     $client = new \Google\Client();

    //     $client->setApplicationName('KAN Dashboard');

    //     $client->setAuthConfig(
    //         storage_path('app/private/google-service-account.json')
    //     );

    //     $client->setScopes([
    //         \Google\Service\Drive::DRIVE_READONLY,
    //     ]);

    //     $drive = new \Google\Service\Drive($client);
    //     $file = $drive->files->get($fileId, [
    //         'fields' => 'id,name,mimeType,webViewLink',
    //     ]);



    //     // Ambil konten file Excel dari Google Drive.
    //     $response = $drive->files->get($fileId, [
    //         'alt' => 'media',
    //     ]);

    //     // $response = $drive->files->get($fileId, [
    //     //     'alt' => 'media',
    //     // ]);

    //     $content = $response instanceof \Psr\Http\Message\ResponseInterface
    //         ? $response->getBody()->getContents()
    //         : (string) $response;
    //     // Simpan sementara agar dapat dibaca PhpSpreadsheet.
    //     $tempFile = tempnam(sys_get_temp_dir(), 'kan_excel_');

    //     if ($tempFile === false) {
    //         throw new \RuntimeException(
    //             'Gagal membuat file sementara.'
    //         );
    //     }

    //     try {
    //         if (file_put_contents($tempFile, $content) === false) {
    //             throw new \RuntimeException(
    //                 'Gagal menulis file Excel sementara.'
    //             );
    //         }

    //         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    //         $reader->setReadDataOnly(false);

    //         $spreadsheet = $reader->load($tempFile);

    //         $sheet = $spreadsheet->getSheetByName(
    //             'PROGRESS PEKERJAAN'
    //         );

    //         if ($sheet === null) {
    //             throw new \RuntimeException(
    //                 'Sheet PROGRESS PEKERJAAN tidak ditemukan.'
    //             );
    //         }

    //         $labels = [
    //             3 => 'Kawasan Jetty',
    //             4 => 'Kawasan Power Plant',
    //             5 => 'Kawasan Refinery',
    //             6 => 'Kawasan Redmud',
    //         ];

    //         $data = [];

    //         foreach ($labels as $row => $label) {
    //             $progress = trim(
    //                 (string) $sheet->getCell("G{$row}")
    //                     ->getFormattedValue()
    //             );

    //             $data[] = [
    //                 'nama' => $label,
    //                 'progress' => $progress !== '' ? $progress : '-',
    //             ];
    //         }

    //         $total = trim(
    //             (string) $sheet->getCell('G7')->getFormattedValue()
    //         );

    //         $data[] = [
    //             'nama' => 'Progress Keseluruhan',
    //             'progress' => $total !== '' ? $total : '-',
    //         ];

    //         $spreadsheet->disconnectWorksheets();

    //         return $data;
    //     } finally {
    //         if (file_exists($tempFile)) {
    //             unlink($tempFile);
    //         }
    //     }
    // }


    public function getDashboardDataPekerjaanFisik(): array
    {
        $fileId = '1ecvWfIpjhryFk_i0VQo0bL3AmySO4Q-R';

        $client = new \Google\Client();
        $client->setApplicationName('KAN Dashboard');
        $client->setAuthConfig(
            storage_path('app/private/google-service-account.json')
        );
        $client->setScopes([
            \Google\Service\Drive::DRIVE_READONLY,
        ]);

        $drive = new \Google\Service\Drive($client);

        $response = $drive->files->get($fileId, [
            'alt' => 'media',
        ]);

        $content = $response instanceof \Psr\Http\Message\ResponseInterface
            ? $response->getBody()->getContents()
            : (string) $response;

        $tempFile = tempnam(sys_get_temp_dir(), 'kan_excel_');

        if ($tempFile === false) {
            throw new \RuntimeException('Gagal membuat file sementara.');
        }

        try {
            if (file_put_contents($tempFile, $content) === false) {
                throw new \RuntimeException(
                    'Gagal menulis file Excel sementara.'
                );
            }

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(false);

            $spreadsheet = $reader->load($tempFile);

            $sheet = $spreadsheet->getSheetByName('PROGRESS PEKERJAAN');

            if ($sheet === null) {
                throw new \RuntimeException(
                    'Sheet PROGRESS PEKERJAAN tidak ditemukan.'
                );
            }

            $labels = [
                3 => 'Kawasan Jetty',
                4 => 'Kawasan Power Plant',
                5 => 'Kawasan Refinery',
                6 => 'Kawasan Redmud',
            ];

            $data = [];
            $total = 0;
            $jumlahKawasan = 0;

            foreach ($labels as $row => $label) {
                $cell = $sheet->getCell("G{$row}");

                // Ambil nilai sel untuk ditampilkan.
                $value = $cell->getCalculatedValue();

                // Jika sel berisi referensi sederhana seperti =I11,
                // ambil hasil dari sel sumbernya.
                if (
                    is_string($value)
                    && preg_match('/^=([A-Z]+[0-9]+)$/i', trim($value), $matches)
                ) {
                    $value = $sheet->getCell($matches[1])->getCalculatedValue();
                }

                // Normalisasi nilai progres.
                $progress = is_numeric($value)
                    ? (float) $value
                    : null;

                // $data[] = [
                //     'nama' => $label,
                //     'progress' => $progress !== null
                //         ? number_format($progress, 2, '.', '')
                //         : '-',
                // ];
                $data[] = [
                    'nama' => $label,
                    'progress' => $progress !== ''
                        ? (string) round((float) $progress)
                        : '-',
                ];

                // Total berasal dari angka yang sama dengan tampilan.
                if ($progress !== null) {
                    $total += $progress;
                    $jumlahKawasan++;
                }
            }

            // Hitung total dari empat kawasan.
            $data[] = [
                'nama' => 'Progress Keseluruhan',
                'progress' => $jumlahKawasan > 0
                    ? (string) round($total / $jumlahKawasan)
                    : '0',
            ];

            $spreadsheet->disconnectWorksheets();

            return $data;
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }



    // public function getDashboardDataPekerjaanFisik(): array
    // {
    //     $fileId = '1ecvWfIpjhryFk_i0VQo0bL3AmySO4Q-R';

    //     $client = new \Google\Client();

    //     $client->setApplicationName('KAN Dashboard');

    //     $client->setAuthConfig(
    //         storage_path('app/private/google-service-account.json')
    //     );

    //     $client->setScopes([
    //         \Google\Service\Drive::DRIVE_READONLY,
    //     ]);

    //     $drive = new \Google\Service\Drive($client);

    //     $file = $drive->files->get($fileId, [
    //         'fields' => 'id,name,mimeType,webViewLink',
    //     ]);

    //     // dd([
    //     //     'id' => $file->getId(),
    //     //     'name' => $file->getName(),
    //     //     'mimeType' => $file->getMimeType(),
    //     //     'url' => $file->getWebViewLink(),
    //     // ]);
    // }
}
