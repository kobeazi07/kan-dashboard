<div class="card shadow-sm mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Timeline Progress Pekerjaan</h5>

        <select id="filterKawasan" class="form-select w-auto">
            <option value="KAWASAN JETTY">Kawasan Jetty</option>
            <option value="KAWASAN POWER PLANT">Kawasan Power Plant</option>
            <option value="KAWASAN REFINERY">Kawasan Refinery</option>
            <option value="KAWASAN RED MUD">Kawasan Red Mud</option>
        </select>
    </div>

    <div class="card-body">
        <div class="table-responsive timeline-wrapper">
            <table class="table table-bordered table-sm timeline-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="sticky-col col-no">No.</th>
                        <th rowspan="2" class="sticky-col col-uraian">
                            Uraian Pekerjaan
                        </th>
                        <th rowspan="2">Volume</th>
                        <th rowspan="2">Satuan</th>
                        <th rowspan="2">Progress</th>

                        @foreach (collect($timeline['periode'])->groupBy('bulan') as $bulan => $minggu)
                            <th colspan="{{ $minggu->count() }}" class="text-center">
                                {{ $bulan }}
                            </th>
                        @endforeach
                    </tr>

                    <tr>
                        @foreach ($timeline['periode'] as $periode)
                            <th class="text-center">
                                {{ $periode['minggu'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($timeline['data'] as $item)
                        @if ($item['jenis'] === 'kawasan')
                            <tr class="baris-kawasan" data-kawasan="{{ $item['nama'] }}">
                                <td colspan="{{ 5 + count($timeline['periode']) }}" class="fw-bold bg-light">
                                    {{ $item['nama'] }}
                                </td>
                            </tr>
                        @elseif ($item['jenis'] === 'subbagian')
                            <tr class="baris-subbagian" data-kawasan="{{ $item['kawasan'] }}">
                                <td colspan="{{ 5 + count($timeline['periode']) }}" class="fw-semibold">
                                    {{ $item['nama'] }}
                                </td>
                            </tr>
                        @elseif ($item['jenis'] === 'pekerjaan')
                            <tr class="baris-pekerjaan" data-kawasan="{{ $item['kawasan'] }}">
                                <td class="sticky-col col-no">
                                    {{ $item['no'] }}
                                </td>

                                <td class="sticky-col col-uraian">
                                    {{ $item['uraian'] }}
                                </td>

                                <td class="text-end">
                                    {{ $item['volume'] }}
                                </td>

                                <td>
                                    {{ $item['satuan'] }}
                                </td>

                                <td class="text-end">
                                    {{ $item['progress'] !== null ? $item['progress'] . '%' : '-' }}
                                </td>

                                @foreach ($item['minggu'] as $nilai)
                                    <td class="text-center">
                                        {{ $nilai ?? '' }}
                                    </td>
                                @endforeach
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filter = document.getElementById('filterKawasan');

        if (!filter) return;

        function filterTimeline() {
            const kawasanDipilih = filter.value;
            const rows = document.querySelectorAll(
                '.timeline-table tbody tr'
            );

            let kawasanAktif = null;

            rows.forEach(function(row) {
                if (row.classList.contains('baris-kawasan')) {
                    kawasanAktif = row.dataset.kawasan;

                    row.style.display =
                        kawasanAktif === kawasanDipilih ? '' : 'none';

                    return;
                }

                const kawasanBaris = row.dataset.kawasan;

                row.style.display =
                    kawasanBaris === kawasanDipilih ? '' : 'none';
            });
        }

        filter.addEventListener('change', filterTimeline);

        filterTimeline();
    });
</script>
