@extends('layouts.index')

@section('konten')
    @include('pages.bangunan.includes.css_foto')


    <div class="body-wrapper-inner kamera-page ">

        <div class="">

            {{-- HEADER --}}
            <div class="kamera-header">
                <h4>Dokumentasi Lapangan</h4>
                <p>
                    Ambil foto dengan informasi lokasi dan waktu otomatis.
                </p>
            </div>


            {{-- KARTU DOKUMENTASI --}}
            <div class="card kamera-card shadow-sm">

                <div class="card-body p-3 p-md-4">

                    {{-- INFORMASI BANGUNAN --}}
                    <input type="hidden" name="bangunan_id" value="{{ $bangunan->id }}">

                    <input type="hidden" id="namaProyek" value="{{ $bangunan->nama ?? '' }}">

                    <div class="bangunan-info">

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-building text-primary"></i>
                            <small class="text-muted fw-semibold">
                                INFORMASI BANGUNAN
                            </small>
                        </div>

                        <h5>{{ $bangunan->nama ?? '' }}</h5>

                        <div class="bangunan-meta">
                            <span>
                                <i class="bi bi-upc-scan me-1"></i>
                                {{ $bangunan->kode ?? '' }}
                            </span>

                            <span>
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $bangunan->kawasan->nama ?? '' }}
                            </span>
                        </div>

                    </div>


                    {{-- INFORMASI GPS --}}
                    <div class="kamera-status mb-3">

                        <div class="gps-heading">
                            <i class="bi bi-geo-alt-fill"></i>
                            Informasi Lokasi
                        </div>

                        <div id="statusGPS" class="text-muted mb-2">
                            Lokasi belum diambil.
                        </div>

                        <div class="gps-item">
                            <i class="bi bi-crosshair"></i>
                            <span id="koordinatGPS">
                                Latitude: - | Longitude: -
                            </span>
                        </div>

                        <div class="gps-item">
                            <i class="bi bi-bullseye"></i>
                            <span id="akurasiGPS">
                                Akurasi GPS: -
                            </span>
                        </div>

                        <div class="gps-item">
                            <i class="bi bi-map"></i>
                            <span id="alamatGPS">
                                Alamat: sedang menunggu lokasi.
                            </span>
                        </div>

                        <div class="gps-item">
                            <i class="bi bi-clock"></i>
                            <span id="waktuGPS">
                                Waktu: -
                            </span>
                        </div>

                    </div>


                    {{-- AREA KAMERA --}}
                    {{-- Area Kamera --}}
                    <div id="areaKamera" class="mb-3" style="display:none;">

                        <div class="label-section">Kamera</div>

                        {{-- Parent kamera --}}
                        <div class="kamera-frame">

                            {{-- Video kamera --}}
                            <video id="videoKamera" class="kamera-preview" autoplay playsinline muted>
                            </video>

                            {{-- Tombol batal: kiri atas --}}
                            <button type="button" id="btnCancelFoto" class="btn-camera-cancel"
                                title="Batalkan pengambilan foto">

                                <i class="bi bi-x-lg"></i>
                            </button>

                            {{-- Tombol ambil foto: kanan tengah --}}
                            <button type="button" id="btnAmbilFoto" class="btn-camera-capture" title="Ambil Foto">
                            </button>

                        </div>

                    </div>

                </div>


                {{-- PREVIEW FOTO WATERMARK --}}
                <div id="areaPreview" style="display:none;" class="mb-3">

                    <div class="label-section">
                        Preview Foto Watermark
                    </div>

                    <div class="preview-container">

                        <canvas id="canvasFoto"></canvas>

                    </div>

                    <div class="row g-2 mt-2">

                        <div class="col-6">
                            <button type="button" id="btnUlangi" class="btn btn-outline-secondary btn-kamera w-100">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                Foto Ulang
                            </button>
                        </div>

                        <div class="col-6">
                            <button type="button" id="btnUpload" class="btn btn-primary btn-kamera w-100">
                                <i class="bi bi-cloud-upload me-1"></i>
                                Upload Foto
                            </button>
                        </div>

                    </div>

                </div>


                {{-- TOMBOL MULAI --}}
                <div id="areaMulai">

                    <div class="d-grid">

                        <button type="button" id="btnBukaKamera" class="btn btn-primary btn-kamera">
                            <i class="bi bi-camera-fill me-1"></i>
                            Buka Kamera dan Ambil Lokasi
                        </button>

                    </div>

                </div>


                {{-- PESAN UPLOAD --}}
                <div id="pesanUpload" class="mt-3" aria-live="polite"></div>

            </div>

        </div>


        {{-- GALERI DOKUMENTASI --}}
        <div class="mt-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="galeri-heading mb-1">
                        <i class="bi bi-images me-1"></i>
                        Dokumentasi Foto
                    </h5>

                    <small class="text-muted">
                        Foto dokumentasi bangunan
                    </small>
                </div>
            </div>

            <div class="row g-3">

                @forelse ($foto as $itemFoto)
                    <div class="col-6 col-md-4 col-xl-3">

                        <div class="blog-item foto-card">

                            <div class="blog-img">

                                {{-- FOTO --}}
                                <a href="{{ asset('inputan/bangunan/detailimg/' . $itemFoto->foto) }}"
                                    data-lightbox="Blog-1" data-title="Dokumentasi laporan harian" class="lightbox-link">

                                    <img src="{{ asset('inputan/bangunan/detailimg/' . $itemFoto->foto) }}"
                                        class="img-fluid rounded-image" alt="Dokumentasi laporan harian" loading="lazy">

                                </a>


                                {{-- TOMBOL HAPUS --}}
                                <form action="{{ route('dokumentasi.destroy', $itemFoto->id) }}" method="POST"
                                    class="form-delete-foto">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-submit-delete" aria-label="Hapus foto dokumentasi"
                                        title="Hapus foto">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="empty-gallery">
                            <i class="bi bi-image"></i>
                            <div class="fw-semibold">
                                Dokumentasi belum tersedia
                            </div>
                            <small>
                                Foto yang berhasil diunggah akan tampil di sini.
                            </small>
                        </div>

                    </div>
                @endforelse

            </div>

        </div>

    </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const video = document.getElementById('videoKamera');
            const canvas = document.getElementById('canvasFoto');
            const ctx = canvas.getContext('2d');

            const btnBuka = document.getElementById('btnBukaKamera');
            const btnAmbil = document.getElementById('btnAmbilFoto');
            const btnUlangi = document.getElementById('btnUlangi');
            const btnUpload = document.getElementById('btnUpload');
            const btnCancelFoto = document.getElementById('btnCancelFoto');

            let streamKamera = null;
            let fotoSiapUpload = null;
            let posisiGPS = null;
            let alamat = '';
            let metadataFoto = null;

            const areaKamera = document.getElementById('areaKamera');
            const areaPreview = document.getElementById('areaPreview');
            const areaMulai = document.getElementById('areaMulai');
            const pesanUpload = document.getElementById('pesanUpload');


            function tampilkanPesan(pesan, jenis = 'info') {

                pesanUpload.innerHTML = '';

                const alert = document.createElement('div');
                alert.className = `alert alert-${jenis} mb-0`;
                alert.textContent = pesan;

                pesanUpload.appendChild(alert);
            }


            function formatTanggal(waktu) {

                return waktu.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });

            }


            function formatJam(waktu) {

                return waktu.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

            }


            function hentikanKamera() {

                if (streamKamera) {

                    streamKamera.getTracks().forEach(track => track.stop());
                    streamKamera = null;

                }

                video.srcObject = null;

            }


            async function ambilGPS() {

                if (!navigator.geolocation) {
                    throw new Error('Browser tidak mendukung GPS.');
                }

                document.getElementById('statusGPS').textContent =
                    'Meminta lokasi GPS...';

                const posisi = await new Promise((resolve, reject) => {

                    navigator.geolocation.getCurrentPosition(
                        resolve,
                        reject, {
                            enableHighAccuracy: true,
                            timeout: 20000,
                            maximumAge: 0
                        }
                    );

                });

                posisiGPS = {
                    latitude: posisi.coords.latitude,
                    longitude: posisi.coords.longitude,
                    accuracy: posisi.coords.accuracy
                };

                document.getElementById('koordinatGPS').textContent =
                    `Latitude: ${posisiGPS.latitude.toFixed(6)} | ` +
                    `Longitude: ${posisiGPS.longitude.toFixed(6)}`;

                document.getElementById('akurasiGPS').textContent =
                    `Akurasi GPS: ±${Math.round(posisiGPS.accuracy)} meter`;

                document.getElementById('statusGPS').textContent =
                    'Koordinat berhasil diperoleh.';


                // Reverse geocoding untuk mencari alamat.
                document.getElementById('alamatGPS').textContent =
                    'Alamat sedang dicari...';

                try {

                    const params = new URLSearchParams({
                        format: 'jsonv2',
                        lat: posisiGPS.latitude,
                        lon: posisiGPS.longitude
                    });

                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?${params}`
                    );

                    if (!response.ok) {
                        throw new Error('Layanan alamat tidak tersedia.');
                    }

                    const data = await response.json();

                    alamat = data.display_name || 'Alamat tidak ditemukan';

                } catch (error) {

                    alamat = 'Alamat tidak berhasil diperoleh';

                }

                document.getElementById('alamatGPS').textContent =
                    `Alamat: ${alamat}`;

                return posisiGPS;

            }


            // BUKA KAMERA DAN AMBIL GPS
            btnBuka.addEventListener('click', async function() {

                btnBuka.disabled = true;

                tampilkanPesan(
                    'Mengambil lokasi dan membuka kamera...',
                    'info'
                );

                try {

                    // Ambil GPS terlebih dahulu.
                    await ambilGPS();

                    // Meminta kamera belakang jika tersedia.
                    streamKamera = await navigator.mediaDevices.getUserMedia({

                        audio: false,

                        video: {
                            facingMode: {
                                ideal: 'environment'
                            },
                            width: {
                                ideal: 1920
                            },
                            height: {
                                ideal: 1080
                            }
                        }

                    });

                    video.srcObject = streamKamera;

                    await video.play();

                    areaKamera.style.display = 'block';
                    areaMulai.style.display = 'none';
                    areaPreview.style.display = 'none';

                    tampilkanPesan('Kamera siap digunakan.', 'success');

                } catch (error) {

                    console.error(error);

                    let pesan = 'Tidak dapat membuka kamera atau mengambil GPS.';

                    if (error.code === 1) {

                        pesan = 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.';

                    } else if (error.code === 2) {

                        pesan = 'Lokasi tidak tersedia. Periksa pengaturan GPS perangkat.';

                    } else if (error.code === 3) {

                        pesan = 'Waktu pengambilan GPS habis. Coba kembali.';

                    } else if (error.name === 'NotAllowedError') {

                        pesan = 'Izin kamera ditolak. Aktifkan izin kamera di browser.';

                    } else if (error.name === 'NotFoundError') {

                        pesan = 'Kamera tidak ditemukan pada perangkat ini.';

                    } else if (error.name === 'NotReadableError') {

                        pesan = 'Kamera sedang digunakan aplikasi lain.';

                    } else if (error.message) {

                        pesan = error.message;

                    }

                    tampilkanPesan(pesan, 'danger');

                } finally {

                    btnBuka.disabled = false;

                }

            });


            // AMBIL FOTO
            btnAmbil.addEventListener('click', function() {

                if (!streamKamera || !posisiGPS || !video.videoWidth) {

                    tampilkanPesan(
                        'Kamera atau GPS belum siap.',
                        'warning'
                    );

                    return;

                }

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Ambil gambar dari kamera.
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Waktu dicatat ketika foto diambil.
                const waktuFoto = new Date();

                metadataFoto = {

                    tanggal: formatTanggal(waktuFoto),
                    jam: formatJam(waktuFoto),
                    tanggal_jam_iso: waktuFoto.toISOString(),

                    latitude: posisiGPS.latitude,
                    longitude: posisiGPS.longitude,
                    akurasi_gps: posisiGPS.accuracy,

                    alamat: alamat,

                    nama_proyek: document.getElementById('namaProyek').value.trim()

                };

                document.getElementById('waktuGPS').textContent =
                    `Waktu pengambilan: ${metadataFoto.tanggal} ${metadataFoto.jam}`;


                // WATERMARK
                const namaProyek = metadataFoto.nama_proyek || 'Dokumentasi Proyek';

                const padding = Math.round(canvas.width * 0.025);
                const fontSize = Math.max(18, Math.round(canvas.width * 0.018));
                const lineHeight = Math.round(fontSize * 1.5);
                const maxWidth = canvas.width - padding * 2;

                const lines = [

                    `Proyek: ${namaProyek}`,
                    `Tanggal: ${metadataFoto.tanggal}`,
                    `Jam: ${metadataFoto.jam}`,
                    `Latitude: ${metadataFoto.latitude.toFixed(6)}`,
                    `Longitude: ${metadataFoto.longitude.toFixed(6)}`,
                    `Akurasi GPS: ±${Math.round(metadataFoto.akurasi_gps)} meter`,
                    metadataFoto.alamat

                ];

                ctx.font = `bold ${fontSize}px Arial`;

                const wrappedLines = [];

                lines.forEach(line => {

                    let current = '';

                    for (const word of line.split(/\s+/)) {

                        const test = current ? `${current} ${word}` : word;

                        if (ctx.measureText(test).width > maxWidth && current) {

                            wrappedLines.push(current);
                            current = word;

                        } else {

                            current = test;

                        }

                    }

                    if (current) {
                        wrappedLines.push(current);
                    }

                });

                const panelWidth = canvas.width * 0.7;

                const panelHeight =
                    padding * 1 + wrappedLines.length * lineHeight;

                const panelX = 0;
                const panelY = canvas.height - panelHeight;

                ctx.fillStyle = 'rgba(0, 0, 0, 0.25)';
                ctx.fillRect(panelX, panelY, panelWidth, panelHeight);

                ctx.fillStyle = '#fff';
                ctx.textBaseline = 'top';

                ctx.lineWidth = 2;
                ctx.strokeStyle = 'rgba(0, 0, 0, 0.9)';
                ctx.lineJoin = 'round';

                wrappedLines.forEach((line, index) => {

                    const x = padding;
                    const y = panelY + padding + index * lineHeight;

                    ctx.strokeText(line, x, y, maxWidth);

                    ctx.fillStyle = '#fff';
                    ctx.fillText(line, x, y, maxWidth);

                });

                wrappedLines.forEach((line, index) => {

                    ctx.fillText(
                        line,
                        padding,
                        panelY + padding + index * lineHeight,
                        maxWidth
                    );

                });


                canvas.toBlob(function(blob) {

                    if (!blob) {
                        tampilkanPesan('Gagal memproses foto.', 'danger');
                        return;
                    }

                    fotoSiapUpload = blob;

                    // Langsung tampilkan preview
                    areaPreview.style.display = 'block';
                    areaKamera.style.display = 'none';

                    // Matikan kamera
                    hentikanKamera();

                    tampilkanPesan(
                        'Foto berhasil diambil. Periksa watermark sebelum upload.',
                        'success'
                    );

                }, 'image/jpeg', 0.92);
            });


            // FOTO ULANG
            btnUlangi.addEventListener('click', function() {

                fotoSiapUpload = null;
                metadataFoto = null;

                areaPreview.style.display = 'none';
                areaKamera.style.display = 'none';
                areaMulai.style.display = 'block';

                btnBuka.click();

            });


            // BATAL FOTO
            btnCancelFoto.addEventListener('click', function() {

                hentikanKamera();

                fotoSiapUpload = null;
                metadataFoto = null;

                areaKamera.style.display = 'none';
                areaPreview.style.display = 'none';
                areaMulai.style.display = 'block';

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                tampilkanPesan(
                    'Pengambilan foto dibatalkan.',
                    'warning'
                );

            });


            // UPLOAD FOTO
            btnUpload.addEventListener('click', async function() {

                if (!fotoSiapUpload || !metadataFoto) {

                    tampilkanPesan(
                        'Silakan ambil foto terlebih dahulu.',
                        'warning'
                    );

                    return;

                }

                const formData = new FormData();

                formData.append(
                    'bangunan_id',
                    $('input[name="bangunan_id"]').val()
                );

                formData.append(
                    'foto',
                    fotoSiapUpload,
                    'dokumentasi.jpg'
                );

                formData.append(
                    'nama_proyek',
                    metadataFoto.nama_proyek
                );

                formData.append(
                    'latitude',
                    metadataFoto.latitude
                );

                formData.append(
                    'longitude',
                    metadataFoto.longitude
                );

                formData.append(
                    'akurasi_gps',
                    metadataFoto.akurasi_gps
                );

                formData.append(
                    'alamat',
                    metadataFoto.alamat
                );

                formData.append(
                    'tanggal_jam',
                    metadataFoto.tanggal_jam_iso
                );

                btnUpload.disabled = true;
                btnUpload.textContent = 'Mengunggah...';

                try {

                    const response = await fetch(
                        "{{ route('dokumentasi.upload') }}", {
                            method: 'POST',

                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content,

                                'Accept': 'application/json'
                            },

                            body: formData
                        }
                    );

                    const result = await response.json();

                    if (!response.ok) {

                        if (result.errors) {

                            const firstError =
                                Object.values(result.errors).flat()[0];

                            throw new Error(
                                firstError || result.message || 'Upload gagal.'
                            );

                        }

                        throw new Error(result.message || 'Upload gagal.');

                    }

                    tampilkanPesan(
                        result.message || 'Foto berhasil diunggah.',
                        'success'
                    );

                    fotoSiapUpload = null;
                    metadataFoto = null;

                    areaPreview.style.display = 'none';
                    areaMulai.style.display = 'block';

                    // Refresh halaman setelah upload berhasil.
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);

                } catch (error) {

                    tampilkanPesan(error.message, 'danger');

                } finally {

                    btnUpload.disabled = false;
                    btnUpload.textContent = 'Upload Foto';

                }

            });


            // Hentikan kamera jika pengguna meninggalkan halaman.
            window.addEventListener('pagehide', hentikanKamera);

        });


        // HAPUS FOTO
        $(document).on('submit', '.form-delete-foto', function(e) {

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
                        data: form.serialize(),

                        success: function(response) {

                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            ).then(() => location.reload());

                        },

                        error: function(xhr) {

                            console.log(xhr.responseText);

                            Swal.fire(
                                'Error',
                                'Gagal menghapus data',
                                'error'
                            );

                        }

                    });

                }

            });

        });
    </script>
@endsection
