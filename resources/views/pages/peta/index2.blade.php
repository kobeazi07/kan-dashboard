@extends('layouts.index')

@section('konten')
    <div class="container-fluid py-3">

        {{-- =========================
        NAV TAB KAWASAN
    ========================== --}}
        <ul class="nav nav-tabs mb-3" id="mapTabs">

            <li class="nav-item">
                <button class="nav-link active" id="jetty-tab" data-bs-toggle="tab" data-bs-target="#jetty" type="button">

                    Jetty
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" id="powerplant-tab" data-bs-toggle="tab" data-bs-target="#powerplant"
                    type="button">

                    Power Plant
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" id="refinery-tab" data-bs-toggle="tab" data-bs-target="#refinery" type="button">

                    Refinery
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" id="redmud-tab" data-bs-toggle="tab" data-bs-target="#redmud" type="button">

                    Red Mud
                </button>
            </li>

        </ul>


        <div class="tab-content">

            {{-- =====================================================
            TAB JETTY
        ====================================================== --}}
            <div class="tab-pane fade show active" id="jetty">

                <div class="row g-3">

                    {{-- =========================
                    SIDEBAR
                ========================== --}}
                    <div class="col-lg-3">

                        <div class="card h-100 border-0 shadow-sm">

                            <div class="card-body">

                                <h6 class="fw-bold mb-3">
                                    Kawasan Jetty
                                </h6>

                                <div class="sidebar-search mb-3">
                                    <input type="text" id="searchBangunan" class="form-control"
                                        placeholder="Cari kode atau nama bangunan...">
                                </div>

                                <div class="list-group" id="buildingList">

                                    <button type="button"
                                        class="list-group-item list-group-item-action active building-item" data-code="JE-1"
                                        data-name="Substation">
                                        <div class="fw-bold">
                                            JE-1
                                        </div>

                                        <small>
                                            Substation
                                        </small>
                                    </button>


                                    <button type="button" class="list-group-item list-group-item-action building-item"
                                        data-code="JE-2" data-name="Office Building">
                                        <div class="fw-bold">
                                            JE-2
                                        </div>

                                        <small>
                                            Office Building
                                        </small>
                                    </button>


                                    <button type="button" class="list-group-item list-group-item-action building-item"
                                        data-code="JE-3" data-name="Comprehensive Building">
                                        <div class="fw-bold">
                                            JE-3
                                        </div>

                                        <small>
                                            Comprehensive Building
                                        </small>
                                    </button>


                                    <button type="button" class="list-group-item list-group-item-action building-item"
                                        data-code="JE-4" data-name="Sewage Treatment Station">
                                        <div class="fw-bold">
                                            JE-4
                                        </div>

                                        <small>
                                            Sewage Treatment Station
                                        </small>
                                    </button>

                                </div>

                                <!-- Pesan jika data tidak ditemukan -->
                                <div id="emptySearch" class="text-center text-muted mt-3" style="display: none;">
                                    <i class="bi bi-search"></i>
                                    <br>
                                    Bangunan tidak ditemukan
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =========================
                    PETA
                ========================== --}}
                    <div class="col-lg-9">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <h6 class="fw-bold mb-3">
                                    Site Plan Jetty
                                </h6>


                                {{-- ==================================
                                MAP WRAPPER

                                PIN HARUS BERADA DI DALAM DIV INI
                                AGAR MENYATU DENGAN GAMBAR
                            =================================== --}}
                                <div class="map-viewer" id="mapViewer">

                                    <!-- Kontrol Zoom -->
                                    <div class="map-controls">

                                        <button type="button" class="map-control-btn" id="zoomIn" title="Zoom In">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>

                                        <button type="button" class="map-control-btn" id="zoomOut" title="Zoom Out">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>

                                        <button type="button" class="map-control-btn" id="resetZoom" title="Reset">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>

                                    </div>


                                    <!-- AREA YANG AKAN DI ZOOM -->
                                    <div class="map-content" id="mapContent">

                                        <!-- GAMBAR -->
                                        <img src="{{ asset('assets/images/SITEPLANJETTY_A1_1.png') }}" alt="Site Plan Jetty"
                                            class="map-image" draggable="false">


                                        <!-- PIN JE-1 -->
                                        <button type="button" class="map-pin" data-code="JE-1"
                                            style="
                left: 59%;
                top: 40.5%;
            ">
                                            JE-1
                                        </button>


                                        <!-- PIN JE-2 -->
                                        <button type="button" class="map-pin" data-code="JE-2"
                                            style="
                left: 53.5%;
                top: 40.5%;
            ">
                                            JE-2
                                        </button>


                                        <!-- PIN JE-3 -->
                                        <button type="button" class="map-pin" data-code="JE-3"
                                            style="
                left: 45.5%;
                top: 40.5%;
            ">
                                            JE-3
                                        </button>


                                        <!-- PIN JE-4 -->
                                        <button type="button" class="map-pin" data-code="JE-4"
                                            style="
                left: 17%;
                top: 45%;
            ">
                                            JE-4
                                        </button>

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            TAB POWER PLANT
        ====================================================== --}}
            <div class="tab-pane fade" id="powerplant">

                <div class="alert alert-secondary text-center py-5">
                    <h5 class="mb-2">
                        Power Plant
                    </h5>

                    <p class="mb-0">
                        Data belum tersedia.
                    </p>
                </div>

            </div>


            {{-- =====================================================
            TAB REFINERY
        ====================================================== --}}
            <div class="tab-pane fade" id="refinery">

                <div class="alert alert-secondary text-center py-5">
                    <h5 class="mb-2">
                        Refinery
                    </h5>

                    <p class="mb-0">
                        Data belum tersedia.
                    </p>
                </div>

            </div>


            {{-- =====================================================
            TAB RED MUD
        ====================================================== --}}
            <div class="tab-pane fade" id="redmud">

                <div class="alert alert-secondary text-center py-5">
                    <h5 class="mb-2">
                        Red Mud
                    </h5>

                    <p class="mb-0">
                        Data belum tersedia.
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
    MODAL DETAIL BANGUNAN
