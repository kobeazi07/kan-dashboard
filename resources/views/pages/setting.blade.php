@extends('layouts.index')


@section('konten')
    <!-- START: Page Header Banner -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Link Data PBG</h1>

        </div>

    </div>
    <!-- END: Page Header Banner -->

    <!-- START: Form Component Row Grid Layout -->
    <div class="row g-4 mb-4">

        <!-- Column 1: Basic controls -->
        <form class="editformcv" data-id="{{ $setting->id }}" enctype="multipart/form-data">
            @csrf
            <div class="col-12 col-lg-6">
                <div class="card border-light shadow-sm p-4 h-100">
                    <h5 class="card-title mb-4">Link Dokumen</h5>

                    <div class="mb-3">
                        <label for="link_jetty" class="form-label-custom">Jetty</label>
                        <textarea class="form-control" id="link_jetty" name="link_jetty" placeholder="Masukkan Link">{{ $setting->link_jetty ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="link_refinery" class="form-label-custom">Refinery</label>
                        <textarea class="form-control" id="link_refinery" name="link_refinery" placeholder="Masukkan Link">{{ $setting->link_refinery ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="link_power_plant" class="form-label-custom">Power Plant</label>
                        <textarea class="form-control" id="link_power_plant" name="link_power_plant" placeholder="Masukkan Link">{{ $setting->link_power_plant ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="link_redmud" class="form-label-custom">Redmud</label>
                        <textarea class="form-control" id="link_redmud" name="link_redmud" placeholder="Masukkan Link">{{ $setting->link_redmud ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="link_jembatan" class="form-label-custom">Jembatan</label>
                        <textarea class="form-control" id="link_jembatan" name="link_jembatan" placeholder="Masukkan Link">{{ $setting->link_jembatan ?? '' }}</textarea>
                    </div>

                    <button class="btn-custom btn-custom-primary" type="submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>



    </div>
    <!-- END: Form Component Row Grid Layout -->


    <script>
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('custom-file-input')) {
                e.target.nextElementSibling.innerText = e.target.files[0].name;
            }
        });


        $(document).on('submit', '.editformcv', function(e) {
            e.preventDefault();

            let form = $(this);
            let id = form.data('id');
            let formData = new FormData(this);


            $.ajax({
                url: "{{ url('/edit_setting') }}/" + id,
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
    </script>
@endsection
