@extends('layouts.index')

@section('konten')
    <div class="container-fluid py-4">
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
            <div class="tab-pane fade show active" id="jetty">

                <div class="row g-3">
                    {{-- ============================================
             SIDEBAR
        ============================================ --}}
                    <div class="col-lg-3 mb-3">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <h6 class="fw-bold mb-3">
                                    Kawasan Jetty
                                </h6>


                                {{-- SEARCH --}}
                                <div class="sidebar-search mb-3">

                                    <input type="text" id="searchBangunan" class="form-control"
                                        placeholder="Cari kode atau nama bangunan...">

                                </div>


                                {{-- LIST BANGUNAN --}}
                                <div class="list-group" id="buildingList">

                                    {{-- JE-1 --}}
                                    <button type="button"
                                        class="list-group-item list-group-item-action building-item active" data-code="JE-1"
                                        data-name="Substation">

                                        <div class="fw-bold">
                                            JE-1
                                        </div>

                                        <small>
                                            Substation
                                        </small>

                                    </button>


                                    {{-- JE-2 --}}
                                    <button type="button" class="list-group-item list-group-item-action building-item"
                                        data-code="JE-2" data-name="Office Building">

                                        <div class="fw-bold">
                                            JE-2
                                        </div>

                                        <small>
                                            Office Building
                                        </small>

                                    </button>


                                    {{-- JE-3 --}}
                                    <button type="button" class="list-group-item list-group-item-action building-item"
                                        data-code="JE-3" data-name="Comprehensive Building">

                                        <div class="fw-bold">
                                            JE-3
                                        </div>

                                        <small>
                                            Comprehensive Building
                                        </small>

                                    </button>


                                    {{-- JE-4 --}}
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


                                {{-- EMPTY SEARCH --}}
                                <div id="emptySearch" class="text-center text-muted py-3" style="display: none;">

                                    Data bangunan tidak ditemukan.

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- ============================================
             MAP
        ============================================ --}}
                    <div class="col-lg-9">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <h6 class="fw-bold mb-0">
                                        Site Plan Jetty
                                    </h6>


                                    {{-- MAP CONTROLS --}}
                                    <div class="btn-group">

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



                                {{-- =====================================
                         MAP VIEWPORT
                    ====================================== --}}
                                <div class="map-viewport" id="mapViewport">

                                    {{-- =====================================
                             MAP CONTENT
                             Gambar dan pin berada di sini.
                             Jadi ketika zoom/pan, semuanya
                             bergerak bersama.
                        ====================================== --}}
                                    <div class="map-content" id="mapContent">

                                        {{-- GAMBAR --}}
                                        <img src="{{ asset('assets/images/SITEPLANJETTY_A1_1.png') }}" alt="Site Plan Jetty"
                                            class="map-image" id="mapImage" draggable="false">


                                        {{-- PIN JE-1 --}}
                                        <button type="button" class="map-pin" data-code="JE-1" data-name="Substation"
                                            style="
                                    left: 59%;
                                    top: 40.5%;
                                ">
                                            JE-1
                                        </button>


                                        {{-- PIN JE-2 --}}
                                        <button type="button" class="map-pin" data-code="JE-2"
                                            data-name="Office Building"
                                            style="
                                    left: 53.5%;
                                    top: 40.5%;
                                ">
                                            JE-2
                                        </button>


                                        {{-- PIN JE-3 --}}
                                        <button type="button" class="map-pin" data-code="JE-3"
                                            data-name="Comprehensive Building"
                                            style="
                                    left: 45.5%;
                                    top: 40.5%;
                                ">
                                            JE-3
                                        </button>


                                        {{-- PIN JE-4 --}}
                                        <button type="button" class="map-pin" data-code="JE-4"
                                            data-name="Sewage Treatment Station"
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

            {{-- akhir jetty --}}

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



        {{-- ============================================
     MODAL DETAIL BANGUNAN
============================================ --}}
        <div class="modal fade" id="buildingModal" tabindex="-1" aria-labelledby="buildingModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-scrollable">

                <div class="modal-content">

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title fw-bold" id="buildingModalLabel">
                                Detail Bangunan
                            </h5>

                            <small id="modalBuildingCode" class="text-muted">
                            </small>

                        </div>


                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="row">

                            {{-- INFO --}}
                            <div class="col-12 mb-4">

                                <div class="card bg-light border-0">

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-6">

                                                <small class="text-muted">
                                                    Kode Bangunan
                                                </small>

                                                <h6 id="modalCode">
                                                </h6>

                                            </div>


                                            <div class="col-md-6">

                                                <small class="text-muted">
                                                    Nama Bangunan
                                                </small>

                                                <h6 id="modalName">
                                                </h6>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- DOKUMENTASI --}}
                            <div class="col-12">

                                <h6 class="fw-bold mb-3">
                                    Dokumentasi
                                </h6>


                                <div class="row g-3" id="documentationContainer">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endsection



    <style>
        /* =========================================
           MAP
        ========================================= */

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

            border: 2px solid #ffffff;

            border-radius: 50px;

            background: #0d6efd;

            color: #ffffff;

            font-size: 13px;

            font-weight: 700;

            padding: 7px 12px;

            min-width: 52px;

            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);

            cursor: pointer;

            white-space: nowrap;

            transition: transform 0.2s ease;
        }


        .map-pin:hover {

            transform:
                translate(-50%, -50%) scale(1.1);

        }


        .map-pin.active-pin {

            background: #dc3545;

            transform:
                translate(-50%, -50%) scale(1.15);

        }


        /* =========================================
           SIDEBAR
        ========================================= */

        .building-item {

            text-align: left;

            border-left: 4px solid transparent;

        }


        .building-item.active {

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
    </style>



    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /* =========================================
               ELEMENT
            ========================================= */

            const viewport =
                document.getElementById('mapViewport');

            const mapContent =
                document.getElementById('mapContent');

            const mapImage =
                document.getElementById('mapImage');


            /* =========================================
               MAP STATE
            ========================================= */

            let scale = 1;

            let translateX = 0;

            let translateY = 0;


            const minScale = 0.5;

            const maxScale = 4;

            const zoomStep = 0.2;


            /* =========================================
               UPDATE TRANSFORM
            ========================================= */

            function updateTransform() {

                mapContent.style.transform =
                    `translate(${translateX}px, ${translateY}px) scale(${scale})`;

            }


            /* =========================================
               RESET MAP
            ========================================= */

            function resetMap() {

                scale = 1;

                translateX = 0;

                translateY = 0;

                updateTransform();

            }


            /* =========================================
               ZOOM
            ========================================= */

            function zoomMap(newScale, centerX, centerY) {

                newScale =
                    Math.min(
                        Math.max(newScale, minScale),
                        maxScale
                    );


                const rect =
                    viewport.getBoundingClientRect();


                const x =
                    centerX ?? rect.width / 2;

                const y =
                    centerY ?? rect.height / 2;


                /*
                 * Posisi mouse sebelum zoom
                 */

                const mapX =
                    (x - translateX) / scale;

                const mapY =
                    (y - translateY) / scale;


                /*
                 * Scale baru
                 */

                scale = newScale;


                /*
                 * Hitung translate agar zoom
                 * tetap fokus ke titik yang sama
                 */

                translateX =
                    x - (mapX * scale);

                translateY =
                    y - (mapY * scale);


                updateTransform();

            }


            /* =========================================
               ZOOM BUTTON
            ========================================= */

            document
                .getElementById('zoomIn')
                .addEventListener('click', function() {

                    zoomMap(
                        scale + zoomStep
                    );

                });


            document
                .getElementById('zoomOut')
                .addEventListener('click', function() {

                    zoomMap(
                        scale - zoomStep
                    );

                });


            document
                .getElementById('resetMap')
                .addEventListener('click', function() {

                    resetMap();

                });


            /* =========================================
               MOUSE WHEEL ZOOM
            ========================================= */

            viewport.addEventListener(
                'wheel',
                function(event) {

                    event.preventDefault();


                    const rect =
                        viewport.getBoundingClientRect();


                    const mouseX =
                        event.clientX - rect.left;

                    const mouseY =
                        event.clientY - rect.top;


                    const direction =
                        event.deltaY > 0 ?
                        -zoomStep :
                        zoomStep;


                    zoomMap(
                        scale + direction,
                        mouseX,
                        mouseY
                    );

                }, {
                    passive: false
                }
            );


            /* =========================================
               DRAG / PAN
            ========================================= */

            let isDragging = false;

            let startX = 0;

            let startY = 0;

            let initialTranslateX = 0;

            let initialTranslateY = 0;


            viewport.addEventListener(
                'mousedown',
                function(event) {

                    /*
                     * Jangan drag jika klik PIN
                     */

                    if (
                        event.target.classList.contains('map-pin')
                    ) {
                        return;
                    }


                    isDragging = true;


                    viewport.classList.add(
                        'dragging'
                    );


                    startX =
                        event.clientX;

                    startY =
                        event.clientY;


                    initialTranslateX =
                        translateX;

                    initialTranslateY =
                        translateY;

                }
            );


            window.addEventListener(
                'mousemove',
                function(event) {

                    if (!isDragging) {
                        return;
                    }


                    const deltaX =
                        event.clientX - startX;

                    const deltaY =
                        event.clientY - startY;


                    translateX =
                        initialTranslateX + deltaX;

                    translateY =
                        initialTranslateY + deltaY;


                    updateTransform();

                }
            );


            window.addEventListener(
                'mouseup',
                function() {

                    isDragging = false;


                    viewport.classList.remove(
                        'dragging'
                    );

                }
            );


            /* =========================================
               FOCUS BUILDING

               Saat sidebar diklik,
               peta otomatis menuju pin.
            ========================================= */

            function focusBuilding(code) {

                const pin =
                    document.querySelector(
                        `.map-pin[data-code="${code}"]`
                    );


                if (!pin) {
                    return;
                }


                /*
                 * Ambil ukuran viewport
                 */

                const viewportWidth =
                    viewport.clientWidth;

                const viewportHeight =
                    viewport.clientHeight;


                /*
                 * Posisi pin pada MAP ASLI.
                 *
                 * offsetLeft dan offsetTop tidak
                 * terpengaruh oleh zoom.
                 */

                const pinX =
                    pin.offsetLeft;

                const pinY =
                    pin.offsetTop;


                /*
                 * Zoom otomatis minimal 1.5
                 *
                 * Kalau user sudah zoom lebih besar,
                 * zoom user tidak dikurangi.
                 */

                const targetScale =
                    Math.max(scale, 1.5);


                scale =
                    targetScale;


                /*
                 * Geser peta agar PIN berada
                 * tepat di tengah viewport.
                 */

                translateX =
                    (viewportWidth / 2) -
                    (pinX * scale);


                translateY =
                    (viewportHeight / 2) -
                    (pinY * scale);


                updateTransform();


                /*
                 * Reset active pin
                 */

                document
                    .querySelectorAll('.map-pin')
                    .forEach(function(item) {

                        item.classList.remove(
                            'active-pin'
                        );

                    });


                pin.classList.add(
                    'active-pin'
                );

            }


            /* =========================================
               SIDEBAR CLICK
            ========================================= */

            document
                .querySelectorAll('.building-item')
                .forEach(function(item) {

                    item.addEventListener(
                        'click',
                        function() {

                            const code =
                                this.dataset.code;


                            /*
                             * Fokus peta ke bangunan
                             */

                            focusBuilding(code);


                            /*
                             * Active sidebar
                             */

                            document
                                .querySelectorAll('.building-item')
                                .forEach(function(button) {

                                    button.classList.remove(
                                        'active'
                                    );

                                });


                            this.classList.add(
                                'active'
                            );

                        }
                    );

                });


            /* =========================================
               PIN CLICK
            ========================================= */

            document
                .querySelectorAll('.map-pin')
                .forEach(function(pin) {

                    pin.addEventListener(
                        'click',
                        function(event) {

                            event.stopPropagation();


                            const code =
                                this.dataset.code;

                            const name =
                                this.dataset.name;


                            /*
                             * Active pin
                             */

                            document
                                .querySelectorAll('.map-pin')
                                .forEach(function(item) {

                                    item.classList.remove(
                                        'active-pin'
                                    );

                                });


                            this.classList.add(
                                'active-pin'
                            );


                            /*
                             * Active sidebar
                             */

                            document
                                .querySelectorAll('.building-item')
                                .forEach(function(item) {

                                    item.classList.remove(
                                        'active'
                                    );


                                    if (
                                        item.dataset.code === code
                                    ) {

                                        item.classList.add(
                                            'active'
                                        );

                                    }

                                });


                            /*
                             * Isi modal
                             */

                            showBuildingModal(
                                code,
                                name
                            );

                        }
                    );

                });


            /* =========================================
               MODAL DETAIL
            ========================================= */

            function showBuildingModal(code, name) {

                document
                    .getElementById('buildingModalLabel')
                    .textContent =
                    name;


                document
                    .getElementById('modalBuildingCode')
                    .textContent =
                    code;


                document
                    .getElementById('modalCode')
                    .textContent =
                    code;


                document
                    .getElementById('modalName')
                    .textContent =
                    name;


                /*
                 * Dokumentasi sementara.
                 *
                 * Nantinya dapat diganti dari database.
                 */

                const documentationContainer =
                    document.getElementById(
                        'documentationContainer'
                    );


                documentationContainer.innerHTML = `

            <div class="col-12">

                <div
                    class="
                        border
                        rounded
                        p-4
                        text-center
                        text-muted
                    "
                >

                    <i
                        class="
                            bi
                            bi-image
                            fs-1
                            d-block
                            mb-2
                        "
                    >
                    </i>

                    Dokumentasi belum tersedia.

                </div>

            </div>

        `;


                const modalElement =
                    document.getElementById(
                        'buildingModal'
                    );


                const modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );


                modal.show();

            }


            /* =========================================
               SEARCH SIDEBAR
            ========================================= */

            const searchInput =
                document.getElementById(
                    'searchBangunan'
                );


            const buildingItems =
                document.querySelectorAll(
                    '#buildingList .building-item'
                );


            const emptySearch =
                document.getElementById(
                    'emptySearch'
                );


            searchInput.addEventListener(
                'input',
                function() {

                    const keyword =
                        this.value
                        .toLowerCase()
                        .trim();


                    let totalVisible = 0;


                    buildingItems.forEach(
                        function(item) {

                            const code =
                                (item.dataset.code || '')
                                .toLowerCase();


                            const name =
                                (item.dataset.name || '')
                                .toLowerCase();


                            const match =
                                code.includes(keyword) ||
                                name.includes(keyword);


                            if (match) {

                                item.style.display = '';

                                totalVisible++;

                            } else {

                                item.style.display = 'none';

                            }

                        }
                    );


                    if (totalVisible === 0) {

                        emptySearch.style.display =
                            'block';

                    } else {

                        emptySearch.style.display =
                            'none';

                    }

                }
            );


            /* =========================================
               SET UKURAN MAP SETELAH IMAGE LOAD
            ========================================= */

            mapImage.addEventListener(
                'load',
                function() {

                    /*
                     * Pastikan mapContent mengikuti
                     * ukuran asli gambar.
                     */

                    mapContent.style.width =
                        mapImage.offsetWidth + 'px';

                }
            );


            /*
             * Jika gambar sudah cache
             */

            if (mapImage.complete) {

                mapContent.style.width =
                    mapImage.offsetWidth + 'px';

            }


        });
    </script>
