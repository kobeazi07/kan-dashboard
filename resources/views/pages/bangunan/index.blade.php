@extends('layouts.index')

@section('konten')
    <div class="container">

        <div class="d-flex justify-content-between mb-4">

            <div>

                <h3>{{ $kawasan->nama }}</h3>

                <small>
                    Drag pin untuk mengubah posisi bangunan.
                </small>

            </div>

            <a href="{{ route('bangunan.create', $kawasan->id) }}" class="btn btn-primary">
                + Tambah Bangunan
            </a>

        </div>


        <div class="row">

            {{-- PETA / GAMBAR --}}
            <div class="col-lg-8">

                <div id="mapContainer"
                    style="
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                    border: 1px solid #ddd;
                ">

                    <img id="mapImage" src="{{ asset('storage/' . $kawasan->gambar) }}"
                        style="
                        width: 100%;
                        display: block;
                    ">


                    @foreach ($bangunans as $bangunan)
                        <div class="map-pin" data-id="{{ $bangunan->id }}"
                            style="
                            position: absolute;
                            left: {{ $bangunan->posisi_x }}%;
                            top: {{ $bangunan->posisi_y }}%;
                            transform: translate(-50%, -100%);
                            cursor: move;
                            z-index: 10;
                        ">

                            📍

                        </div>
                    @endforeach

                </div>

            </div>


            {{-- LIST BANGUNAN --}}
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">
                        Daftar Bangunan
                    </div>

                    <div class="list-group list-group-flush">

                        @foreach ($bangunans as $bangunan)
                            <div class="list-group-item">

                                <strong>
                                    {{ $bangunan->nama }}
                                </strong>

                                <div class="mt-2">

                                    <a href="{{ route('bangunan.edit', $bangunan->id) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('bangunan.destroy', $bangunan->id) }}" method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus bangunan?')">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        document.querySelectorAll('.map-pin').forEach(pin => {

            let isDragging = false;

            pin.addEventListener('mousedown', function(e) {

                isDragging = true;

                e.preventDefault();

            });


            document.addEventListener('mousemove', function(e) {

                if (!isDragging) return;

                const container =
                    document.getElementById('mapContainer');

                const rect =
                    container.getBoundingClientRect();

                let x =
                    ((e.clientX - rect.left) / rect.width) * 100;

                let y =
                    ((e.clientY - rect.top) / rect.height) * 100;


                x = Math.max(0, Math.min(100, x));

                y = Math.max(0, Math.min(100, y));


                pin.style.left = x + '%';

                pin.style.top = y + '%';

            });


            document.addEventListener('mouseup', function(e) {

                if (!isDragging) return;

                isDragging = false;


                const container =
                    document.getElementById('mapContainer');

                const rect =
                    container.getBoundingClientRect();

                let x =
                    ((e.clientX - rect.left) / rect.width) * 100;

                let y =
                    ((e.clientY - rect.top) / rect.height) * 100;


                x = Math.max(0, Math.min(100, x));

                y = Math.max(0, Math.min(100, y));


                fetch(
                        `/admin/bangunan/${pin.dataset.id}/position`, {
                            method: 'PUT',

                            headers: {
                                'Content-Type': 'application/json',

                                'X-CSRF-TOKEN': '{{ csrf_token() }}',

                                'Accept': 'application/json',
                            },

                            body: JSON.stringify({
                                posisi_x: x,
                                posisi_y: y,
                            }),
                        }
                    )

                    .then(response => response.json())

                    .then(data => {

                        console.log(data);

                    })

                    .catch(error => {

                        console.error(error);

                        alert(
                            'Gagal menyimpan posisi pin.'
                        );

                    });

            });

        });
    </script>
@endpush
