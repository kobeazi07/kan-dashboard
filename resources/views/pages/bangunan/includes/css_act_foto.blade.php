<style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: #111;
    }

    .camera-screen {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .kamera-frame {
        position: relative;
        width: 100%;
        height: 100%;
        background: #111;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .kamera-preview {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #111;
    }

    /* Tombol X */
    .btn-camera-cancel {
        position: absolute;
        top: max(15px, env(safe-area-inset-top));
        left: 15px;
        z-index: 10;

        width: 42px;
        height: 42px;

        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 50%;

        background: rgba(0, 0, 0, .35);
        color: white;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 25px;
        font-weight: 300;

        cursor: pointer;
    }

    /* Tombol ambil foto */
    .btn-camera-capture {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);

        z-index: 10;

        width: 60px;
        height: 60px;

        border: 5px solid #fff;
        border-radius: 50%;

        background: rgba(255, 255, 255, .8);

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;
    }

    .btn-camera-capture::after {
        content: '';
        width: 42px;
        height: 42px;
        background: white;
        border-radius: 50%;
    }

    .btn-camera-capture:active {
        transform: translateY(-50%) scale(.92);
    }

    /* Preview foto */
    #areaPreview {
        position: fixed;
        inset: 0;
        z-index: 20;
        background: #111;
        padding: 15px;
        overflow: auto;
    }

    #canvasFoto {
        display: block;
        width: 100%;
        max-height: 75vh;
        object-fit: contain;
    }

    .preview-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .preview-actions button {
        flex: 1;
        min-height: 45px;
        border: 0;
        border-radius: 8px;
        font-weight: 600;
    }

    #btnUlangi {
        background: #6c757d;
        color: white;
    }

    #btnUpload {
        background: #0d6efd;
        color: white;
    }

    #pesanUpload {
        position: fixed;
        bottom: 10px;
        left: 10px;
        right: 10px;
        z-index: 30;
    }


    /* ================================
   TABLET & MOBILE
================================= */

    @media (max-width: 767px) {

        .kamera-page {
            padding: 8px;
        }

        .kamera-header h4 {
            font-size: 19px;
        }

        .kamera-card .card-body {
            padding: 14px !important;
        }

        .bangunan-info {
            padding: 13px;
        }

        .bangunan-info h5 {
            font-size: 15px;
        }

        .kamera-frame {
            height: 65svh;
            min-height: 350px;
            max-height: 650px;
        }

        .btn-camera-cancel {
            top: 12px;
            left: 12px;
            width: 40px;
            height: 40px;
        }

        .foto-card .blog-img {
            height: 160px;
        }

        .btn-submit-delete {
            top: 7px;
            right: 7px;
            width: 34px;
            height: 34px;
            font-size: 14px;
        }

        .galeri-heading {
            font-size: 16px;
        }
    }


    /* ================================
   PORTRAIT
   HP BERDIRI
   SHUTTER BAWAH TENGAH
================================= */

    @media (orientation: portrait) {

        .btn-camera-capture {
            top: auto;
            bottom: 25px;
            left: 50%;
            right: auto;

            width: 74px;
            height: 74px;

            transform: translateX(-50%);
        }

        .btn-camera-capture::after {
            width: 52px;
            height: 52px;
        }

        .btn-camera-capture:active {
            transform: translateX(-50%) scale(.92);
        }
    }


    /* ================================
   LANDSCAPE
   HP BERBARING
   SHUTTER KANAN TENGAH
================================= */

    @media (orientation: landscape) {

        .kamera-frame {
            height: 75svh;
            min-height: 220px;
        }

        .btn-camera-capture {
            top: 50%;
            bottom: auto;
            left: auto;
            right: 20px;

            width: 70px;
            height: 70px;

            transform: translateY(-50%);
        }

        .btn-camera-capture::after {
            width: 48px;
            height: 48px;
        }

        .btn-camera-capture:active {
            transform: translateY(-50%) scale(.92);
        }
    }


    /* ================================
   SMALL MOBILE
================================= */

    @media (max-width: 375px) {

        .kamera-page {
            padding: 6px;
        }

        .kamera-frame {
            min-height: 300px;
        }

        .foto-card .blog-img {
            height: 130px;
        }

        .bangunan-meta {
            font-size: 12px;
        }
    }


    /* ================================
   SMALL MOBILE PORTRAIT
================================= */

    @media (max-width: 767px) and (orientation: portrait) {

        .kamera-frame {
            height: 75svh;
            min-height: 350px;
            max-height: 850px;
        }

        .btn-camera-capture {
            bottom: max(20px, env(safe-area-inset-bottom));
            width: 68px;
            height: 68px;
        }

        .btn-camera-capture::after {
            width: 48px;
            height: 48px;
        }
    }


    /* ================================
   SMALL MOBILE LANDSCAPE
================================= */

    @media (max-width: 991px) and (orientation: landscape) {

        .btn-camera-capture {
            right: 14px;
            width: 60px;
            height: 60px;
        }

        .btn-camera-capture::after {
            width: 40px;
            height: 40px;
        }
    }
</style>
