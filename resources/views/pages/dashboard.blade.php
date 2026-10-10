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


        {{-- <button class="btn-date-picker" type="button" id="date-picker-trigger">
            <i class="bi bi-calendar4-event"></i>
            <span id="selected-date-range">January 12, 2026 - January 23, 2026</span>
            <i class="bi bi-chevron-down ms-1"></i>
        </button> --}}
    </div>
    <!-- END: Dashboard Header Banner -->

    <!-- START: Main Layout Grid (2 Columns: Dashboard + Performance Pane) -->
    <div class="row g-4">

        <!-- TOP AREA: Quick Info Stat Cards Row (Full Width) -->
        <div class="col-12">
            <div class="row g-4">
                <!-- Stat Card 1: Green Alert Banner -->

                <div class="col-md-12">
                    <div class="card alert-green-card">
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

        <!-- LEFT AREA: Primary Dashboard Stats & Tables -->
        <div class="col-xl-12 col-lg-12">

            <!-- START: Details Area (Transactions + Performance Charts) -->
            <div class="row g-4">
                <!-- Column: Revenue Chart (Full Width / Wider) -->

                <!-- Column: Transaction List -->

                <!-- Column: Product Overview Progress -->

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
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="return false;">
                                                <i class="bi bi-file-earmark-text"></i>
                                                Documents ({{ count($documents) }})
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Daftar Dokumen --}}
                            @foreach ($documents as $document)
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
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="progress" role="progressbar" aria-label="Progres {{ $document['name'] }}"
                                        aria-valuenow="{{ max(0, min(100, $document['progress'] ?? 0)) }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-lime-accent"
                                            style="width: {{ max(0, min(100, $document['progress'] ?? 0)) }}%">
                                        </div>
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach


            </div>
            <!-- END: Details Area -->

        </div>
        <div class="row ">
            <div class="col-xl-8 col-lg-8 mb-5">
                <div class="right-panel-wrapper d-flex flex-column gap-4 h-100">

                    <!-- Performance Donut Chart card -->
                    <div class="card flex-grow-1 d-flex flex-column justify-content-between mb-0">
                        <div class="card-header mb-1">
                            <h2 class="card-title">PROJECT SITEPLAN</h2>
                        </div>

                        <div class="row">
                            <img src="{{ asset('assets/images/siteplan.jpeg') }}" alt="">
                        </div>

                        <!-- Custom Legends below the chart -->

                    </div>

                    <!-- Level Up Promotion CTA banner -->

                </div>
            </div>
            <div class="col-lg-4">
                <div class="promo-banner-card">
                    <!-- Inline SVG geometric decoration (Lime green 6-pointed star/asterisk with rounded caps) -->
                    <a href="https://docs.google.com/spreadsheets/d/15XrPzw5rFvcewhdaV6bJvJt9_42DvuHGACRV9ZBs7iQ/edit?usp=sharing"
                        target="_blank" rel="noopener noreferrer">
                        <button class="btn-promo">
                            <i class="bi bi-file-earmark-text-fill me-3"></i>
                            Document Transmittal Log
                        </button>
                    </a>

                    <a href="https://docs.google.com/spreadsheets/d/1f1bXlR_JbZon0BKWKjRMUqGxIdkkIEcHv5PJ2Z8xBBs/edit?usp=sharing"
                        target="_blank" rel="noopener noreferrer">
                        <button class="btn-promo">
                            <i class="bi bi-file-earmark-text-fill me-3"></i>
                            Document Submission Tracker
                        </button>
                    </a>

                    <a href="https://docs.google.com/spreadsheets/d/1ecvWfIpjhryFk_i0VQo0bL3AmySO4Q-R/edit?usp=sharing&ouid=106394931469940063034&rtpof=true&sd=true"
                        target="_blank" rel="noopener noreferrer">
                        <button class="btn-promo">
                            <i class="bi bi-file-earmark-text-fill me-3"></i>
                            Progress Pekerjaan Fisik PT. KAI
                        </button>
                    </a>

                </div>
            </div>
        </div>

        <!-- RIGHT AREA: Performance Details Sidebar Panel -->
        {{-- <div class="col-xl-3 col-lg-4">
            <div class="right-panel-wrapper d-flex flex-column gap-4 h-100">

                <!-- Performance Donut Chart card -->
                <div class="card flex-grow-1 d-flex flex-column justify-content-between mb-0">
                    <div class="card-header mb-1">
                        <h2 class="card-title">Total View Performance</h2>
                    </div>

                    <div id="views-chart"></div>

                    <!-- Custom Legends below the chart -->
                    <div class="chart-legends-container">
                        <div class="chart-legend-item">
                            <span class="legend-dot bg-lime-accent"></span>
                            <span class="text-muted-green">View Count</span>
                        </div>
                        <div class="chart-legend-item">
                            <span class="legend-dot bg-forest-medium"></span>
                            <span class="text-muted-green">Percentage</span>
                        </div>
                        <div class="chart-legend-item">
                            <span class="legend-dot bg-brand-orange"></span>
                            <span class="text-muted-green">Sales</span>
                        </div>
                    </div>
                </div>

                <!-- Level Up Promotion CTA banner -->
                <div class="promo-banner-card">
                    <!-- Inline SVG geometric decoration (Lime green 6-pointed star/asterisk with rounded caps) -->
                    <svg class="promo-banner-bg-shape" viewBox="0 0 100 100" fill="none"
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

                    <h3 class="promo-title">Level up your sales managing to the next level.</h3>
                    <p class="promo-desc">An easy way to manage sales with care and precision.</p>
                    <button class="btn-promo" id="btn-promo-action">Check the updates now</button>
                </div>
            </div>
        </div> --}}
        {{-- disimpan2 --}}
        <!-- END: RIGHT AREA -->

    </div>
@endsection()
