<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kamera Dokumentasi</title>
    @include('pages.bangunan.includes.css_act_foto')
</head>

<body>

    <input type="hidden" name="bangunan_id" value="{{ $bangunan->id }}">

    <input type="hidden" id="namaProyek" value="{{ $bangunan->nama }}">

    {{-- LAYAR KAMERA --}}
    <div class="camera-screen">

        <div id="areaKamera" class="kamera-frame">

            <video id="videoKamera" class="kamera-preview" autoplay playsinline muted>
            </video>

            {{-- Tombol X --}}
            <button type="button" id="btnCancelFoto" class="btn-camera-cancel" title="Kembali">

                &times;

            </button>

            {{-- Tombol shutter --}}
            <button type="button" id="btnAmbilFoto" class="btn-camera-capture" title="Ambil Foto">
            </button>

        </div>

    </div>

    {{-- PREVIEW FOTO --}}
    <div id="areaPreview" style="display:none;">

        <canvas id="canvasFoto"></canvas>

        <div class="preview-actions">

            <button type="button" id="btnUlangi">
                Foto Ulang
            </button>

            <button type="button" id="btnUpload">
                Upload Foto
            </button>

        </div>

    </div>

    <div id="pesanUpload"></div>

    <script>
        // ==================================================
        // TOMBOL X - KEMBALI KE HALAMAN DOKUMENTASI
        // ==================================================

        btnCancelFoto.addEventListener('click', function() {

            // Hentikan kamera
            hentikanKamera();

            // Bersihkan data sementara jika diperlukan
            fotoSiapUpload = null;
            metadataFoto = null;

            // Kembali ke halaman foto laporan harian
            window.location.href =
                "{{ route('HalamanFotolapharian', $bangunan->id) }}";

        });
    </script>
    {{-- =====================================
         JAVASCRIPT
    ====================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==================================================
            // 1. ELEMEN HTML
            // ==================================================

            const video = document.getElementById('videoKamera');

            const btnAmbil = document.getElementById('btnAmbilFoto');
            const btnCancelFoto = document.getElementById('btnCancelFoto');
            const btnUlangi = document.getElementById('btnUlangi');
            const btnUpload = document.getElementById('btnUpload');

            const canvas = document.getElementById('canvasFoto');
            const ctx = canvas.getContext('2d');

            const areaKamera = document.getElementById('areaKamera');
            const areaPreview = document.getElementById('areaPreview');

            const pesanUpload = document.getElementById('pesanUpload');

            const namaProyekInput = document.getElementById('namaProyek');

            const inputBangunan = document.querySelector(
                'input[name="bangunan_id"]'
            );


            // ==================================================
            // 2. VARIABEL
            // ==================================================

            let streamKamera = null;

            let fotoSiapUpload = null;

            let metadataFoto = null;

            let dataKamera = null;

            let bangunanId = null;

            let posisiGPS = null;

            let alamat = '';

            let kameraSedangDibuka = false;


            // ==================================================
            // 3. VALIDASI ELEMEN HTML
            // ==================================================

            if (
                !video ||
                !btnAmbil ||
                !btnCancelFoto ||
                !btnUlangi ||
                !btnUpload ||
                !canvas ||
                !areaKamera ||
                !areaPreview ||
                !inputBangunan
            ) {
                console.error('Elemen HTML kamera tidak lengkap.');
                return;
            }


            // ==================================================
            // 4. AMBIL DATA GPS DARI SESSION STORAGE
            // ==================================================

            try {

                dataKamera = JSON.parse(
                    sessionStorage.getItem('dataKamera') || 'null'
                );

            } catch (error) {

                console.error('Data GPS tidak valid:', error);

            }


            // Validasi data GPS
            if (!dataKamera) {

                alert(
                    'Data GPS tidak ditemukan. Silakan kembali dan ambil lokasi terlebih dahulu.'
                );

                window.location.href =
                    "{{ route('HalamanFotolapharian', $bangunan->id) }}";

                return;
            }


            // Validasi ID bangunan
            bangunanId = dataKamera.bangunan_id;

            if (String(bangunanId) !== String(inputBangunan.value)) {

                alert('ID bangunan tidak sesuai dengan data GPS.');

                window.location.href =
                    "{{ route('HalamanFotolapharian', $bangunan->id) }}";

                return;
            }


            // Validasi koordinat
            if (
                dataKamera.latitude === null ||
                dataKamera.longitude === null ||
                !Number.isFinite(Number(dataKamera.latitude)) ||
                !Number.isFinite(Number(dataKamera.longitude))
            ) {

                alert('Koordinat GPS tidak valid.');

                window.location.href =
                    "{{ route('HalamanFotolapharian', $bangunan->id) }}";

                return;
            }


            // Ambil data GPS
            posisiGPS = {

                latitude: Number(dataKamera.latitude),

                longitude: Number(dataKamera.longitude),

                accuracy: Number(dataKamera.akurasi_gps)

            };

            alamat = dataKamera.alamat || 'Alamat tidak tersedia';


            // ==================================================
            // 5. DEBUG DATA KAMERA
            // ==================================================

            console.log('====================================');
            console.log('DATA KAMERA DARI SESSION STORAGE');
            console.log('====================================');

            console.log('Data Kamera:', dataKamera);
            console.log('ID Bangunan:', bangunanId);
            console.log('Latitude:', posisiGPS.latitude);
            console.log('Longitude:', posisiGPS.longitude);
            console.log('Akurasi GPS:', posisiGPS.accuracy);
            console.log('Alamat:', alamat);
            console.log('Waktu GPS:', dataKamera.tanggal_jam);

            console.log('====================================');


            // ==================================================
            // 6. FUNGSI PESAN
            // ==================================================

            function tampilkanPesan(pesan, tipe = 'info') {

                if (!pesanUpload) return;

                if (!pesan) {

                    pesanUpload.innerHTML = '';

                    return;
                }

                const alert = document.createElement('div');

                alert.className = `alert alert-${tipe} shadow`;

                alert.textContent = pesan;

                pesanUpload.replaceChildren(alert);

            }


            // ==================================================
            // 7. FORMAT WAKTU WIB
            // ==================================================

            function formatWaktuWIB(date) {

                const parts = new Intl.DateTimeFormat('en-GB', {

                    timeZone: 'Asia/Jakarta',

                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',

                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',

                    hour12: false

                }).formatToParts(date);

                const waktu = {};

                parts.forEach(function(part) {

                    if (part.type !== 'literal') {

                        waktu[part.type] = part.value;

                    }

                });

                return {
                    tanggal: `${waktu.day}/${waktu.month}/${waktu.year}`,
                    jam: `${waktu.hour}:${waktu.minute}:${waktu.second}`,
                    tanggal_jam: `${waktu.year}-${waktu.month}-${waktu.day} ${waktu.hour}:${waktu.minute}:${waktu.second}`
                };

            }


            // ==================================================
            // 8. HENTIKAN KAMERA
            // ==================================================

            function hentikanKamera() {

                if (streamKamera) {

                    streamKamera.getTracks().forEach(function(track) {

                        track.stop();

                    });

                    streamKamera = null;

                }

                video.pause();

                video.srcObject = null;

                kameraSedangDibuka = false;

            }


            // ==================================================
            // 9. BUKA KAMERA BELAKANG
            // ==================================================

            async function bukaKamera() {

                if (kameraSedangDibuka) return;

                try {

                    hentikanKamera();

                    tampilkanPesan('Membuka kamera...', 'info');

                    if (!navigator.mediaDevices ||
                        !navigator.mediaDevices.getUserMedia) {

                        throw new Error(
                            'Browser tidak mendukung akses kamera. Gunakan HTTPS.'
                        );

                    }

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


                    // Pastikan video sudah memiliki ukuran
                    if (!video.videoWidth || !video.videoHeight) {

                        await new Promise(function(resolve, reject) {

                            video.addEventListener('loadedmetadata', resolve, {
                                once: true
                            });

                            setTimeout(function() {

                                reject(
                                    new Error('Kamera belum siap menampilkan gambar.')
                                );

                            }, 10000);

                        });

                    }


                    kameraSedangDibuka = true;

                    areaKamera.style.display = 'flex';

                    areaPreview.style.display = 'none';

                    tampilkanPesan('');


                    console.log('Kamera berhasil dibuka.');

                    console.log('Resolusi kamera:', {
                        width: video.videoWidth,
                        height: video.videoHeight
                    });


                } catch (error) {

                    console.error('Kesalahan kamera:', error);

                    let pesan = 'Tidak dapat membuka kamera.';

                    if (error.name === 'NotAllowedError') {

                        pesan = 'Izin kamera ditolak. Aktifkan izin kamera browser.';

                    } else if (error.name === 'NotFoundError') {

                        pesan = 'Kamera tidak ditemukan pada perangkat.';

                    } else if (error.name === 'NotReadableError') {

                        pesan = 'Kamera sedang digunakan aplikasi lain.';

                    } else if (error.message) {

                        pesan = error.message;

                    }

                    tampilkanPesan(pesan, 'danger');

                    hentikanKamera();

                }

            }


            // ==================================================
            // 10. WATERMARK FOTO
            // ==================================================

            function buatWatermark() {

                const namaProyek =
                    namaProyekInput?.value.trim() || 'Dokumentasi Bangunan';

                const padding = Math.round(canvas.width * 0.025);

                const fontSize = Math.max(
                    18,
                    Math.round(canvas.width * 0.018)
                );

                const lineHeight = Math.round(fontSize * 1.5);

                const panelWidth = Math.round(canvas.width * 0.85);

                const maxWidth = panelWidth - padding * 2;


                // Informasi watermark dari metadata foto
                const lines = [

                    `Bangunan: ${namaProyek}`,

                    `Tanggal: ${metadataFoto.tanggal}`,

                    `Jam: ${metadataFoto.jam} WIB`,

                    `Latitude: ${metadataFoto.latitude.toFixed(6)}`,

                    `Longitude: ${metadataFoto.longitude.toFixed(6)}`,

                    `Akurasi GPS: ±${Math.round(metadataFoto.akurasi_gps)} meter`,

                    `Alamat: ${metadataFoto.alamat}`

                ];


                // Pecah teks agar tidak keluar dari panel
                ctx.font = `bold ${fontSize}px Arial`;

                const wrappedLines = [];

                lines.forEach(function(line) {

                    let current = '';

                    for (const word of line.split(/\s+/)) {

                        const test = current ?
                            `${current} ${word}` :
                            word;

                        if (
                            ctx.measureText(test).width > maxWidth &&
                            current
                        ) {

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


                // Ukuran panel
                const panelHeight =
                    padding * 2 + wrappedLines.length * lineHeight;

                const panelX = 0;

                const panelY = canvas.height - panelHeight;


                // Background watermark
                ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';

                ctx.fillRect(
                    panelX,
                    panelY,
                    panelWidth,
                    panelHeight
                );


                // Tulisan watermark
                ctx.font = `bold ${fontSize}px Arial`;

                ctx.fillStyle = '#ffffff';

                ctx.textBaseline = 'top';

                ctx.lineWidth = 2;

                ctx.strokeStyle = 'rgba(0, 0, 0, 0.9)';

                ctx.lineJoin = 'round';


                wrappedLines.forEach(function(line, index) {

                    const x = panelX + padding;

                    const y = panelY + padding + index * lineHeight;

                    ctx.strokeText(
                        line,
                        x,
                        y,
                        maxWidth
                    );

                    ctx.fillText(
                        line,
                        x,
                        y,
                        maxWidth
                    );

                });

            }


            // ==================================================
            // 11. AMBIL FOTO
            // ==================================================

            btnAmbil.addEventListener('click', function() {

                if (
                    !streamKamera ||
                    !posisiGPS ||
                    !video.videoWidth ||
                    !video.videoHeight
                ) {

                    tampilkanPesan(
                        'Kamera atau GPS belum siap.',
                        'warning'
                    );

                    return;

                }


                try {

                    // Ambil resolusi asli kamera
                    canvas.width = video.videoWidth;

                    canvas.height = video.videoHeight;


                    // Gambar dari kamera ke canvas
                    ctx.drawImage(
                        video,
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    );


                    // Waktu aktual ketika foto diambil
                    const waktuFoto = new Date();

                    const waktuWIB = formatWaktuWIB(waktuFoto);


                    // ==================================================
                    // 12. GABUNGKAN DATA KAMERA DAN DATA GPS
                    // ==================================================

                    metadataFoto = {

                        // Data bangunan
                        bangunan_id: bangunanId,

                        nama_proyek: namaProyekInput?.value.trim() || '',

                        // Data waktu
                        tanggal: waktuWIB.tanggal,

                        jam: waktuWIB.jam,

                        tanggal_jam: waktuWIB.tanggal_jam,

                        // Data GPS dari halaman sebelumnya
                        latitude: posisiGPS.latitude,

                        longitude: posisiGPS.longitude,

                        akurasi_gps: posisiGPS.accuracy,

                        alamat: alamat,

                        // Data waktu GPS awal
                        waktu_gps: dataKamera.tanggal_jam || ''

                    };


                    console.log('====================================');

                    console.log('METADATA FOTO');

                    console.log('====================================');

                    console.log(metadataFoto);


                    // Buat watermark pada foto
                    buatWatermark();


                    // ==================================================
                    // 13. UBAH CANVAS MENJADI FILE FOTO
                    // ==================================================

                    canvas.toBlob(function(blob) {

                        if (!blob) {

                            tampilkanPesan(
                                'Gagal memproses foto.',
                                'danger'
                            );

                            return;

                        }


                        fotoSiapUpload = blob;


                        // Tampilkan preview
                        areaKamera.style.display = 'none';

                        areaPreview.style.display = 'block';


                        // Hentikan kamera setelah foto diambil
                        hentikanKamera();


                        tampilkanPesan(
                            'Foto berhasil diambil. Periksa hasil preview sebelum upload.',
                            'success'
                        );


                        console.log('File foto:', fotoSiapUpload);

                        console.log(
                            'Ukuran foto:',
                            (fotoSiapUpload.size / 1024 / 1024).toFixed(2) + ' MB'
                        );


                    }, 'image/jpeg', 0.92);


                } catch (error) {

                    console.error('Gagal mengambil foto:', error);

                    tampilkanPesan(
                        'Gagal mengambil foto: ' + error.message,
                        'danger'
                    );

                }

            });


            // ==================================================
            // 14. FOTO ULANG
            // ==================================================

            btnUlangi.addEventListener('click', function() {

                fotoSiapUpload = null;

                metadataFoto = null;

                ctx.clearRect(
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                areaPreview.style.display = 'none';

                areaKamera.style.display = 'flex';

                tampilkanPesan('');

                // GPS tidak diambil ulang
                // Gunakan data GPS yang sudah tersimpan
                bukaKamera();

            });


            // ==================================================
            // 15. TOMBOL X - KEMBALI KE HALAMAN DOKUMENTASI
            // ==================================================

            btnCancelFoto.addEventListener('click', function() {

                hentikanKamera();

                fotoSiapUpload = null;

                metadataFoto = null;

                // Data GPS tidak perlu dihapus
                // Agar bisa digunakan lagi ketika kembali ke halaman sebelumnya

                window.location.href =
                    "{{ route('HalamanFotolapharian', $bangunan->id) }}";

            });


            // ==================================================
            // 16. UPLOAD FOTO
            // ==================================================

            btnUpload.addEventListener('click', async function() {

                if (!fotoSiapUpload || !metadataFoto) {

                    tampilkanPesan(
                        'Silakan ambil foto terlebih dahulu.',
                        'warning'
                    );

                    return;

                }


                const formData = new FormData();


                // Data bangunan
                formData.append(
                    'bangunan_id',
                    metadataFoto.bangunan_id
                );


                // File foto
                formData.append(
                    'foto',
                    fotoSiapUpload,
                    'dokumentasi.jpg'
                );


                // Nama proyek
                formData.append(
                    'nama_proyek',
                    metadataFoto.nama_proyek
                );


                // Latitude
                formData.append(
                    'latitude',
                    metadataFoto.latitude
                );


                // Longitude
                formData.append(
                    'longitude',
                    metadataFoto.longitude
                );


                // Akurasi GPS
                formData.append(
                    'akurasi_gps',
                    metadataFoto.akurasi_gps
                );


                // Alamat
                formData.append(
                    'alamat',
                    metadataFoto.alamat
                );


                // Tanggal dan jam WIB
                formData.append(
                    'tanggal_jam',
                    metadataFoto.tanggal_jam
                );


                // ==================================================
                // 17. KIRIM KE LARAVEL
                // ==================================================

                btnUpload.disabled = true;

                btnUpload.textContent = 'Mengunggah...';


                try {

                    const csrfToken = document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content;


                    if (!csrfToken) {

                        throw new Error('CSRF token tidak ditemukan.');

                    }


                    const response = await fetch(
                        "{{ route('dokumentasi.upload') }}", {

                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN': csrfToken,

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

                        throw new Error(
                            result.message || 'Upload gagal.'
                        );

                    }


                    // ==================================================
                    // 18. UPLOAD BERHASIL
                    // ==================================================

                    tampilkanPesan(
                        result.message || 'Foto berhasil diunggah.',
                        'success'
                    );


                    fotoSiapUpload = null;

                    metadataFoto = null;


                    // Hapus data GPS setelah upload berhasil
                    sessionStorage.removeItem('dataKamera');


                    // Hentikan kamera
                    hentikanKamera();


                    // Kembali ke halaman foto laporan harian
                    setTimeout(function() {

                        window.location.href =
                            "{{ route('HalamanFotolapharian', $bangunan->id) }}";

                    }, 1000);


                } catch (error) {

                    console.error('Upload error:', error);

                    tampilkanPesan(
                        error.message,
                        'danger'
                    );


                } finally {

                    btnUpload.disabled = false;

                    btnUpload.textContent = 'Upload Foto';

                }

            });


            // ==================================================
            // 19. HENTIKAN KAMERA KETIKA MENINGGALKAN HALAMAN
            // ==================================================

            window.addEventListener('pagehide', function() {

                hentikanKamera();

            });


            // ==================================================
            // 20. LANGSUNG BUKA KAMERA
            // ==================================================

            bukaKamera();

        });
    </script>
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    {{-- JavaScript kamera diletakkan di sini --}}

</body>

</html>
