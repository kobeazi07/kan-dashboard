@extends('layouts.index')

@section('konten')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>Data Kawasan</h3>

            <a href="{{ route('kawasan.create') }}" class="btn btn-primary">
                + Tambah Kawasan
            </a>

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row">

            @foreach ($kawasans as $kawasan)
                <div class="col-md-4 mb-4">

                    <div class="card h-100">

                        <img src="{{ asset('storage/' . $kawasan->gambar) }}" class="card-img-top"
                            style="height: 200px; object-fit: cover;">

                        <div class="card-body">

                            <h5>
                                {{ $kawasan->nama }}
                            </h5>

                            <p>
                                {{ $kawasan->bangunan_count }}
                                Bangunan
                            </p>

                            <a href="{{ route('bangunan.index', $kawasan->id) }}" class="btn btn-success btn-sm">
                                Kelola Bangunan
                            </a>

                            <a href="{{ route('kawasan.edit', $kawasan->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('kawasan.destroy', $kawasan->id) }}" method="POST" class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus kawasan?')">
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>
@endsection