============================================================= --}}
    <div class="modal fade" id="buildingModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content border-0">

                {{-- HEADER --}}
                <div class="modal-header">

                    <div>

                        <div class="text-primary fw-bold" id="modalBuildingCode">

                            JE-1

                        </div>

                        <h5 class="modal-title" id="modalBuildingName">

                            Substation

                        </h5>

                    </div>


                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body">

                    <h6 class="fw-bold mb-3">
                        Dokumentasi
                    </h6>


                    {{-- LIST FOTO --}}
                    <div class="row g-3" id="documentationList">

                        {{-- Diisi JavaScript --}}

                    </div>


                    {{-- JIKA TIDAK ADA FOTO --}}
                    <div id="noDocumentation" class="text-center py-5">

                        <i class="bi bi-image" style="font-size: 3rem;">
                        </i>

                        <p class="text-muted mt-3 mb-0">
                            Dokumentasi belum tersedia.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
    CSS
============================================================= --}}
    <style>
        /*
                                                                                                        |--------------------------------------------------------------------------
                                                                                                        | MAP WRAPPER
                                                                                                        |--------------------------------------------------------------------------
                                                                                                        |
                                                                                                        | position: relative PENTING.
                                                                                                        |
                       <style>
                    /* AREA YANG TERLIHAT */
        .map-viewer {
            position: relative;
            width: 100%;
            height: 650px;
            overflow: hidden;
            background: #f1f3f5;
            border-radius: 10px;
            cursor: grab;
            user-select: none;
        }

        .map-viewer.dragging {
            cursor: grabbing;
        }


        /* KONTEN GAMBAR + PIN */
        .map-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            transform-origin: 0 0;
            will-change: transform;
        }


        /* GAMBAR */
        .map-image {
            display: block;
            width: 100%;
            height: auto;
            max-width: none;
            pointer-events: none;
        }


        /* PIN */
        .map-pin {
            position: absolute;
            z-index: 10;

            transform: translate(-50%, -50%);

            background: #0d6efd;
            color: #ffffff;

            border: 3px solid #ffffff;
            border-radius: 50%;

            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
            font-weight: 700;

            cursor: pointer;

            box-shadow: 0 3px 10px rgba(0, 0, 0, .25);

            transition: box-shadow .2s;
        }

        .map-pin:hover {
            z-index: 20;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .4);
        }


        /* KONTROL ZOOM */
        .map-controls {
            position: absolute;

            top: 15px;
            right: 15px;

            z-index: 1000;

            display: flex;
            flex-direction: column;

            gap: 8px;
        }

        .map-control-btn {
            width: 42px;
            height: 42px;

            border: none;
            border-radius: 8px;

            background: #ffffff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;

            cursor: pointer;

            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
        }

        .map-control-btn:hover {
            background: #f1f1f1;
        }


        /* RESPONSIVE */
        @media (max-width: 768px) {

            .map-viewer {
                height: 500px;
            }

            .map-pin {
                width: 42px;
                height: 42px;
                font-size: 10px;
            }

        }



        /*
                |--------------------------------------------------------------------------
                | HOVER PIN
                |--------------------------------------------------------------------------
                */

        .map-pin:hover {

            background: #0b5ed7;

            transform:
                translate(-50%, -50%) scale(1.15);

            z-index: 20;
        }


        /*
                |--------------------------------------------------------------------------
                | PIN AKTIF
                |--------------------------------------------------------------------------
                */

        .map-pin.active {

            background: #084298;

            box-shadow:
                0 0 0 5px rgba(13, 110, 253, 0.25),
                0 3px 10px rgba(0, 0, 0, 0.4);
        }


        /*
                |--------------------------------------------------------------------------
                | SIDEBAR
                |--------------------------------------------------------------------------
                */

        .building-item {

            text-align: left;
            padding: 14px 12px;
        }


        .building-item small {

            color: #6c757d;
        }


        .building-item.active small {

            color: rgba(255, 255, 255, 0.85);
        }


        /*
                |--------------------------------------------------------------------------
                | FOTO DOKUMENTASI
                |--------------------------------------------------------------------------
                |
                | Landscape seperti kamera HP
                |
                */

        .documentation-image {

            width: 100%;

            /*
                | Rasio landscape kamera
                */
            aspect-ratio: 16 / 9;

            object-fit: cover;

            border-radius: 8px;
        }
    </style>


    {{-- ============================================================
    JAVASCRIPT
============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /*
            |--------------------------------------------------------------------------
            | DATA BANGUNAN
            |--------------------------------------------------------------------------
            |
            | NANTI DATA INI BISA DIGANTI
            | DENGAN DATA DARI DATABASE LARAVEL.
            |
            */

            const buildings = {

                'JE-1': {

                    code: 'JE-1',

                    name: 'Substation',

                    photos: [
                        /*
                        |--------------------------------------------------------------------------
                        | CONTOH JIKA ADA FOTO
                        |--------------------------------------------------------------------------
                        |
                        | '/assets/images/dokumentasi-je-1-1.jpg',
                        | '/assets/images/dokumentasi-je-1-2.jpg',
                        |
                        */
                    ]

                },


                'JE-2': {

                    code: 'JE-2',

                    name: 'Office Building',

                    photos: []

                },


                'JE-3': {

                    code: 'JE-3',

                    name: 'Comprehensive Building',

                    photos: []

                },


                'JE-4': {

                    code: 'JE-4',

                    name: 'Sewage Treatment Station',

                    photos: []

                }

            };


            /*
            |--------------------------------------------------------------------------
            | ELEMENT MODAL
            |--------------------------------------------------------------------------
            */

            const modalElement =
                document.getElementById('buildingModal');


            const buildingModal =
                new bootstrap.Modal(modalElement);


            const modalBuildingCode =
                document.getElementById('modalBuildingCode');


            const modalBuildingName =
                document.getElementById('modalBuildingName');


            const documentationList =
                document.getElementById('documentationList');


            const noDocumentation =
                document.getElementById('noDocumentation');


            /*
            |--------------------------------------------------------------------------
            | FUNGSI BUKA DETAIL BANGUNAN
            |--------------------------------------------------------------------------
            */

            function openBuilding(code) {

                const building = buildings[code];

                if (!building) {
                    return;
                }


                /*
                | Update kode
                */

                modalBuildingCode.textContent =
                    building.code;


                /*
                | Update nama
                */

                modalBuildingName.textContent =
                    building.name;


                /*
                | Reset dokumentasi sebelumnya
                */

                documentationList.innerHTML = '';


                /*
                |--------------------------------------------------------------------------
                | JIKA TIDAK ADA FOTO
                |--------------------------------------------------------------------------
                */

                if (
                    !building.photos ||
                    building.photos.length === 0
                ) {

                    noDocumentation.style.display =
                        'block';

                } else {

                    noDocumentation.style.display =
                        'none';


                    /*
                    | Tampilkan semua foto
                    */

                    building.photos.forEach(function(photo) {

                        const photoColumn =
                            document.createElement('div');


                        photoColumn.className =
                            'col-md-6';


                        photoColumn.innerHTML = `

                    <img
                        src="${photo}"
                        class="documentation-image"
                        alt="Dokumentasi ${building.name}"
                    >

                `;


                        documentationList.appendChild(
                            photoColumn
                        );

                    });

                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE PIN AKTIF
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('.map-pin')
                    .forEach(function(pin) {

                        pin.classList.remove('active');

                    });


                const activePin =
                    document.querySelector(
                        `.map-pin[data-code="${code}"]`
                    );


                if (activePin) {

                    activePin.classList.add('active');

                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE SIDEBAR AKTIF
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('.building-item')
                    .forEach(function(item) {

                        item.classList.remove('active');

                    });


                const activeSidebar =
                    document.querySelector(
                        `.building-item[data-code="${code}"]`
                    );


                if (activeSidebar) {

                    activeSidebar.classList.add('active');

                }


                /*
                |--------------------------------------------------------------------------
                | BUKA MODAL
                |--------------------------------------------------------------------------
                */

                buildingModal.show();

            }


            /*
            |--------------------------------------------------------------------------
            | CLICK PIN
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.map-pin')
                .forEach(function(pin) {

                    pin.addEventListener(
                        'click',
                        function() {

                            openBuilding(
                                this.dataset.code
                            );

                        }
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | CLICK SIDEBAR
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.building-item')
                .forEach(function(item) {

                    item.addEventListener(
                        'click',
                        function() {

                            openBuilding(
                                this.dataset.code
                            );

                        }
                    );

                });


        });


        // zoom in
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const mapViewer = document.getElementById('mapViewer');
            const mapContent = document.getElementById('mapContent');

            const zoomIn = document.getElementById('zoomIn');
            const zoomOut = document.getElementById('zoomOut');
            const resetZoom = document.getElementById('resetZoom');


            /* =========================
               KONFIGURASI
            ========================= */

            let scale = 1;

            let translateX = 0;
            let translateY = 0;

            const minScale = 1;
            const maxScale = 4;
            const zoomStep = 0.25;


            /* =========================
               UPDATE MAP
            ========================= */

            function updateMap() {

                mapContent.style.transform =
                    `translate(${translateX}px, ${translateY}px) scale(${scale})`;

            }


            /* =========================
               ZOOM IN
            ========================= */

            zoomIn.addEventListener('click', function() {

                if (scale < maxScale) {

                    scale += zoomStep;

                    updateMap();

                }

            });


            /* =========================
               ZOOM OUT
            ========================= */

            zoomOut.addEventListener('click', function() {

                if (scale > minScale) {

                    scale -= zoomStep;

                    if (scale < minScale) {
                        scale = minScale;
                    }

                    updateMap();

                }

            });


            /* =========================
               RESET
            ========================= */

            resetZoom.addEventListener('click', function() {

                scale = 1;

                translateX = 0;
                translateY = 0;

                updateMap();

            });


            /* =========================
               DRAG / GESER
            ========================= */

            let isDragging = false;

            let startX = 0;
            let startY = 0;

            let initialTranslateX = 0;
            let initialTranslateY = 0;


            mapViewer.addEventListener('mousedown', function(event) {

                /* Jangan drag jika klik PIN */
                if (event.target.closest('.map-pin')) {
                    return;
                }

                isDragging = true;

                mapViewer.classList.add('dragging');

                startX = event.clientX;
                startY = event.clientY;

                initialTranslateX = translateX;
                initialTranslateY = translateY;

            });


            document.addEventListener('mousemove', function(event) {

                if (!isDragging) {
                    return;
                }

                const deltaX = event.clientX - startX;
                const deltaY = event.clientY - startY;

                translateX = initialTranslateX + deltaX;
                translateY = initialTranslateY + deltaY;

                updateMap();

            });


            document.addEventListener('mouseup', function() {

                if (!isDragging) {
                    return;
                }

                isDragging = false;

                mapViewer.classList.remove('dragging');

            });


            /* =========================
               SCROLL MOUSE ZOOM
            ========================= */

            mapViewer.addEventListener('wheel', function(event) {

                event.preventDefault();

                if (event.deltaY < 0) {

                    scale += zoomStep;

                } else {

                    scale -= zoomStep;

                }

                scale = Math.max(
                    minScale,
                    Math.min(scale, maxScale)
                );

                updateMap();

            }, {
                passive: false
            });


            /* =========================
               TOUCH SUPPORT
            ========================= */

            mapViewer.addEventListener('touchstart', function(event) {

                if (event.touches.length !== 1) {
                    return;
                }

                const touch = event.touches[0];

                isDragging = true;

                startX = touch.clientX;
                startY = touch.clientY;

                initialTranslateX = translateX;
                initialTranslateY = translateY;

            });


            mapViewer.addEventListener('touchmove', function(event) {

                if (!isDragging || event.touches.length !== 1) {
                    return;
                }

                event.preventDefault();

                const touch = event.touches[0];

                const deltaX = touch.clientX - startX;
                const deltaY = touch.clientY - startY;

                translateX = initialTranslateX + deltaX;
                translateY = initialTranslateY + deltaY;

                updateMap();

            }, {
                passive: false
            });


            mapViewer.addEventListener('touchend', function() {

                isDragging = false;

            });

        });
    </script>
    {{-- search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchBangunan');

            const bangunanItems = document.querySelectorAll(
                '#buildingList .building-item'
            );

            const emptySearch = document.getElementById('emptySearch');

            searchInput.addEventListener('input', function() {

                const keyword = this.value
                    .toLowerCase()
                    .trim();

                let totalVisible = 0;

                bangunanItems.forEach(function(item) {

                    const kode = (item.dataset.code || '')
                        .toLowerCase();

                    const nama = (item.dataset.name || '')
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

                if (totalVisible === 0) {

                    emptySearch.style.display = 'block';

                } else {

                    emptySearch.style.display = 'none';

                }

            });

        });
    </script>
@endsection
