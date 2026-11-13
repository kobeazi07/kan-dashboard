@extends('layouts.index')

@section('konten')
    <!-- START: Page Header Banner -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Data bangunan</h1>
            <p class="page-subtitle"></p>
        </div>
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.html" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item text-muted-green">Components</li>
                <li class="breadcrumb-item active text-main" aria-current="page">Basic Tables</li>
            </ol>
        </nav> --}}
    </div>
    <!-- END: Page Header Banner -->

    <!-- START: Basic Table Card Container -->
    <div class="table-card-custom">
        <!-- Header Controls -->
        <div class="table-header-control">
            <!-- Search bar -->
            <div class="table-search-box">


            </div>
            <!-- Action buttons / Filter options -->
            <div class="table-filter-group">

                <button class="btn-table-action" type="sumbit" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Tambah +
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formbangunan" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Kode</label>
                                        <input type="text" class="form-control" name="kode"
                                            id="exampleFormControlInput1" placeholder="masukkan kode bangunan">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Kawasan</label>

                                        <select id="building_type" name="kawasan_id" class="form-control" required>

                                            <option value="" selected disabled>Choose...</option>

                                            @foreach ($kawasan as $b_types)
                                                <option value="{{ $b_types->id }}">
                                                    {{ $b_types->nama }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Nama bangunan</label>
                                        <input type="text" class="form-control" name="nama"
                                            id="exampleFormControlInput1" placeholder="masukkan nama bangunan">
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Deskripsi</label>
                                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="masukkan deskripsi bangunan"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Position X</label>
                                        <input type="text" class="form-control" name="position_x"
                                            id="exampleFormControlInput1" placeholder="masukkan posisi X">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Position Y</label>
                                        <input type="text" class="form-control" name="position_y"
                                            id="exampleFormControlInput1" placeholder="masukkan posisi Y">
                                    </div>

                                    <label>Detail Gambar</label>
                                    <div class="input-group mb-3">
                                        <button type="button" class="btn btn-primary" onclick="addInput()">Tambah
                                            +</button>

                                    </div>


                                    <div id = "items-container"></div>



                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="btnSavebangunan">Save
                                        changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Table Wrapper -->
        <div class="table-responsive">
            <table class="table-custom" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama bangunan</th>
                        <th>Kawasan </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bangunan as $key => $bangunan)
                        <!-- Row 1 -->
                        <tr>
                            <td class="table-order-id">{{ $key + 1 }}</td>
                            <td>{{ $bangunan->kode }}</td>
                            <td>{{ $bangunan->nama }}</td>
                            <td>
                                @if ($bangunan->kawasan)
                                    {{ $bangunan->kawasan->nama }}
                                @else
                                    <span class="text-muted">Tidak ada kawasan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="table-btn-action" title="Edit row" data-bs-toggle="modal"
                                        data-bs-target="#Edit-{{ $bangunan->id }}">
                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg> --}}<i class="bi bi-pencil"></i>
                                    </button>


                                    {{-- modal edit --}}
                                    <!-- Modal -->
                                    <div class="modal fade" id="Edit-{{ $bangunan->id }}" data-backdrop="static"
                                        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">bangunan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form class="editformbangunan" data-id="{{ $bangunan->id }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">


                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Kode</label>
                                                            <input type="text" class="form-control" name="kode"
                                                                id="exampleFormControlInput1"
                                                                value= "{{ $bangunan->kode }}"
                                                                placeholder="masukkan kode bangunan">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Kawasan</label>


                                                            <select id="building_type" name="kawasan_id"
                                                                class="form-control" required>

                                                                <option value="" disabled>Choose...</option>

                                                                @foreach ($kawasan1 as $b_typesss)
                                                                    <option value="{{ $b_typesss->id }}"
                                                                        {{ $bangunan->kawasan_id == $b_typesss->id ? 'selected' : '' }}>
                                                                        {{ $b_typesss->nama }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Nama bangunan</label>
                                                            <input type="text" class="form-control" name="nama"
                                                                id="exampleFormControlInput1"
                                                                value= "{{ $bangunan->nama }}"
                                                                placeholder="masukkan nama bangunan">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Deskripsi</label>
                                                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="masukkan deskripsi bangunan">{{ $bangunan->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Position X</label>
                                                            <input type="text" class="form-control" name="position_x"
                                                                id="exampleFormControlInput1"
                                                                value="{{ $bangunan->position_x }}"
                                                                placeholder="masukkan posisi X">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Position Y</label>
                                                            <input type="text" class="form-control" name="position_y"
                                                                id="exampleFormControlInput1"
                                                                value="{{ $bangunan->position_y }}"
                                                                placeholder="masukkan posisi Y">
                                                        </div>

                                                        <label>Detail Gambar</label>
                                                        <div class="input-group mb-3">
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="addInput1  ({{ $bangunan->id }})">Tambah
                                                                +</button>

                                                        </div>


                                                        <div id = "items-container1-{{ $bangunan->id }}"></div>

                                                        <div class="row">
                                                            @if ($bangunan->fotoBangunan->count())
                                                                @foreach ($bangunan->fotoBangunan as $picture)
                                                                    <div class="position-relative d-inline-block mr-2 mb-2"
                                                                        id="picture-{{ $picture->id }}">

                                                                        <img src="{{ asset('inputan/bangunan/detailimg/' . $picture->foto) }}"
                                                                            style="
                                                                                width:80px;
                                                                                height:80px;
                                                                                object-fit:cover;
                                                                                border-radius:6px;
                                                                            ">

                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm position-absolute"
                                                                            style="top:2px; right:2px; padding:2px 6px;"
                                                                            onclick="deletePicture({{ $picture->id }})">
                                                                            ×
                                                                        </button>

                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <p>Gambar Kosong</p>
                                                            @endif
                                                        </div>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- akhir modal edit --}}

                                    <form action="{{ route('bangunan.destroy', $bangunan->id) }}" method="POST"
                                        class="form-delete-bangunan">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-btn-action delete btn-submit-delete">
                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                            </svg> --}} <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- <!-- Footer Controls / Pagination -->
        <div class="table-footer-control">
            <span class="table-pagination-info">Showing 1 to 10 of 50 entries</span>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0 gap-1">
                    <li class="page-item disabled"><a class="page-link border-0" href="#"><i
                                class="bi bi-chevron-left"></i></a>
                    </li>
                    <li class="page-item active"><a class="page-link border-0" href="#">1</a></li>
                    <li class="page-item"><a class="page-link border-0" href="#">2</a></li>
                    <li class="page-item"><a class="page-link border-0" href="#">3</a></li>
                    <li class="page-item"><a class="page-link border-0" href="#"><i
                                class="bi bi-chevron-right"></i></a></li>
                </ul>
            </nav>
        </div> --}}
    </div>


    <script>
        $(document).ready(function() {
            let table;

            if ($.fn.DataTable.isDataTable('#dataTable')) {
                table = $('#dataTable').DataTable();
            } else {
                table = $('#dataTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    searching: true,
                    paging: true,
                    ordering: true,
                    info: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        zeroRecords: "Data tidak ditemukan",
                        emptyTable: "Belum ada data",
                        paginate: {
                            next: "Berikutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });
            }

            // Hubungkan search custom hanya sekali
            $('.table-search-input')
                .off('input.dataTableSearch')
                .on('input.dataTableSearch', function() {
                    table.search(this.value).draw();
                });
        });

        // Update label filename
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('custom-file-input')) {
                e.target.nextElementSibling.innerText = e.target.files[0].name;
            }
        });
        $('#btnSavebangunan').on('click', function() {
            let form = document.getElementById('formbangunan');
            let formData = new FormData(form);


            $.ajax({
                url: "{{ route('Tambah_bangunan') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {
                    $('#btnSavebangunan')
                        .prop('disabled', true)
                        .text('Menyimpan...');
                },

                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'bangunan berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },

                error: function(xhr) {
                    let pesan = 'Terjadi kesalahan';

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        pesan = '';
                        for (let key in errors) {
                            pesan += `• ${errors[key][0]}<br>`;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: pesan
                    });
                },

                complete: function() {
                    $('#btnSavebangunan')
                        .prop('disabled', false)
                        .text('Simpanbangunan');
                }
            });
        });


        function addInput() {
            let uniqueId = Date.now();

            let html = `
        <div class="input-group mb-3" id="item-${uniqueId}">
            <div class="input-group-prepend">
                <span class="input-group-text">Upload</span>
            </div>

            <div class="custom-file">
                <input type="file" 
                       name="files[]" 
                       class="custom-file-input" 
                       id="file-${uniqueId}">
                <label class="custom-file-label" for="file-${uniqueId}">
                    Choose file
                </label>
            </div>
      
            <div class="input-group-append">
                <button type="button" 
                        class="btn btn-danger"
                        onclick="removeInput('${uniqueId}')">
                    Hapus
                </button>
            </div>
        </div>
        `;

            document.getElementById('items-container').insertAdjacentHTML('beforeend', html);
        }

        function addInput1(portfolioId) {
            let uniqueId = Date.now();

            let html = `
        <div class="input-group mb-3" id="items1-${uniqueId}">
            <div class="input-group-prepend">
                <span class="input-group-text">Upload</span>
            </div>

            <div class="custom-file">
                <input type="file"
                       name="files[]"
                       class="custom-file-input"
                       id="file-${uniqueId}">
                <label class="custom-file-label" for="file-${uniqueId}">
                    Choose file
                </label>
            </div>

            <div class="input-group-append">
                <button type="button"
                        class="btn btn-danger"
                        onclick="removeInput1('${uniqueId}')">
                    Hapus
                </button>
            </div>
        </div>
    `;

            document.getElementById(`items-container1-${portfolioId}`).insertAdjacentHTML('beforeend', html);
        }

        function removeInput(id) {
            document.getElementById(`item-${id}`).remove();
        }

        function removeInput1(id) {
            document.getElementById(`items1-${id}`)?.remove();
        }

        // Update label filename
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('custom-file-input')) {
                e.target.nextElementSibling.innerText = e.target.files[0].name;
            }
        });

        function deletePicture(id) {
            if (!confirm('Hapus gambar ini?')) return;

            fetch(`/bangunan/detail-picture/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('picture-' + id).remove();
                    } else {
                        alert('Gagal menghapus gambar');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan');
                });
        }
        $(document).on('submit', '.editformbangunan', function(e) {
            e.preventDefault();

            let form = $(this);
            let id = form.data('id');
            let formData = new FormData(this);
            $.ajax({
                url: "{{ url('/edit_bangunan') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire('Sukses', response.message, 'success');
                    $('#Edit-' + id).modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan', 'error');
                }
            });
        });

        $(document).on('submit', '.form-delete-bangunan', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            console.log('DELETE URL:', url);

            Swal.fire({
                title: 'Yakin?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: form.serialize(), // sudah ada _method=DELETE + _token
                        success: function(response) {
                            Swal.fire('Terhapus!', response.message, 'success')
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        }
                    });

                }
            });
        });
    </script>
    <!-- END: Basic Table Card Container -->
@endsection
