@extends('layouts.index')

@section('konten')
    <!-- START: Page Header Banner -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Peta</h1>
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
                            <form id="formmaps" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">


                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" id="date" name="date">
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Link Embed</label>
                                        <textarea class="form-control" id="embed" name="embed" placeholder="masukkan Tulisan"></textarea>
                                    </div>




                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="btnSavemaps">Save changes</button>
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
                        <th>Tanggal</th>

                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maps as $key => $maps)
                        <!-- Row 1 -->
                        <tr>
                            <td class="table-order-id">{{ $key + 1 }}</td>
                            <td>{{ $maps->date }}</td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="table-btn-action" title="Edit row" data-bs-toggle="modal"
                                        data-bs-target="#Edit-{{ $maps->id }}">
                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg> --}}<i class="bi bi-pencil"></i>
                                    </button>


                                    {{-- modal edit --}}
                                    <!-- Modal -->
                                    <div class="modal fade" id="Edit-{{ $maps->id }}" data-backdrop="static"
                                        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Maps</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form class="editformmaps" data-id="{{ $maps->id }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">

                                                        <div class="form-group">
                                                            <label for="date">Tanggal</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ \Carbon\Carbon::parse($maps->date)->format('Y-m-d') }}"
                                                                id="date" name="date">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Link Embed</label>
                                                            <textarea class="form-control" id="embed" name="embed" placeholder="masukkan embed">{{ $maps->embed }}</textarea>
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

                                    <form action="{{ route('maps.destroy', $maps->id) }}" method="POST"
                                        class="form-delete-maps">
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
        $('#btnSavemaps').on('click', function() {
            let form = document.getElementById('formmaps');
            let formData = new FormData(form);


            $.ajax({
                url: "{{ route('Tambah_maps') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {
                    $('#btnSavemaps')
                        .prop('disabled', true)
                        .text('Menyimpan...');
                },

                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'maps berhasil ditambahkan',
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
                    $('#btnSavemaps')
                        .prop('disabled', false)
                        .text('Simpanmaps');
                }
            });
        });

        $(document).on('submit', '.editformmaps', function(e) {
            e.preventDefault();

            let form = $(this);
            let id = form.data('id');
            let formData = new FormData(this);
            $.ajax({
                url: "{{ url('/edit_maps') }}/" + id,
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

        $(document).on('submit', '.form-delete-maps', function(e) {
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
