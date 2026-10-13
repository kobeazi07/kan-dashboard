@extends('layouts.index')

@section('konten')
    <div class="page-header">

        <div class="row align-items-center g-2">
            <!-- Dashboard -->
            <div class="col-6">
                <h1 class="dashboard-title">Dashboard Project SLF PT. KAN</h1>
                <p class="dashboard-subtitle">Monitoring progres dokumen proyek</p>
            </div>

            <!-- Gambar -->
            <div class="col-6">
                <div class="row g-2 justify-content-end">

                    <div class="col-lg-3 col-5 ms-3">
                        <img src="{{ asset('assets/images/ptkan.png') }}" alt="Monitoring Proyek"
                            class="img-fluid  dashboard-image">
                    </div>
                    <div class="col-lg-3 col-5">
                        <img src="{{ asset('assets/images/rpnp-dashboardfix.png') }}" alt="Dashboard Project"
                            class="img-fluid  dashboard-image">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END: Dashboard Header Banner -->

    <!-- START: Main Layout Grid (2 Columns: Dashboard + Performance Pane) -->
    <div class="row g-4">

        <!-- TOP AREA: Quick Info Stat Cards Row (Full Width) -->
        <div class="col-12">
            <div class="row g-4">
                <!-- Stat Card 1: Green Alert Banner -->
                <div class="col-xl-12">

                    <div class="card alert-green-card">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative z-index-2">
                                    <span class="alert-green-badge">UPDATE</span>

                                    <div class="alert-green-text">PROJECT INFORMATION</div>

                                    <div class="project-information">
                                        <div class="project-info-row">
                                            <span class="project-info-label">Client</span>
                                            <span class="project-info-separator">:</span>
                                            <span class="project-info-value">
                                                PT. KALIMANTAN ALUMINA NUSANTARA
                                            </span>
                                        </div>

                                        <div class="project-info-row">
                                            <span class="project-info-label">Consultant</span>
                                            <span class="project-info-separator">:</span>
                                            <span class="project-info-value">
                                                PT. RPNP DESAIN HIJAU INDONESIA
                                            </span>
                                        </div>

                                        <div class="project-info-row">
                                            <span class="project-info-label">Project</span>
                                            <span class="project-info-separator">:</span>
                                            <span class="project-info-value">
                                                Sertifikat Laik Fungsi (SLF)
                                            </span>
                                        </div>

                                        <div class="project-info-row">
                                            <span class="project-info-label">Location</span>
                                            <span class="project-info-separator">:</span>
                                            <span class="project-info-value">
                                                Desa Sansat, Kec. Toba, Kab. Sanggau,
                                                Kalimantan Barat
                                            </span>
                                        </div>

                                        <div class="project-info-row">
                                            <span class="project-info-label">Status</span>
                                            <span class="project-info-separator">:</span>
                                            <span class="project-status">On Going</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-5 ms-3">


                                <div class="position-relative z-index-2">


                                    <div class="alert-green-text mt-5">
                                        ESTIMASI PROGRESS PEKERJAAN
                                    </div>

                                    <div class="project-information" id="pekerjaan-fisik-container">
                                        @foreach ($pekerjaanFisik as $item)
                                            @if ($item['nama'] === 'Progress Keseluruhan')
                                                <div class="project-info-row progress-total">
                                                    <span class="project-info-label">
                                                        {{ $item['nama'] }}
                                                    </span>

                                                    <span class="project-info-separator">:</span>

                                                    <span class="project-info-value">
                                                        <span class="progress-total-badge project-sum fw-bold "
                                                            data-pekerjaan-fisik="{{ $item['nama'] }}">
                                                            {{ $item['progress'] }} %
                                                        </span>
                                                    </span>
                                                </div>
                                            @else
                                                <div class="project-info-row">
                                                    <span class="project-info-label">
                                                        {{ $item['nama'] }}
                                                    </span>

                                                    <span class="project-info-separator">:</span>

                                                    <span class="project-info-value"
                                                        data-pekerjaan-fisik="{{ $item['nama'] }}">
                                                        {{ $item['progress'] }} %
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Dekorasi SVG -->
                        <svg class="alert-green-bg-shape" viewBox="0 0 100 100" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g transform="translate(50,50)">
                                <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6"
                                    fill="#B4F105" />
                                <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6"
                                    fill="#B4F105" transform="rotate(60)" />
                                <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6"
                                    fill="#B4F105" transform="rotate(120)" />
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- disimpan --}}
                <!-- Stat Card 2: Net Income -->
                <!-- Stat Card 3: Total Return -->
            </div>
        </div>
        <!-- END: TOP AREA -->
        {{-- tombol dokumen --}}
        {{-- <pre>{{ print_r($pekerjaanFisik, true) }}</pre> --}}
        <div class="col-lg-12">
            <div class="promo-banner-card">
                <div class="row">
                    <div class="col-6">
                        <a href="https://docs.google.com/spreadsheets/d/15XrPzw5rFvcewhdaV6bJvJt9_42DvuHGACRV9ZBs7iQ/edit?usp=sharing"
                            target="_blank" rel="noopener noreferrer">
                            <button class="btn-promo">
                                <i class="bi bi-file-earmark-text-fill me-3"></i>
                                Document Transmittal Log
                            </button>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="https://docs.google.com/spreadsheets/d/1f1bXlR_JbZon0BKWKjRMUqGxIdkkIEcHv5PJ2Z8xBBs/edit?usp=sharing"
                            target="_blank" rel="noopener noreferrer">
                            <button class="btn-promo">
                                <i class="bi bi-file-earmark-text-fill me-3"></i>
                                Document Submission Tracker
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- LEFT AREA: Primary Dashboard Stats & Tables -->
        <div class="col-xl-12 col-lg-12">

            <!-- START: Details Area (Transactions + Performance Charts) -->
            <div class="row g-4">
                @foreach ($data as $division => $documents)
                    <div class="col-md-4 d-flex flex-column">
                        <div class="card h-100 flex-grow-1">

                            {{-- Card Header --}}
                            <div class="card-header">
                                <h2 class="card-title">{{ $division }}</h2>

                                <div class="dropdown">
                                    <button class="card-more-btn" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" aria-label="More Options">
                                        <i class="bi bi-three-dots"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                        @foreach ($documents as $document)
                                            @if (($document['type'] ?? null) === 'additional' && !empty($document['url']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ $document['url'] }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                        {{ $document['label'] ?? $document['name'] }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            {{-- Daftar Dokumen --}}
                            @foreach ($documents as $document)
                                @if (($document['type'] ?? null) !== 'additional')
                                    <div class="progress-container">

                                        {{-- Nama Dokumen dan Persentase --}}
                                        <div class="progress-label-row">
                                            <span class="progress-label">
                                                {{ $document['name'] }}
                                            </span>

                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="progress-value
                                {{ $document['progress'] === null ? 'unavailable' : '' }}">

                                                    @if ($document['progress'] !== null)
                                                        {{ rtrim(rtrim(number_format($document['progress'], 2, '.', ''), '0'), '.') }}%
                                                    @else
                                                        Belum tersedia
                                                    @endif

                                                </span>

                                                {{-- Tombol Dokumen --}}
                                                @if (!empty($document['url']))
                                                    <a href="{{ $document['url'] }}" class="progress-action-btn"
                                                        aria-label="Lihat {{ $document['name'] }}" title="Lihat dokumen"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-file-earmark-text-fill"></i>
                                                    </a>
                                                @endif
                                                @if (!empty($document['download_dokumen']))
                                                    <a href="{{ $document['download_dokumen'] }}"
                                                        class="progress-action-btn" aria-label="Download"
                                                        title="Download dokumen" target="_blank"
                                                        rel="noopener noreferrer">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Progress Bar --}}
                                        <div class="progress" role="progressbar"
                                            aria-label="Progres {{ $document['name'] }}"
                                            aria-valuenow="{{ max(0, min(100, $document['progress'] ?? 0)) }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-lime-accent"
                                                style="width: {{ max(0, min(100, $document['progress'] ?? 0)) }}%">
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
            <!-- END: Details Area -->

        </div>
        <div class="row ">

        </div>
        <div class="row mt-3">
            <h2 class="fw-bold dashboard-title">
                {{ $maps->date ?? '-' }}
            </h2>
        </div>

        <div class="row mt-3">
            {!! $maps->embed ?? '' !!}
        </div>



    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            async function updatePekerjaanFisik() {
                try {
                    const response = await fetch(
                        '{{ route('dashboard.progress-data') }}', {
                            headers: {
                                'Accept': 'application/json'
                            },
                            cache: 'no-store'
                        }
                    );

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data dashboard');
                    }

                    const result = await response.json();
                    const data = result.pekerjaanFisik || [];

                    data.forEach(function(item) {
                        const element = document.querySelector(
                            '[data-pekerjaan-fisik="' + item.nama + '"]'
                        );

                        if (!element) return;

                        if (item.nama === 'Progress Keseluruhan') {
                            const badge = element.querySelector(
                                '.progress-total-badge'
                            );

                            if (badge) {
                                badge.textContent = item.progress + ' %';
                            } else {
                                element.textContent = item.progress + ' %';
                            }
                        } else {
                            element.textContent = item.progress + ' %';
                        }
                    });
                } catch (error) {
                    console.error(
                        'Gagal memperbarui progres pekerjaan fisik:',
                        error
                    );
                }
            }

            // Ambil data terbaru saat halaman dibuka.
            updatePekerjaanFisik();

            // Periksa kembali setiap 15 detik tanpa refresh halaman.
            setInterval(updatePekerjaanFisik, 15000);
        });
    </script>
@endsection()
