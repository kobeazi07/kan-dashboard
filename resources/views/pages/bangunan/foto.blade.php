@extends('layouts.index')

@section('konten')
    <style>
        .body-wrapper-inner {
            margin-top: 0 !important;
            padding: 20px;
        }

        .page-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .building-info {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 18px;
        }

        .building-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .building-meta {
            color: #6c757d;
            font-size: 13px;
        }

        .kamera-card {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .kamera-preview {
            width: 100%;
            max-height: 520px;
            min-height: 250px;
            object-fit: contain;
            background: #171717;
            border-radius: 12px;
        }

        .kamera-status {
            background: #f8f9fa;
            border: 1px solid #edf0f2;
            border-radius: 12px;
            padding: 14px;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .gps-line {
            margin-top: 5px;
            color: #495057;
        }

        #canvasFoto {
            width: 100%;
            height: auto;
            border-radius: 12px;
            background: #eee;
        }

        .btn-kamera {
            min-height: 50px;
            border-radius: 10px;
            font-weight: 600;
        }

        .label-section {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .foto-card {
            height: 100%;
            overflow: hidden;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .07);
        }

        .foto-card .blog-img {
            position: relative;
        }

        .foto-card .blog-img img {
            width: 100%;
            height: 190px;
            object-fit: cover;
            display: block;
        }

        .btn-submit-delete {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 8px;
            background: rgba(220, 53, 69, .95);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .gallery-title {
            font-weight: 700;
            margin-bottom: 15px;
        }

        @media (max-width: 767.98px) {
            .body-wrapper-inner {
                padding: 12px !important;
            }

            .page-title {
                font-size: 20px;
            }

            .building-info {
                padding: 13px;
            }

            .building-name {
                font-size: 16px;
            }

            .kamera-card .card-body {
                padding: 13px !important;
            }

            .kamera-preview {
                min-height: 230px;
                max-height: 65vh;
            }

            .kamera-status {
                padding: 12px;
                font-size: 12px;
            }

            .btn-kamera {
                min-height: 52px;
                font-size: 14px;
            }

            .foto-card .blog-img img {
                height: 145px;
            }
        }

        @media (max-width: 375px) {
            .foto-card .blog-img img {
                height: 120px;
            }

            .building-meta {
                font-size: 12px;
            }
        }
    </style>

    <div class="body-wrapper-inner">
        <div class="container-fluid">

            {{-- Header --}}
            <div class="mb-3">
                <h4 class="page-title">Dokumentasi Lapangan</h4>
                <p class="text-muted mb-0">
                    Ambil foto dokumentasi dengan informasi lokasi dan waktu otomatis.
                </p>
            </div>

            {{-- Informasi Bangunan --}}
            <div class="building-info mb-3">
                <div class="building-name">
                    {{ $bangunan->nama ?? '-' }}
                </div>

                <div class="building-meta">
                    <span>Kode: {{ $bangunan->kode ?? '-' }}</span>
                    <span class="mx-1">•</span>
                    <span>Kawasan: {{ $bangunan->kawasan->nama ?? '-' }}</span>
                </div>
            </div>

            {{-- ID Bangunan dan nama untuk JavaScript --}}
            <input type="hidden" name="bangunan_id" value="{{ $bangunan->id }}">
            <input type="hidden" id="namaProyek" value="{{ $bangunan->nama ?? '' }}">

            {{-- Kamera --}}
            <div class="card kamera-card shadow-sm mb-4">
                <div class="card-body p-3 p-md-4">

                    {{-- Informasi GPS --}}
                    <div class="kamera-status mb-3">
                        <div class="fw-bold mb-2">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                            Informasi Lokasi
                        </div>

                        <div id="statusGPS" class="text-muted">
                            Lokasi belum diambil.
                        </div>

                        <div id="koordinatGPS" class="gps-line">
                            Latitude: - | Longitude: -
                        </div>

                        <div id="akurasiGPS" class="gps-line">
                            Akurasi GPS: -
                        </div>

                        <div id="alamatGPS" class="gps-line">
                            Alamat: menunggu lokasi.
                        </div>

                        <div id="waktuGPS" class="gps-line">
                            Waktu: -
                        </div>
                    </div>

                    {{-- Area Kamera --}}
                    <div id="areaKamera" class="mb-3" style="display:none;">
                        <div class="label-section">Kamera</div>

                        <video id="videoKamera" class="kamera-preview" autoplay playsinline muted>
                        </video>

                        <div class="row g-2 mt-2">
                            <div class="col-3">
                                <button type="button" id="btnCancelFoto" class="btn btn-outline-danger btn-kamera w-100">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <div class="col-9">
                                <button type="button" id="btnAmbilFoto" class="btn btn-success btn-kamera w-100">
                                    <i class="bi bi-camera-fill me-1"></i>
                                    Ambil Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Area Preview --}}
                    <div id="areaPreview" class="mb-3" style="display:none;">
                        <div class="label-section">Preview Foto Watermark</div>

                        <canvas id="canvasFoto"></canvas>

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <button type="button" id="btnUlangi" class="btn btn-outline-secondary btn-kamera w-100">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Foto Ulang
                                </button>
                            </div>

                            <div class="col-6">
                                <button type="button" id="btnUpload" class="btn btn-primary btn-kamera w-100">
                                    <i class="bi bi-cloud-arrow-up me-1"></i>
                                    Upload Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Mulai --}}
                    <div id="areaMulai">
                        <div class="d-grid">
                            <button type="button" id="btnBukaKamera" class="btn btn-primary btn-kamera">
                                <i class="bi bi-camera-fill me-1"></i>
                                Buka Kamera dan Ambil Lokasi
                            </button>
                        </div>
                    </div>

                    <div id="pesanUpload" class="mt-3" aria-live="polite"></div>

                </div>
            </div>

            {{-- Galeri Dokumentasi --}}
            <div class="mb-3">
                <h5 class="gallery-title">
                    <i class="bi bi-images me-1"></i>
                    Galeri Dokumentasi
                </h5>
            </div>

            <div class="row g-3">
                @forelse ($foto as $itemFoto)
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="foto-card">
                            <div class="blog-img">

                                <a href="{{ asset('inputan/bangunan/detailimg/' . $itemFoto->foto) }}"
                                    data-lightbox="DokumentasiBangunan" data-title="Dokumentasi {{ $bangunan->nama ?? '' }}"
                                    class="lightbox-link">

                                    <img src="{{ asset('inputan/bangunan/detailimg/' . $itemFoto->foto) }}"
                                        class="img-fluid" alt="Dokumentasi bangunan" loading="lazy">
                                </a>

                                <form action="{{ route('dokumentasi.destroy', $itemFoto->id) }}" method="POST"
                                    class="form-delete-foto">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-submit-delete" aria-label="Hapus foto"
                                        title="Hapus foto">

                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-4 bg-white rounded shadow-sm">
                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                            Belum ada dokumentasi foto untuk bangunan ini.
                        </div>
                    </div>
                @endforelse
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

            const areaKamera = document.getElementById('areaKamera');
            const areaPreview = document.getElementById('areaPreview');
            const areaMulai = document.getElementById('areaMulai');
            const pesanUpload = document.getElementById('pesanUpload');

            const bangunanId = document.querySelector('input[name="bangunan_id"]').value;
            const namaProyek = document.getElementById('namaProyek').value;

            let streamKamera = null;
            let fotoSiapUpload = null;
            let posisiGPS = null;
            let alamat = '';
            let metadataFoto = null;

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
                    'Sedang mengambil lokasi GPS...';

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
                    `Latitude: ${posisiGPS.latitude.toFixed(6)} | Longitude: ${posisiGPS.longitude.toFixed(6)}`;

                document.getElementById('akurasiGPS').textContent =
                    `Akurasi GPS: ±${Math.round(posisiGPS.accuracy)} meter`;

                document.getElementById('statusGPS').textContent =
                    'Koordinat berhasil diperoleh.';

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

            async function bukaKamera() {
                btnBuka.disabled = true;
                tampilkanPesan('Mengambil lokasi dan membuka kamera...', 'info');

                try {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        throw new Error('Browser tidak mendukung akses kamera. Gunakan HTTPS atau localhost.');
                    }

                    await ambilGPS();

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
                    areaPreview.style.display = 'none';
                    areaMulai.style.display = 'none';

                    tampilkanPesan('Kamera siap digunakan.', 'success');

                } catch (error) {
                    console.error(error);

                    let pesan = error.message || 'Tidak dapat membuka kamera atau mengambil GPS.';

                    if (error.code === 1) {
                        pesan = 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.';
                    } else if (error.name === 'NotAllowedError') {
                        pesan = 'Izin kamera ditolak. Aktifkan izin kamera di browser.';
                    } else if (error.name === 'NotFoundError') {
                        pesan = 'Kamera tidak ditemukan pada perangkat ini.';
                    } else if (error.name === 'NotReadableError') {
                        pesan = 'Kamera sedang digunakan aplikasi lain.';
                    }

                    tampilkanPesan(pesan, 'danger');

                } finally {
                    btnBuka.disabled = false;
                }
            }

            btnBuka.addEventListener('click', bukaKamera);

            // Ambil foto dan tambahkan watermark
            btnAmbil.addEventListener('click', function() {

                if (!streamKamera || !posisiGPS || !video.videoWidth) {
                    tampilkanPesan('Kamera atau GPS belum siap.', 'warning');
                    return;
                }

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const waktuFoto = new Date();

                metadataFoto = {
                    tanggal: formatTanggal(waktuFoto),
                    jam: formatJam(waktuFoto),
                    tanggal_jam_iso: waktuFoto.toISOString(),
                    latitude: posisiGPS.latitude,
                    longitude: posisiGPS.longitude,
                    akurasi_gps: posisiGPS.accuracy,
                    alamat: alamat,
                    nama_proyek: namaProyek
                };

                document.getElementById('waktuGPS').textContent =
                    `Waktu pengambilan: ${metadataFoto.tanggal} ${metadataFoto.jam}`;

                const padding = Math.round(canvas.width * 0.025);
                const fontSize = Math.max(18, Math.round(canvas.width * 0.018));
                const lineHeight = Math.round(fontSize * 1.5);
                const maxWidth = canvas.width - padding * 2;

                const lines = [
                    `Bangunan: ${metadataFoto.nama_proyek || 'Dokumentasi Bangunan'}`,
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

                    for (const word of String(line).split(/\s+/)) {
                        const test = current ? `${current} ${word}` : word;

                        if (ctx.measureText(test).width > maxWidth && current) {
                            wrappedLines.push(current);
                            current = word;
                        } else {
                            current = test;
                        }
                    }

                    if (current) wrappedLines.push(current);
                });

                const panelHeight = padding * 2 + wrappedLines.length * lineHeight;
                const panelY = canvas.height - panelHeight;

                // Latar watermark
                ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
                ctx.fillRect(0, panelY, canvas.width, panelHeight);

                // Teks watermark
                ctx.fillStyle = '#fff';
                ctx.textBaseline = 'top';
                ctx.font = `bold ${fontSize}px Arial`;

                wrappedLines.forEach((line, index) => {
                    const y = panelY + padding + index * lineHeight;
                    ctx.fillText(line, padding, y, maxWidth);
                });

                canvas.toBlob(function(blob) {
                    if (!blob) {
                        tampilkanPesan('Gagal memproses foto.', 'danger');
                        return;
                    }

                    fotoSiapUpload = blob;

                    areaPreview.style.display = 'block';
                    areaKamera.style.display = 'none';

                    hentikanKamera();

                    tampilkanPesan(
                        'Foto berhasil diambil. Periksa watermark sebelum upload.',
                        'success'
                    );

                }, 'image/jpeg', 0.92);
            });

            // Foto ulang
            btnUlangi.addEventListener('click', async function() {
                fotoSiapUpload = null;
                metadataFoto = null;

                areaPreview.style.display = 'none';
                areaKamera.style.display = 'none';
                areaMulai.style.display = 'block';

                await bukaKamera();
            });

            // Batalkan pengambilan foto
            btnCancelFoto.addEventListener('click', function() {
                hentikanKamera();

                fotoSiapUpload = null;
                metadataFoto = null;
                posisiGPS = null;
                alamat = '';

                areaKamera.style.display = 'none';
                areaPreview.style.display = 'none';
                areaMulai.style.display = 'block';

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                tampilkanPesan('Pengambilan foto dibatalkan.', 'warning');
            });

            // Upload foto
            btnUpload.addEventListener('click', async function() {

                if (!fotoSiapUpload || !metadataFoto) {
                    tampilkanPesan('Silakan ambil foto terlebih dahulu.', 'warning');
                    return;
                }

                const formData = new FormData();

                formData.append('bangunan_id', bangunanId);
                formData.append('foto', fotoSiapUpload, 'dokumentasi.jpg');
                formData.append('nama_proyek', metadataFoto.nama_proyek);
                formData.append('latitude', metadataFoto.latitude);
                formData.append('longitude', metadataFoto.longitude);
                formData.append('akurasi_gps', metadataFoto.akurasi_gps);
                formData.append('alamat', metadataFoto.alamat);
                formData.append('tanggal_jam', metadataFoto.tanggal_jam_iso);

                btnUpload.disabled = true;
                btnUpload.textContent = 'Mengunggah...';

                try {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfMeta) {
                        throw new Error('Meta CSRF tidak ditemukan di layout.');
                    }

                    const response = await fetch(
                        "{{ route('dokumentasi.upload') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfMeta.content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        }
                    );

                    const result = await response.json();

                    if (!response.ok) {
                        if (result.errors) {
                            const firstError = Object.values(result.errors).flat()[0];
                            throw new Error(firstError || result.message || 'Upload gagal.');
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

                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } catch (error) {
                    console.error(error);
                    tampilkanPesan(error.message, 'danger');

                } finally {
                    btnUpload.disabled = false;
                    btnUpload.innerHTML =
                        '<i class="bi bi-cloud-arrow-up me-1"></i> Upload Foto';
                }
            });

            // Hentikan kamera ketika halaman ditinggalkan
            window.addEventListener('pagehide', hentikanKamera);
        });

        // Hapus foto dengan konfirmasi SweetAlert
        $(document).on('submit', '.form-delete-foto', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Hapus dokumentasi?',
                text: 'Foto yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
