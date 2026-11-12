@extends('layouts.index')

@section('konten')
    <div class="container-fluid py-4">

        {{-- ========================= --}}
        {{-- NAV TAB KAWASAN --}}
        {{-- ========================= --}}
        <ul class="nav nav-tabs mb-4" id="kawasanTabs">

            @foreach ($kawasans as $index => $kawasan)
                <li class="nav-item">

                    <button type="button" class="nav-link kawasan-tab {{ $index === 0 ? 'active' : '' }}"
                        data-kawasan="{{ $kawasan->id }}">
                        {{ $kawasan->nama }}
                    </button>

                </li>
            @endforeach

        </ul>


        <div class="row">

            {{-- ================================= --}}
            {{-- SIDEBAR BANGUNAN --}}
            {{-- ================================= --}}
            <div class="col-lg-3 mb-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        {{-- SEARCH --}}
                        <div class="sidebar-search mb-3">

                            <input type="text" id="searchBangunan" class="form-control"
                                placeholder="Cari kode atau nama bangunan...">

                        </div>


                        {{-- ========================= --}}
                        {{-- LIST BANGUNAN --}}
                        {{-- ========================= --}}
                        @foreach ($kawasans as $index => $kawasan)
                            <div class="bangunan-list-kawasan {{ $index !== 0 ? 'd-none' : '' }}"
                                data-kawasan="{{ $kawasan->id }}">

                                <div class="list-group">

                                    @forelse ($kawasan->bangunan as $bangunan)
                                        <button type="button" class="list-group-item list-group-item-action bangunan-item"
                                            data-id="{{ $bangunan->id }}" data-kawasan="{{ $kawasan->id }}"
                                            data-kode="{{ $bangunan->kode }}" data-nama="{{ $bangunan->nama }}"
                                            data-deskripsi="{{ $bangunan->deskripsi }}"
                                            data-x="{{ $bangunan->position_x }}" data-y="{{ $bangunan->position_y }}">

                                            <div class="fw-bold">
                                                {{ $bangunan->kode }}
                                            </div>

                                            <small>
                                                {{ $bangunan->nama }}
                                            </small>

                                        </button>

                                    @empty

                                        <div class="text-muted text-center py-3">

                                            Data bangunan belum tersedia

                                        </div>
                                    @endforelse

                                </div>

                            </div>
                        @endforeach


                        {{-- SEARCH EMPTY --}}
                        <div id="emptySearch" class="text-center text-muted py-3 d-none">
                            Bangunan tidak ditemukan
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================= --}}
            {{-- PETA --}}
            {{-- ================================= --}}
            <div class="col-lg-9">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h6 class="fw-bold mb-0" id="mapTitle">
                                @if ($kawasans->count())
                                    {{ $kawasans->first()->nama }}
                                @endif
                            </h6>


                            {{-- CONTROL ZOOM --}}
                            <div class="map-controls">

                                <button type="button" class="btn btn-primary" id="zoomIn" title="Zoom In">
                                    <i class="bi bi-plus-lg"></i>
                                </button>


                                <button type="button" class="btn btn-primary" id="zoomOut" title="Zoom Out">
                                    <i class="bi bi-dash-lg"></i>
                                </button>


                                <button type="button" class="btn btn-secondary" id="resetMap" title="Reset Peta">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>

                            </div>

                        </div>


                        {{-- ========================= --}}
                        {{-- CONTAINER PETA --}}
                        {{-- ========================= --}}
                        <div class="map-viewport" id="mapViewport">

                            <div class="map-stage" id="mapStage">


                                {{-- ========================= --}}
                                {{-- LOOP SETIAP KAWASAN --}}
                                {{-- ========================= --}}
                                @foreach ($kawasans as $index => $kawasan)
                                    <div class="map-kawasan {{ $index !== 0 ? 'd-none' : '' }}"
                                        data-kawasan="{{ $kawasan->id }}">


                                        {{-- GAMBAR KAWASAN --}}
                                        @if ($kawasan->gambar)
                                            <img src="{{ asset($kawasan->gambar) }}" alt="{{ $kawasan->nama }}"
                                                class="map-image" draggable="false">
                                        @else
                                            <div class="map-empty">

                                                Gambar kawasan belum tersedia

                                            </div>
                                        @endif


                                        {{-- ========================= --}}
                                        {{-- PIN BANGUNAN --}}
                                        {{-- ========================= --}}
                                        @foreach ($kawasan->bangunan as $bangunan)
                                            @if ($bangunan->position_x !== null && $bangunan->position_y !== null)
                                                <button type="button" class="map-pin" data-id="{{ $bangunan->id }}"
                                                    data-kawasan="{{ $kawasan->id }}" data-kode="{{ $bangunan->kode }}"
                                                    data-nama="{{ $bangunan->nama }}"
                                                    data-deskripsi="{{ $bangunan->deskripsi }}"
                                                    style="
                                                    left: {{ $bangunan->position_x }}%;
                                                    top: {{ $bangunan->position_y }}%;
                                                ">
                                                    {{ last(explode('-', $bangunan->kode)) }}
                                                </button>
                                            @endif
                                        @endforeach

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================= --}}
    {{-- MODAL DETAIL BANGUNAN --}}
    {{-- ================================= --}}
    <div class="modal fade" id="bangunanModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title" id="modalNama">
                            -
                        </h5>

                        <small class="text-muted" id="modalKode">
                            -
                        </small>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <div id="modalDeskripsi">

                        -

                    </div>
                    <div class="col-12">

                        {{-- <h6 class="fw-bold mb-3">
                            Dokumentasi
                        </h6> --}}


                        <div class="row g-3" id="documentationContainer">
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <style>
        /* ================================= */
        /* MAP */
        /* ================================= */

        .map-viewport {
            position: relative;

            width: 100%;
            height: 650px;

            overflow: hidden;

            background: #f1f3f5;

            border-radius: 10px;

            cursor: grab;

            user-select: none;
        }


        .map-viewport.dragging {
            cursor: grabbing;
        }


        /* =========================================
                                                                                                                                                                                                   MAP CONTENT

                                                                                                                                                                                                   Semua isi peta berada di sini.
                                                                                                                                                                                                   Gambar + pin akan ikut bergerak bersama.
                                                                                                                                                                                                ========================================= */

        .map-content {
            position: absolute;

            top: 0;
            left: 0;

            transform-origin: 0 0;

            will-change: transform;
        }


        /* =========================================
                                                                                                                                                                                                   IMAGE
                                                                                                                                                                                                ========================================= */

        .map-image {
            display: block;

            width: 100%;

            height: auto;

            max-width: none;

            pointer-events: none;
        }


        /* =========================================
                                                                                                                                                                                                   PIN
                                                                                                                                                                                                ========================================= */

        .map-pin {
            position: absolute;
            transform: translate(-50%, -50%);
            z-index: 10;

            width: 32px;
            height: 32px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            border: 2px solid #ffffff;
            border-radius: 50%;

            background: #0d6efd;
            color: #ffffff;

            font-size: 13px;
            font-weight: 700;

            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
            cursor: pointer;
            white-space: nowrap;

            transition:
                transform 0.2s ease,
                width 0.2s ease,
                height 0.2s ease,
                background 0.2s ease;
        }

        .map-pin:hover {
            transform: translate(-50%, -50%) scale(1.1);
        }


        /* PIN AKTIF */
        .map-pin.active-pin {
            background: #dc3545;

            width: 52px;
            height: 52px;

            z-index: 999;

            transform: translate(-50%, -50%) scale(1.15);
        }

        /* =========================================
                                                                                                                                                                                                   SIDEBAR
                                                                                                                                                                                                ========================================= */

        .bangunan-item {

            text-align: left;

            border-left: 4px solid transparent;

        }


        .bangunan-item.active {

            border-left-color: #0d6efd;

        }


        /* =========================================
                                                                                                                                                                                                   DOKUMENTASI

                                                                                                                                                                                                   Landscape seperti kamera HP
                                                                                                                                                                                                ========================================= */

        .documentation-image {

            width: 100%;

            aspect-ratio: 16 / 9;

            object-fit: cover;

            border-radius: 8px;

        }


        /* ================================= */
        /* RESPONSIVE */
        /* ================================= */

        @media (max-width: 991px) {

            .map-viewport {

                height: 500px;

            }

        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* =========================================
               ELEMENT
            ========================================= */

            const kawasanTabs = document.querySelectorAll('.kawasan-tab');

            const bangunanLists = document.querySelectorAll(
                '.bangunan-list-kawasan'
            );

            const mapKawasans = document.querySelectorAll(
                '.map-kawasan'
            );

            const mapTitle = document.getElementById('mapTitle');

            const searchInput = document.getElementById(
                'searchBangunan'
            );

            const emptySearch = document.getElementById(
                'emptySearch'
            );


            /* =========================================
               ZOOM & PAN
            ========================================= */

            const mapViewport = document.getElementById(
                'mapViewport'
            );

            const mapStage = document.getElementById(
                'mapStage'
            );

            const zoomIn = document.getElementById(
                'zoomIn'
            );

            const zoomOut = document.getElementById(
                'zoomOut'
            );

            const resetMap = document.getElementById(
                'resetMap'
            );


            let scale = 1;

            let translateX = 0;

            let translateY = 0;

            let isDragging = false;

            let startX = 0;

            let startY = 0;


            function updateTransform() {

                mapStage.style.transformOrigin = 'top left';

                mapStage.style.transform =
                    `translate(${translateX}px, ${translateY}px) scale(${scale})`;
            }

            // function updateTransform() {

            //     if (!mapStage) return;

            //     mapStage.style.transform =
            //         translate($ {
            //                 translateX
            //             }
            //             px, $ {
            //                 translateY
            //             }
            //             px) scale($ {
            //             scale
            //         });
            // }


            /* =========================================
               ZOOM IN
            ========================================= */

            zoomIn.addEventListener('click', function() {

                scale += 0.2;

                if (scale > 3) {
                    scale = 3;
                }

                updateTransform();

            });


            /* =========================================
               ZOOM OUT
            ========================================= */

            zoomOut.addEventListener('click', function() {

                scale -= 0.2;

                if (scale < 0.5) {
                    scale = 0.5;
                }

                updateTransform();

            });


            /* =========================================
               RESET MAP
            ========================================= */

            resetMap.addEventListener('click', function() {

                scale = 1;

                translateX = 0;

                translateY = 0;

                updateTransform();

            });


            /* =========================================
               DRAG START
            ========================================= */

            mapViewport.addEventListener('mousedown', function(e) {

                if (e.target.classList.contains('map-pin')) {
                    return;
                }

                isDragging = true;

                startX = e.clientX - translateX;

                startY = e.clientY - translateY;

                mapViewport.style.cursor = 'grabbing';

            });


            /* =========================================
               DRAG MOVE
            ========================================= */

            window.addEventListener('mousemove', function(e) {

                if (!isDragging) return;

                translateX = e.clientX - startX;

                translateY = e.clientY - startY;

                updateTransform();

            });


            /* =========================================
               DRAG END
            ========================================= */

            window.addEventListener('mouseup', function() {

                isDragging = false;

                mapViewport.style.cursor = 'grab';

            });


            /* =========================================
               PILIH KAWASAN
            ========================================= */

            kawasanTabs.forEach(function(tab) {

                tab.addEventListener('click', function() {

                    const kawasanId = this.dataset.kawasan;

                    const kawasanNama = this.textContent.trim();


                    /* ACTIVE TAB */

                    kawasanTabs.forEach(function(item) {

                        item.classList.remove('active');

                    });

                    this.classList.add('active');


                    /* SIDEBAR */

                    bangunanLists.forEach(function(list) {

                        if (
                            String(list.dataset.kawasan) ===
                            String(kawasanId)
                        ) {

                            list.classList.remove('d-none');

                        } else {

                            list.classList.add('d-none');

                        }

                    });


                    /* MAP */

                    mapKawasans.forEach(function(map) {

                        if (
                            String(map.dataset.kawasan) ===
                            String(kawasanId)
                        ) {

                            map.classList.remove('d-none');

                        } else {

                            map.classList.add('d-none');

                        }

                    });


                    /* TITLE */

                    mapTitle.textContent = kawasanNama;


                    /* RESET SEARCH */

                    searchInput.value = '';


                    document
                        .querySelectorAll('.bangunan-item')
                        .forEach(function(item) {

                            item.style.display = '';

                        });


                    emptySearch.classList.add('d-none');


                    /* RESET MAP */

                    scale = 1;

                    translateX = 0;

                    translateY = 0;

                    updateTransform();

                });

            });


            /* =========================================
               SEARCH BANGUNAN
            ========================================= */

            searchInput.addEventListener('input', function() {

                const keyword = this.value
                    .toLowerCase()
                    .trim();


                const activeList = document.querySelector(
                    '.bangunan-list-kawasan:not(.d-none)'
                );


                if (!activeList) {
                    return;
                }


                const bangunanItems = activeList.querySelectorAll(
                    '.bangunan-item'
                );


                let totalVisible = 0;


                bangunanItems.forEach(function(item) {

                    const kode =
                        (item.dataset.kode || '')
                        .toLowerCase();

                    const nama =
                        (item.dataset.nama || '')
                        .toLowerCase();


                    const cocok =
                        kode.includes(keyword) ||
                        nama.includes(keyword);


                    if (cocok) {

                        item.style.display = '';

                        totalVisible++;

                    } else {

                        item.style.display = 'none';

                    }

                });


                if (
                    totalVisible === 0 &&
                    keyword !== ''
                ) {

                    emptySearch.classList.remove('d-none');

                } else {

                    emptySearch.classList.add('d-none');

                }

            });


            /* =========================================
               KLIK SIDEBAR
               FOCUS KE PIN
            ========================================= */

            document
                .querySelectorAll('.bangunan-item')
                .forEach(function(item) {

                    item.addEventListener('click', function() {

                        const kawasanId =
                            this.dataset.kawasan;

                        const kode =
                            this.dataset.kode;

                        const nama =
                            this.dataset.nama;

                        const x =
                            parseFloat(this.dataset.x);

                        const y =
                            parseFloat(this.dataset.y);


                        /* ACTIVE SIDEBAR */
                        /* ACTIVE SIDEBAR */

                        document.querySelectorAll('.bangunan-item')
                            .forEach(function(el) {

                                el.classList.remove('active');

                            });

                        this.classList.add('active');
                        /* CENTER KE PIN */

                        centerPin(
                            kawasanId,
                            kode,
                            x,
                            y
                        );

                        function centerPin(kawasanId, kode, x, y) {

                            /* =========================
                               CARI PIN
                            ========================= */

                            const pin = document.querySelector(
                                `.map-pin[data-kawasan="${kawasanId}"][data-kode="${kode}"]`
                            );

                            if (!pin) {

                                console.log(
                                    'Pin tidak ditemukan:',
                                    kode
                                );

                                return;
                            }


                            /* =========================
                               ZOOM
                            ========================= */

                            scale = 1.8;


                            /* =========================
                               AMBIL UKURAN VIEWPORT
                            ========================= */

                            const viewportWidth =
                                mapViewport.clientWidth;

                            const viewportHeight =
                                mapViewport.clientHeight;


                            /* =========================
                               AMBIL GAMBAR KAWASAN
                            ========================= */

                            const mapKawasan =
                                pin.closest('.map-kawasan');

                            if (!mapKawasan) {

                                console.log('Map kawasan tidak ditemukan');

                                return;
                            }


                            const image =
                                mapKawasan.querySelector('.map-image');

                            if (!image) {

                                console.log('Map image tidak ditemukan');

                                return;
                            }


                            const imageWidth =
                                image.offsetWidth;

                            const imageHeight =
                                image.offsetHeight;


                            /* =========================
                               POSISI ASLI PIN
                            ========================= */



                            const pinX = pin.offsetLeft;

                            const pinY = pin.offsetTop;

                            /* =========================
                               CENTER PIN
                            ========================= */

                            translateX =
                                (viewportWidth / 2) -
                                (pinX * scale);

                            translateY =
                                (viewportHeight / 2) -
                                (pinY * scale);

                            /* =========================
                               UPDATE MAP
                            ========================= */

                            updateTransform();
                            /* HAPUS ACTIVE PIN SEBELUMNYA */
                            document.querySelectorAll('.map-pin').forEach(function(item) {
                                item.classList.remove('active-pin');
                            });


                            /* BESARKAN PIN TERPILIH */
                            pin.classList.add('active-pin');
                        }

                        /*
                         * Highlight pin
                         */

                        document
                            .querySelectorAll('.map-pin')
                            .forEach(function(item) {

                                item.style.zIndex = '';

                            });


                        pin.style.zIndex = '999';

                    });

                });


            /* =========================================
               MODAL DETAIL
            ========================================= */

            const modalElement =
                document.getElementById('bangunanModal');

            const bangunanModal =
                new bootstrap.Modal(modalElement);


            document
                .querySelectorAll('.map-pin')
                .forEach(function(pin) {

                    pin.addEventListener('click', function(e) {

                        e.stopPropagation();


                        const kode =
                            this.dataset.kode;

                        const nama =
                            this.dataset.nama;

                        const deskripsi =
                            this.dataset.deskripsi;


                        document.getElementById(
                            'modalKode'
                        ).textContent = kode;


                        document.getElementById(
                            'modalNama'
                        ).textContent = nama;


                        document.getElementById(
                                'modalDeskripsi'
                            ).textContent =
                            deskripsi ||
                            'Informasi bangunan belum tersedia.';


                        bangunanModal.show();

                    });

                });


        });
    </script>
@endsection
