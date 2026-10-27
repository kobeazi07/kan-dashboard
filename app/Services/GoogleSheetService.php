<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;


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
            'Dashboard!K12:R35'
        );

        // Susun hasil berdasarkan nomor baris dan kolom.
        $grid = [];

        foreach ($cells as $cell) {
            $grid[$cell['row']][$cell['column']] = $cell;
        }

        // Ambil nama, URL, dan progres berdasarkan baris dan kolom.
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

            $progress = is_string($progressText)
                && preg_match(
                    '/^\s*(-?\d+(?:[.,]\d+)?)\s*%\s*$/',
                    $progressText,
                    $matches
                )
                ? (float) str_replace(',', '.', $matches[1])
                : null;

            return [
                'name' => $nameCell['value'] ?? null,
                'url' => $nameCell['url'] ?? null,
                'progress' => $progress,
            ];
        };

        $result = [
            'Dermaga Jetty' => [
                $getDocument(3, 0, 2),
                $getDocument(4, 0, 2),
                $getDocument(5, 0, 2),
                $getDocument(7, 0, 2),

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
                $getDocument(3, 5, 7),
                $getDocument(4, 5, 7),
                $getDocument(5, 5, 7),
                $getDocument(7, 5, 7),

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
                $getDocument(11, 0, 2),
                $getDocument(12, 0, 2),
                $getDocument(13, 0, 2),
                $getDocument(15, 0, 2),

                array_merge($getDocument(8, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Master Schedule',
                ]),

                array_merge($getDocument(14, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Data Dokumentasi',
                ]),
            ],

            'Red Mud' => [
                $getDocument(11, 5, 7), // PQ23 / R23
                $getDocument(12, 5, 7), // PQ24 / R24
                $getDocument(13, 5, 7), // PQ25 / R25
                array_merge($getDocument(14, 5, 7), [ // PQ26 / R26
                    'type' => 'additional',
                    'label' => 'Data Dokumentasi',
                ]),
                array_merge($getDocument(8, 5, 7), [
                    'type' => 'additional',
                    'label' => 'Master Schedule',
                ]),
            ],

            'Jembatan' => [
                $getDocument(20, 0, 2), // KL32 dan M32
                $getDocument(21, 0, 2), // KL33 dan M33
                $getDocument(22, 0, 2), // KL34 dan M34

                array_merge($getDocument(23, 0, 2), [
                    'type' => 'additional',
                    'label' => 'Data Dokumentasi',
                ]),
            ],
        ];

        // Normalisasi kategori dan nama dokumen.
        $normalize = function ($value): string {
            $value = mb_strtolower(trim((string) $value));

            return preg_replace('/[\s-]+/u', '', $value) ?? '';
        };

        // Ambil URL unduhan dari database.
        $documents = DB::table('download_dokumen_tables')
            ->select('nama', 'kategori', 'download_dokumen')
            ->get();

        $lookup = [];

        foreach ($documents as $document) {
            $key = $normalize($document->kategori)
                . '|'
                . $normalize($document->nama);

            $lookup[$key] = $document->download_dokumen;
        }

        // Hubungkan setiap item dashboard dengan URL unduhan.
        foreach ($result as $kategori => &$items) {
            foreach ($items as &$item) {
                $namaDokumen = $item['label']
                    ?? $item['name']
                    ?? '';

                $key = $normalize($kategori)
                    . '|'
                    . $normalize($namaDokumen);

                $item['download_dokumen'] = $lookup[$key] ?? null;
            }

            unset($item);
        }

        unset($items);

        return $result;
    }

    // public function getDashboardData(): array
    // {
    //     $spreadsheetId = '1s7HqchxwI99SLmercIENi7DWRZZbjaM3Tbkvt_EDi9k';

    //     $cells = $this->readDashboardLinks(
    //         $spreadsheetId,
    //         'Dashboard!K12:R28'
    //     );

    //     // Susun hasil berdasarkan nomor baris dan kolom.
    //     $grid = [];

    //     foreach ($cells as $cell) {
    //         $grid[$cell['row']][$cell['column']] = $cell;
    //     }

    //     // Ambil nama, URL, dan progres dari baris tertentu.
    //     $getDocument = function (
    //         int $row,
    //         int $nameColumn,
    //         ?int $progressColumn = null
    //     ) use (&$grid): array {
    //         $nameCell = $grid[$row][$nameColumn] ?? [];

    //         $progressCell = $progressColumn !== null
    //             ? ($grid[$row][$progressColumn] ?? [])
    //             : [];

    //         $progressText = $progressCell['value'] ?? null;

    //         // Contoh: "63%" menjadi 63.
    //         $progress = is_string($progressText)
    //             && preg_match(
    //                 '/^\s*(-?\d+(?:[.,]\d+)?)\s*%\s*$/',
    //                 $progressText,
    //                 $matches
    //             )
    //             ? (float) str_replace(',', '.', $matches[1])
    //             : null;

    //         return [
    //             'name' => $nameCell['value'] ?? null,
    //             'url' => $nameCell['url'] ?? null,
    //             'progress' => $progress,
    //         ];
    //     };

    //     // Susun data dashboard.
    //     $result = [
    //         'Dermaga Jetty' => [
    //             $getDocument(3, 0, 2),
    //             $getDocument(4, 0, 2),
    //             $getDocument(5, 0, 2),
    //             $getDocument(7, 0, 2),

    //             array_merge($getDocument(0, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),

    //             array_merge($getDocument(6, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //         ],

    //         'Refinery' => [
    //             $getDocument(3, 5, 7),
    //             $getDocument(4, 5, 7),
    //             $getDocument(5, 5, 7),
    //             $getDocument(7, 5, 7),

    //             array_merge($getDocument(0, 5, 7), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),

    //             array_merge($getDocument(6, 5, 7), [
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //         ],

    //         'Power Plant' => [
    //             $getDocument(11, 0, 2),
    //             $getDocument(12, 0, 2),
    //             $getDocument(13, 0, 2),
    //             $getDocument(15, 0, 2),

    //             array_merge($getDocument(8, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),

    //             array_merge($getDocument(14, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //         ],
    //         'Red Mud' => [
    //             $getDocument(11, 5, 7), // PQ23 / R23
    //             $getDocument(12, 5, 7), // PQ24 / R24
    //             $getDocument(13, 5, 7), // PQ25 / R25
    //             array_merge($getDocument(14, 5, 7), [ // PQ26 / R26
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //             array_merge($getDocument(8, 5, 7), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),
    //         ],

    //         'Jembatan' => [
    //             $getDocument(31, 0, 2), // KL32 / M32
    //             $getDocument(32, 0, 2), // KL33 / M33
    //             $getDocument(33, 0, 2), // KL34 / M34
    //             array_merge($getDocument(34, 0, 2), [ // KL35 / M35
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //             array_merge($getDocument(28, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),
    //         ],
    //     ];

    //     // Normalisasi kategori dan nama dokumen.
    //     $normalize = function ($value): string {
    //         $value = mb_strtolower(trim((string) $value));

    //         // Samakan perbedaan spasi dan tanda hubung.
    //         return preg_replace('/[\s-]+/u', '', $value) ?? '';
    //     };

    //     // Ambil data URL unduhan dari database.
    //     $documents = DB::table('download_dokumen_tables')
    //         ->select('nama', 'kategori', 'download_dokumen')
    //         ->get();

    //     // Buat indeks berdasarkan kategori dan nama dokumen.
    //     $lookup = [];

    //     foreach ($documents as $document) {
    //         $key = $normalize($document->kategori)
    //             . '|'
    //             . $normalize($document->nama);

    //         $lookup[$key] = $document->download_dokumen;
    //     }

    //     // Hubungkan setiap item dashboard dengan URL database.
    //     foreach ($result as $kategori => &$items) {
    //         foreach ($items as &$item) {
    //             $namaDokumen = $item['label']
    //                 ?? $item['name']
    //                 ?? '';

    //             $key = $normalize($kategori)
    //                 . '|'
    //                 . $normalize($namaDokumen);

    //             $item['download_dokumen'] = $lookup[$key] ?? null;
    //         }

    //         unset($item);
    //     }

    //     unset($items);

    //     return $result;
    // }


    // public function getDashboardData(): array
    // {
    //     $spreadsheetId = '1s7HqchxwI99SLmercIENi7DWRZZbjaM3Tbkvt_EDi9k';

    //     $cells = $this->readDashboardLinks(
    //         $spreadsheetId,
    //         'Dashboard!K12:R28'
    //     );

    //     // Susun hasil berdasarkan nomor baris dan kolom.
    //     $grid = [];

    //     foreach ($cells as $cell) {
    //         $grid[$cell['row']][$cell['column']] = $cell;
    //     }

    //     // Ambil nama, URL, dan progres dari baris tertentu.
    //     $getDocument = function (
    //         int $row,
    //         int $nameColumn,
    //         ?int $progressColumn = null
    //     ) use (&$grid): array {
    //         $nameCell = $grid[$row][$nameColumn] ?? [];
    //         $progressCell = $progressColumn !== null
    //             ? ($grid[$row][$progressColumn] ?? [])
    //             : [];

    //         $progressText = $progressCell['value'] ?? null;

    //         // Contoh: "63%" menjadi 63.
    //         $progress = is_string($progressText)
    //             && preg_match('/^\s*(-?\d+(?:[.,]\d+)?)\s*%\s*$/', $progressText, $matches)
    //             ? (float) str_replace(',', '.', $matches[1])
    //             : null;

    //         return [
    //             'name' => $nameCell['value'] ?? null,
    //             'url' => $nameCell['url'] ?? null,
    //             'progress' => $progress,
    //         ];
    //     };


    //     return [
    //         'Dermaga Jetty' => [
    //             $getDocument(3, 0, 2), // Baris 15
    //             $getDocument(4, 0, 2), // Baris 16
    //             $getDocument(5, 0, 2), // Baris 17
    //             $getDocument(7, 0, 2), // Baris 17
    //             // data dokumentasi
    //             // $getDocument(6, 0, 2), // Baris 18
    //             // akhir data dokumentasi

    //             // $getDocument(0, 0, 2), // Tambahan: KLM baris 12-14

    //             array_merge($getDocument(0, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),

    //             array_merge($getDocument(6, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //         ],

    //         'Refinery' => [
    //             $getDocument(3, 5, 7), // Baris 15
    //             $getDocument(4, 5, 7), // Baris 16
    //             $getDocument(5, 5, 7), // Baris 17
    //             // data dokumentasi
    //             // $getDocument(6, 5, 7), // Baris 18
    //             // akhir data dokumentasi

    //             $getDocument(7, 5, 7), // Baris 19
    //             // $getDocument(0, 5, 7), // Tambahan: PQR baris 12-14

    //             array_merge($getDocument(0, 5, 7), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),

    //             array_merge($getDocument(6, 5, 7), [
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //         ],

    //         'Power Plant' => [
    //             $getDocument(11, 0, 2), // Baris 23
    //             $getDocument(12, 0, 2), // Baris 24
    //             $getDocument(13, 0, 2), // Baris 25
    //             // data dokumentasi
    //             // $getDocument(14, 0, 2), // Baris 26
    //             // akhir data dokumentasi

    //             $getDocument(15, 0, 2), // Baris 27
    //             // $getDocument(8, 0, 2),  // Tambahan: KLM baris 20-22

    //             array_merge($getDocument(8, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Master Schedule',
    //             ]),

    //             array_merge($getDocument(14, 0, 2), [
    //                 'type' => 'additional',
    //                 'label' => 'Data Dokumentasi',
    //             ]),
    //         ],
    //     ];
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


    // timeline

    public function getDashboardDataTimeline(): array
    {
        $fileId = '17iAxg27SNC5QEzWvG0jEyXNqVxFC5rDf';

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

        $tempFile = tempnam(sys_get_temp_dir(), 'kan_timeline_');

        if ($tempFile === false) {
            throw new \RuntimeException(
                'Gagal membuat file sementara.'
            );
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

            $sheet = $spreadsheet->getSheetByName('JADWAL');

            if ($sheet === null) {
                throw new \RuntimeException(
                    'Sheet JADWAL tidak ditemukan.'
                );
            }
            // dd jadwalatas
            // dd([
            //     'F8' => [
            //         'fill_type' => $sheet->getStyle('F8')->getFill()->getFillType(),
            //         'rgb' => $sheet->getStyle('F8')->getFill()->getStartColor()->getRGB(),
            //         'argb' => $sheet->getStyle('F8')->getFill()->getStartColor()->getARGB(),
            //         'conditional_rules' => count(
            //             $sheet->getStyle('F8')->getConditionalStyles()
            //         ),
            //     ],
            //     'G8' => [
            //         'fill_type' => $sheet->getStyle('G8')->getFill()->getFillType(),
            //         'rgb' => $sheet->getStyle('G8')->getFill()->getStartColor()->getRGB(),
            //         'argb' => $sheet->getStyle('G8')->getFill()->getStartColor()->getARGB(),
            //         'conditional_rules' => count(
            //             $sheet->getStyle('G8')->getConditionalStyles()
            //         ),
            //     ],
            // ]);
            /*
        |--------------------------------------------------------------------------
        | 1. MEMBACA DATA EXCEL
        |--------------------------------------------------------------------------
        */

            $range = $sheet->rangeToArray(
                'B3:AG53',
                null,
                true,
                true,
                false
            );

            /*
        |--------------------------------------------------------------------------
        | 2. MEMBACA HEADER BULAN
        |--------------------------------------------------------------------------
        |
        | Range dimulai dari B.
        | Indeks 4 = F, yaitu bulan pertama pada timeline.
        | Indeks 30 = AF, yaitu bulan terakhir pada timeline.
        |
        */

            $headerBulan = $range[2] ?? [];
            $periode = [];
            $tahun = 2025;

            for ($kolomExcel = 6; $kolomExcel <= 32; $kolomExcel++) {
                $indexRange = $kolomExcel - 2;

                $indexBulan = $kolomExcel - 6;

                if ($indexBulan === 3) {
                    $tahun = 2026;
                } elseif ($indexBulan === 15) {
                    $tahun = 2027;
                }

                $namaBulan = $headerBulan[$indexRange] ?? null;

                $periode[$tahun][] = [
                    'bulan' => $namaBulan,
                    'kolom_excel' => Coordinate::stringFromColumnIndex(
                        $kolomExcel
                    ),
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | 3. MEMBACA KAWASAN DAN KEGIATAN
        |--------------------------------------------------------------------------
        */

            $data = [];
            $kawasanAktif = null;

            $daftarKawasan = [
                'DERMAGA JETTY',
                'REFINERY',
                'POWER PLANT',
                'RED MUD',
            ];

            foreach ($range as $index => $row) {
                $excelRow = $index + 3;

                $values = array_map(
                    static fn($value) => is_string($value)
                        ? trim($value)
                        : $value,
                    $row
                );

                $isEmpty = count(array_filter(
                    $values,
                    static fn($value) => $value !== null && $value !== ''
                )) === 0;

                if ($isEmpty) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | 3.1. IDENTIFIKASI KAWASAN
            |--------------------------------------------------------------------------
            */

                $teksBaris = strtoupper(
                    implode(' ', array_filter(
                        array_map(
                            static fn($value) => trim((string) $value),
                            array_slice($values, 0, 4)
                        ),
                        static fn($value) => $value !== ''
                    ))
                );

                $kawasanDitemukan = null;

                foreach ($daftarKawasan as $namaKawasan) {
                    if (str_contains($teksBaris, $namaKawasan)) {
                        $kawasanDitemukan = $namaKawasan;
                        break;
                    }
                }

                if ($kawasanDitemukan !== null) {
                    $kawasanAktif = $kawasanDitemukan;

                    $data[] = [
                        'jenis' => 'kawasan',
                        'baris_excel' => $excelRow,
                        'kawasan' => $kawasanAktif,
                        'nama' => $kawasanDitemukan,
                    ];

                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | 3.2. LEWATI HEADER DAN BARIS DI LUAR KAWASAN
            |--------------------------------------------------------------------------
            */

                if ($excelRow <= 6 || $kawasanAktif === null) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | 3.3. MEMBACA NOMOR DAN URAIAN KEGIATAN
            |--------------------------------------------------------------------------
            |
            | B = indeks 0
            | C = indeks 1
            | D = indeks 2
            | AG = indeks 31
            |
            */

                $nomor = $values[1] ?? null;
                $uraian = $values[2] ?? null;

                if ($uraian === null || trim((string) $uraian) === '') {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | 3.4. MEMBACA JADWAL BERDASARKAN WARNA SEL
            |--------------------------------------------------------------------------
            |
            | F sampai AF adalah kolom jadwal.
            | Sel hijau menandakan kegiatan terjadwal.
            |
            */
                $jadwal = [];

                for ($baris = 7; $baris <= 53; $baris++) {
                    for ($kolom = 6; $kolom <= 32; $kolom++) {
                        $namaKolom = Coordinate::stringFromColumnIndex($kolom);
                        $koordinat = $namaKolom . $baris;

                        $fill = $sheet->getStyle($koordinat)->getFill();

                        $fillType = $fill->getFillType();

                        $warna = strtoupper(
                            (string) $fill->getStartColor()->getRGB()
                        );

                        $jadwal[] = [
                            'kolom' => $koordinat,
                            'warna' => $warna,
                            'terjadwal' => (
                                $fillType === Fill::FILL_SOLID
                                && $warna === 'C2D69B'
                            ),
                        ];
                    }
                }

                // dd($jadwal);
                /*
            |--------------------------------------------------------------------------
            | 3.5. MEMBACA KETERANGAN KOLOM AG
            |--------------------------------------------------------------------------
            */

                $keterangan = $sheet
                    ->getCell('AG' . $excelRow)
                    ->getFormattedValue();

                $keterangan = is_string($keterangan)
                    ? trim($keterangan)
                    : $keterangan;

                /*
            |--------------------------------------------------------------------------
            | 3.6. SIMPAN DATA KEGIATAN
            |--------------------------------------------------------------------------
            */

                $data[] = [
                    'jenis' => 'pekerjaan',
                    'baris_excel' => $excelRow,
                    'kawasan' => $kawasanAktif,
                    'no' => $nomor,
                    'uraian' => $uraian,
                    'jadwal' => $jadwal,
                    'keterangan' => $keterangan,
                ];
            }

            $spreadsheet->disconnectWorksheets();

            return [
                'periode' => $periode,
                'data' => $data,
            ];
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    // public function getDashboardDataTimeline(): array
    // {
    //     $fileId = '17iAxg27SNC5QEzWvG0jEyXNqVxFC5rDf';

    //     $client = new \Google\Client();
    //     $client->setApplicationName('KAN Dashboard');
    //     $client->setAuthConfig(
    //         storage_path('app/private/google-service-account.json')
    //     );
    //     $client->setScopes([
    //         \Google\Service\Drive::DRIVE_READONLY,
    //     ]);

    //     $drive = new \Google\Service\Drive($client);

    //     $response = $drive->files->get($fileId, [
    //         'alt' => 'media',
    //     ]);

    //     $content = $response instanceof \Psr\Http\Message\ResponseInterface
    //         ? $response->getBody()->getContents()
    //         : (string) $response;

    //     $tempFile = tempnam(sys_get_temp_dir(), 'kan_timeline_');

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
    //         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
    //         $reader->setReadDataOnly(false);

    //         $spreadsheet = $reader->load($tempFile);

    //         // Periksa semua nama sheet dalam file Excel.
    //         $sheet = $spreadsheet->getSheetByName('JADWAL');

    //         if ($sheet === null) {
    //             throw new \RuntimeException(
    //                 'Sheet JADWAL tidak ditemukan.'
    //             );
    //         }
    //         /*
    //     |--------------------------------------------------------------------------
    //     | 1. MEMBACA HEADER BULAN
    //     |--------------------------------------------------------------------------
    //     */

    //         $range = $sheet->rangeToArray(
    //             'B3:AG53',
    //             null,
    //             true,
    //             true,
    //             false
    //         );

    //         // Baris Excel 5 adalah header bulan.
    //         // Karena range dimulai dari baris 3, indeksnya adalah 2.
    //         $headerBulan = $range[2] ?? [];
    //         $periode = [];
    //         $tahun = 2025;

    //         for ($i = 4; $i <= 30; $i++) {
    //             $indexBulan = $i - 4;

    //             // 3 bulan pertama masuk tahun 2025.
    //             // Setelah itu, setiap 12 bulan berganti tahun.
    //             if ($indexBulan === 3) {
    //                 $tahun = 2026;
    //             } elseif ($indexBulan === 15) {
    //                 $tahun = 2027;
    //             }

    //             $periode[$tahun][] = [
    //                 'bulan' => $headerBulan[$i] ?? null,
    //                 'kolom_excel' => Coordinate::stringFromColumnIndex($i + 2),
    //             ];
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | 2. MEMBACA KAWASAN DAN KEGIATAN
    //     |--------------------------------------------------------------------------
    //     */

    //         $data = [];

    //         $kawasanAktif = null;

    //         $daftarKawasan = [
    //             'DERMAGA JETTY',
    //             'REFINERY',
    //             'POWER PLANT',
    //             'RED MUD',
    //         ];

    //         foreach ($range as $index => $row) {
    //             $excelRow = $index + 3;

    //             $values = array_map(
    //                 static fn($value) => is_string($value)
    //                     ? trim($value)
    //                     : $value,
    //                 $row
    //             );

    //             $isEmpty = count(array_filter(
    //                 $values,
    //                 static fn($value) => $value !== null && $value !== ''
    //             )) === 0;

    //             if ($isEmpty) {
    //                 continue;
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | 2.1. IDENTIFIKASI KAWASAN
    //         |--------------------------------------------------------------------------
    //         */

    //             $teksBaris = strtoupper(
    //                 implode(' ', array_filter(
    //                     array_map(
    //                         static fn($value) => trim((string) $value),
    //                         array_slice($values, 0, 4)
    //                     ),
    //                     static fn($value) => $value !== ''
    //                 ))
    //             );

    //             $kawasanDitemukan = null;

    //             foreach ($daftarKawasan as $namaKawasan) {
    //                 if (str_contains($teksBaris, $namaKawasan)) {
    //                     $kawasanDitemukan = $namaKawasan;
    //                     break;
    //                 }
    //             }

    //             if ($kawasanDitemukan !== null) {
    //                 $kawasanAktif = $kawasanDitemukan;

    //                 $data[] = [
    //                     'jenis' => 'kawasan',
    //                     'baris_excel' => $excelRow,
    //                     'kawasan' => $kawasanAktif,
    //                     'nama' => $kawasanDitemukan,
    //                 ];

    //                 continue;
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | 2.2. LEWATI HEADER DAN BARIS DI LUAR KAWASAN
    //         |--------------------------------------------------------------------------
    //         */

    //             if ($excelRow <= 6 || $kawasanAktif === null) {
    //                 continue;
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | 2.3. MEMBACA KEGIATAN
    //         |--------------------------------------------------------------------------
    //         |
    //         | Range B:AG:
    //         | B = indeks 0
    //         | C = indeks 1
    //         | D = indeks 2
    //         | E = indeks 3
    //         | F = indeks 4
    //         | AF = indeks 30
    //         | AG = indeks 31
    //         |
    //         */

    //             $nomor = $values[1] ?? null;
    //             $uraian = $values[2] ?? null;

    //             if ($uraian === null || trim((string) $uraian) === '') {
    //                 continue;
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | 2.4. MEMBACA STATUS WARNA JADWAL
    //         |--------------------------------------------------------------------------
    //         */

    //             $jadwal = [];

    //             for ($kolom = 6; $kolom <= 32; $kolom++) {
    //                 $koordinat = $kolom . $excelRow;

    //                 $cell = $sheet->getStyle($koordinat)->getFill();
    //                 $fillType = $cell->getFillType();
    //                 $warna = $cell->getStartColor()->getRGB();

    //                 $jadwal[] = [
    //                     'kolom' => $koordinat,
    //                     'nilai' => $sheet->getCell($koordinat)->getValue(),
    //                     'warna' => $warna,
    //                     'terjadwal' => $fillType !== Fill::FILL_NONE
    //                         && $warna === '00FF00',
    //                 ];
    //             }

    //             /*
    //         |--------------------------------------------------------------------------
    //         | 2.5. SIMPAN KEGIATAN
    //         |--------------------------------------------------------------------------
    //         */

    //             $data[] = [
    //                 'jenis' => 'pekerjaan',
    //                 'baris_excel' => $excelRow,
    //                 'kawasan' => $kawasanAktif,
    //                 'no' => $nomor,
    //                 'uraian' => $uraian,
    //                 'jadwal' => $jadwal,
    //                 'keterangan' => $values[31] ?? null,
    //             ];
    //         }

    //         $spreadsheet->disconnectWorksheets();

    //         return [
    //             'periode' => $periode,
    //             'data' => $data,
    //         ];
    //     } finally {
    //         if (file_exists($tempFile)) {
    //             unlink($tempFile);
    //         }
    //     }
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

    //     // dd([
    //     //     'id' => $file->getId(),
    //     //     'name' => $file->getName(),
    //     //     'mimeType' => $file->getMimeType(),
    //     //     'url' => $file->getWebViewLink(),
    //     // ]);
    // }
}
