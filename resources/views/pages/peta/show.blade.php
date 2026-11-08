@extends('layouts.index')

@section('konten')
    <div class="container">

        <h3>
            {{ $kawasan->nama }}
        </h3>

        <p>
            {{ $kawasan->deskripsi }}
        </p>


        <div class="row">

            {{-- PETA --}}
            <div class="col-lg-8">

                <div id="mapContainer"
                    style="
                    position: relative;
                    width: 100%;
                ">

                    <img src="{{ asset('storage/' . $kawasan->gambar) }}"
                        style="
                        width: 100%;
                        display: block;
                    ">


                    @foreach ($kawasan->bangunan as $bangunan)
                        <button class="user-pin btn" data-id="{{ $bangunan->id }}" data-nama="{{ $bangunan->nama }}"
                            data-deskripsi="{{ $bangunan->deskripsi }}"
                            style="
                            position: absolute;

                            left: {{ $bangunan->posisi_x }}%;

                            top: {{ $bangunan->posisi_y }}%;

                            transform:
                                translate(-50%, -100%);

                            z-index: 10;

                            border: none;

                            background: transparent;

                            font-size: 30px;
                        ">
                            📍
                        </button>
                    @endforeach

                </div>

            </div>


            {{-- LIST --}}
            <div class="col-lg-4">

                <input type="text" id="searchBangunan" class="form-control mb-3" placeholder="Cari bangunan...">


                <div id="bangunanList">

                    @foreach ($kawasan->bangunan as $bangunan)
                        <div class="card mb-2 bangunan-item" data-name="{{ strtolower($bangunan->nama) }}">

                            <div class="card-body">

                                <h6>
                                    {{ $bangunan->nama }}
                                </h6>

                                <button class="btn btn-primary btn-sm detail-button" data-id="{{ $bangunan->id }}">
                                    Detail
                                </button>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </div>

    </div>



    {{-- MODAL --}}
    <div class="modal fade" id="detailModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalTitle">
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">

                    <p id="modalDescription"></p>

                    <div id="modalPhotos" class="row"></div>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        /*
    |--------------------------------------------------------------------------
    | SEARCH BANGUNAN
    |--------------------------------------------------------------------------
    */

        document
            .getElementById('searchBangunan')
            .addEventListener('keyup', function() {

                const keyword =
                    this.value.toLowerCase();

                document
                    .querySelectorAll('.bangunan-item')
                    .forEach(item => {

                        const name =
                            item.dataset.name;

                        if (
                            name.includes(keyword)
                        ) {

                            item.style.display = '';

                        } else {

                            item.style.display = 'none';

                        }

                    });

            });


        /*
        |--------------------------------------------------------------------------
        | DETAIL BANGUNAN
        |--------------------------------------------------------------------------
        */

        function showBangunanDetail(id) {

            fetch(`/bangunan/${id}`)

                .then(response => response.json())

                .then(data => {

                    document
                        .getElementById('modalTitle')
                        .innerText = data.nama;


                    document
                        .getElementById('modalDescription')
                        .innerText =
                        data.deskripsi ?? '-';


                    let photos = '';


                    if (
                        data.foto_bangunan &&
                        data.foto_bangunan.length > 0
                    ) {

                        data.foto_bangunan.forEach(foto => {

                            photos += `
                        <div class="col-md-4 mb-3">

                            <img
                                src="/storage/${foto.foto}"
                                class="img-fluid rounded"
                            >

                        </div>
                    `;

                        });

                    } else {

                        photos =
                            '<p>Tidak ada foto.</p>';

                    }


                    document
                        .getElementById('modalPhotos')
                        .innerHTML = photos;


                    const modalElement =
                        document.getElementById(
                            'detailModal'
                        );


                    const modal =
                        new bootstrap.Modal(
                            modalElement
                        );


                    modal.show();

                });

        }


        /*
        |--------------------------------------------------------------------------
        | KLIK PIN
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.user-pin')
            .forEach(pin => {

                pin.addEventListener('click', function() {

                    showBangunanDetail(
                        this.dataset.id
                    );

                });

            });


        /*
        |--------------------------------------------------------------------------
        | KLIK DETAIL LIST
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.detail-button')
            .forEach(button => {

                button.addEventListener('click', function() {

                    showBangunanDetail(
                        this.dataset.id
                    );

                });

            });
    </script>
@endpush
