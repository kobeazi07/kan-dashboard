<div class="card shadow-sm mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Timeline Progress Pekerjaan</h5>

        <select id="filterKawasan" class="form-select w-auto">
            <option value="DERMAGA JETTY">DERMAGA JETTY</option>
            <option value="REFINERY">REFINERY</option>
            <option value="POWER PLANT">POWER PLANT</option>
            <option value="RED MUD">RED MUD</option>
        </select>
    </div>

    <div class="card-body">
        <div class="table-responsive timeline-wrapper">

            <table class="table table-bordered timeline-table">
                <thead>
                    <tr>
                        <th rowspan="3" class="text-center align-middle sticky-col col-no">
                            NO
                        </th>
                        <th rowspan="3" class="text-center align-middle sticky-col col-uraian">
                            KEGIATAN
                        </th>
                        <th colspan="{{ collect($timeline['periode'])->flatten(1)->count() }}" class="text-center">
                            TAHUN
                        </th>
                        <th rowspan="3" class="text-center align-middle">
                            KET
                        </th>
                    </tr>

                    <tr>
                        @foreach ($timeline['periode'] as $tahun => $daftarBulan)
                            <th colspan="{{ count($daftarBulan) }}" class="text-center">
                                {{ $tahun }}
                            </th>
                        @endforeach
                    </tr>

                    <tr>
                        @foreach ($timeline['periode'] as $daftarBulan)
                            @foreach ($daftarBulan as $bulan)
                                <th class="text-center timeline-month">
                                    {{ $bulan['bulan'] }}
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeline['data'] as $item)
                        @if ($item['jenis'] === 'kawasan')
                            <tr class="baris-kawasan" data-kawasan="{{ $item['kawasan'] }}">
                                <td colspan="{{ 3 + collect($timeline['periode'])->flatten(1)->count() }}"
                                    class="fw-bold bg-light">
                                    {{ $item['nama'] }}
                                </td>
                            </tr>
                        @elseif ($item['jenis'] === 'pekerjaan')
                            <tr class="baris-pekerjaan" data-kawasan="{{ $item['kawasan'] }}">
                                <td class="text-center">
                                    {{ $item['no'] ?? '' }}
                                </td>

                                <td class="timeline-uraian">
                                    {{ $item['uraian'] ?? '' }}
                                </td>

                                {{-- Pertahankan perulangan kolom bulan yang sudah ada --}}

                                @foreach ($timeline['periode'] as $tahun => $daftarBulan)
                                    @foreach ($daftarBulan as $bulan)
                                        @php
                                            $kolomExcel = $bulan['kolom_excel'] ?? null;

                                            $jadwalBulan = collect($item['jadwal'] ?? [])->firstWhere(
                                                'kolom',
                                                $kolomExcel . $item['baris_excel'],
                                            );
                                        @endphp

                                        <td class="timeline-cell">
                                            @if ($jadwalBulan && ($jadwalBulan['terjadwal'] ?? false))
                                                <div class="timeline-block"></div>
                                            @endif
                                        </td>
                                    @endforeach
                                @endforeach

                                <td>{{ $item['keterangan'] ?? '' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<style>
    .timeline-wrapper {
        max-height: 650px;
        overflow: auto;
        position: relative;
    }

    .timeline-table {
        width: max-content;
        min-width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12px;
    }

    .timeline-table th,
    .timeline-table td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 6px 10px;
    }

    .timeline-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8f9fa;
    }

    .timeline-table thead tr:nth-child(2) th {
        top: 34px;
    }

    .timeline-table .sticky-col {
        position: sticky;
        background: #fff;
        z-index: 3;
    }

    .timeline-table .col-no {
        left: 0;
        min-width: 55px;
    }

    .timeline-table .col-uraian {
        left: 55px;
        min-width: 230px;
        max-width: 230px;
        white-space: normal;
    }

    .timeline-table thead .sticky-col {
        z-index: 7;
        background: #f8f9fa;
    }

    .timeline-table tbody .baris-kawasan td,
    .timeline-table tbody .baris-subbagian td {
        position: static;
    }

    .timeline-wrapper {
        max-height: 650px;
        overflow: auto;
        position: relative;
    }

    .timeline-table {
        width: max-content;
        min-width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12px;
    }

    .timeline-table th,
    .timeline-table td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 6px 8px;
        border: 1px solid #dee2e6;
    }

    .timeline-table thead th {
        position: sticky;
        background: #f8f9fa;
        text-align: center;
    }

    .timeline-table thead tr:nth-child(1) th {
        top: 0;
        z-index: 8;
    }

    .timeline-table thead tr:nth-child(2) th {
        top: 31px;
        z-index: 8;
    }

    .timeline-table thead tr:nth-child(3) th {
        top: 62px;
        z-index: 8;
    }

    .timeline-table .sticky-col {
        position: sticky;
        background: #fff;
    }

    .timeline-table .col-no {
        left: 0;
        min-width: 55px;
        width: 55px;
    }

    .timeline-table .col-uraian {
        left: 55px;
        min-width: 230px;
        width: 230px;
        max-width: 230px;
        white-space: normal;
    }

    .timeline-table thead .sticky-col {
        z-index: 10 !important;
        background: #f8f9fa;
    }

    .timeline-table tbody .sticky-col {
        z-index: 3;
    }

    .timeline-month,
    .timeline-cell {
        min-width: 40px;
        width: 40px;
    }

    .timeline-cell {
        height: 30px;
        padding: 4px !important;
    }

    .timeline-block {
        width: 100%;
        height: 18px;
        background: #00b050;
        border-radius: 2px;
    }

    .timeline-table tbody .baris-kawasan td {
        background: #e9ecef;
        font-weight: 700;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filter = document.getElementById('filterKawasan');

        if (!filter) return;

        function filterTimeline() {
            const kawasanDipilih = filter.value;

            document.querySelectorAll('.timeline-table tbody tr').forEach(function(row) {
                const kawasanBaris = row.dataset.kawasan;

                row.style.display =
                    kawasanBaris === kawasanDipilih ? '' : 'none';
            });
        }

        filter.addEventListener('change', filterTimeline);

        filterTimeline();
    });
</script>
