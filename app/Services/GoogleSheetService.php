<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

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
            'Dashboard!K15:R28'
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
                $getDocument(0, 0, 2),
                $getDocument(1, 0, 2),
                $getDocument(2, 0, 2),
                $getDocument(3, 0, 2),
            ],

            'Refinery' => [
                $getDocument(0, 5, 7),
                $getDocument(1, 5, 7),
                $getDocument(2, 5, 7),
                $getDocument(3, 5, 7),
            ],

            'Power Plant' => [
                $getDocument(8, 0, 2),
                $getDocument(9, 0, 2),
                $getDocument(10, 0, 2),
                $getDocument(11, 0, 2),
            ],
        ];
    }
}
