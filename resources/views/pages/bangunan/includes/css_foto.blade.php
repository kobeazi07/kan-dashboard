 <style>
     /* ================================
   GENERAL
================================= */

     .kamera-page {
         margin-top: 0 !important;
         padding: 12px 16px 30px;
     }

     .kamera-card {
         border: 0;
         border-radius: 16px;
         overflow: hidden;
         background: #fff;
     }

     .kamera-header {
         margin-bottom: 20px;
     }

     .kamera-header h4 {
         font-size: 22px;
         font-weight: 700;
         color: #212529;
     }

     .kamera-header p {
         color: #6c757d;
         margin-bottom: 0;
         font-size: 14px;
     }

     .label-section {
         font-size: 14px;
         font-weight: 700;
         margin-bottom: 10px;
         color: #343a40;
     }

     .btn-kamera {
         min-height: 48px;
         border-radius: 10px;
         font-weight: 600;
     }


     /* ================================
   INFORMASI BANGUNAN
================================= */

     .bangunan-info {
         background: #f8f9fa;
         border: 1px solid #edf0f2;
         border-radius: 12px;
         padding: 16px;
         margin-bottom: 18px;
     }

     .bangunan-info h5 {
         font-size: 17px;
         font-weight: 700;
         margin-bottom: 8px;
         color: #212529;
     }

     .bangunan-meta {
         display: flex;
         flex-wrap: wrap;
         gap: 8px;
         font-size: 13px;
         color: #6c757d;
     }

     .bangunan-meta span {
         background: #fff;
         border: 1px solid #e9ecef;
         padding: 5px 10px;
         border-radius: 7px;
     }


     /* ================================
   GPS INFORMATION
================================= */

     .kamera-status {
         background: #f8f9fa;
         border: 1px solid #edf0f2;
         border-radius: 12px;
         padding: 15px;
         font-size: 13px;
         overflow-wrap: anywhere;
     }

     .gps-heading {
         display: flex;
         align-items: center;
         gap: 8px;
         font-size: 14px;
         font-weight: 700;
         margin-bottom: 12px;
     }

     .gps-heading i {
         color: #0d6efd;
         font-size: 17px;
     }

     .gps-item {
         display: flex;
         align-items: flex-start;
         gap: 9px;
         padding: 7px 0;
         border-top: 1px solid #e9ecef;
         color: #495057;
     }

     .gps-item i {
         color: #6c757d;
         margin-top: 2px;
         min-width: 15px;
     }


     /* ================================
   CAMERA
================================= */

     .kamera-frame {
         position: relative;
         width: 100%;
         height: min(70vh, 650px);
         min-height: 300px;
         background: #111;
         border-radius: 14px;
         overflow: hidden;

         display: flex;
         align-items: center;
         justify-content: center;
     }

     .kamera-preview {
         display: block;
         width: 100%;
         height: min(70vh, 650px);
         max-height: none;
         min-height: 0;
         object-fit: contain;
         background: #111;
         border-radius: 0;
     }


     /* ================================
   TOMBOL BATAL KAMERA
================================= */

     .btn-camera-cancel {
         position: absolute;
         top: 15px;
         left: 15px;
         z-index: 10;

         width: 42px;
         height: 42px;

         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;

         background: rgba(0, 0, 0, .55);
         color: #fff;
         border: 1px solid rgba(255, 255, 255, .6);

         font-size: 18px;
         padding: 0;
         cursor: pointer;

         transition: background .2s ease;
     }

     .btn-camera-cancel:hover {
         background: rgba(220, 53, 69, .9);
         color: #fff;
     }


     /* ================================
   TOMBOL AMBIL FOTO
   DEFAULT PORTRAIT
   POSISI BAWAH TENGAH
================================= */

     .btn-camera-capture {
         position: absolute;

         top: auto;
         bottom: 25px;
         left: 50%;
         right: auto;

         transform: translateX(-50%);

         width: 74px;
         height: 74px;

         border-radius: 50%;
         border: 5px solid rgba(255, 255, 255, .9);

         background: rgba(255, 255, 255, .85);
         box-shadow: 0 3px 15px rgba(0, 0, 0, .4);

         display: flex;
         align-items: center;
         justify-content: center;

         padding: 0;
         z-index: 10;
         cursor: pointer;

         transition: transform .15s ease;
     }

     .btn-camera-capture::after {
         content: '';
         width: 52px;
         height: 52px;

         border-radius: 50%;
         background: #fff;
         border: 1px solid #ccc;
     }

     .btn-camera-capture:active {
         transform: translateX(-50%) scale(.92);
     }


     /* ================================
   PREVIEW FOTO
================================= */

     #canvasFoto {
         display: block;
         width: 100%;
         height: auto;
         border-radius: 12px;
         background: #eee;
     }

     .preview-container {
         background: #f8f9fa;
         border: 1px solid #edf0f2;
         padding: 12px;
         border-radius: 12px;
     }


     /* ================================
   GALERI FOTO
================================= */

     .galeri-heading {
         font-size: 18px;
         font-weight: 700;
         margin-bottom: 15px;
     }

     .foto-card {
         position: relative;
         height: 100%;
         overflow: hidden;
         border-radius: 12px;
         background: #fff;
         box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
     }

     .foto-card .blog-img {
         position: relative;
         width: 100%;
         height: 220px;
         overflow: hidden;
         border-radius: 12px;
     }

     .foto-card .lightbox-link {
         display: block;
         width: 100%;
         height: 100%;
     }

     .foto-card .rounded-image {
         width: 100%;
         height: 100%;
         object-fit: cover;
         display: block;
         transition: transform .3s ease;
     }

     .foto-card:hover .rounded-image {
         transform: scale(1.04);
     }


     /* ================================
   TOMBOL HAPUS FOTO
================================= */

     .btn-submit-delete {
         position: absolute;
         top: 10px;
         right: 10px;
         z-index: 5;

         width: 38px;
         height: 38px;

         display: flex;
         align-items: center;
         justify-content: center;

         border: 1px solid rgba(255, 255, 255, .7);
         border-radius: 50%;

         background: rgba(220, 53, 69, .95);
         color: #fff;

         font-size: 16px;
         cursor: pointer;

         box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
         transition: all .2s ease;
     }

     .btn-submit-delete:hover {
         background: #bb2d3b;
         color: #fff;
         transform: scale(1.08);
     }

     .btn-submit-delete:active {
         transform: scale(.92);
     }

     .btn-submit-delete:focus {
         outline: none;
         box-shadow: 0 0 0 3px rgba(220, 53, 69, .25);
     }

     .btn-submit-delete i {
         pointer-events: none;
     }


     /* ================================
   EMPTY GALLERY
================================= */

     .empty-gallery {
         padding: 35px 15px;
         text-align: center;
         background: #f8f9fa;
         border: 1px dashed #ced4da;
         border-radius: 12px;
         color: #6c757d;
     }

     .empty-gallery i {
         font-size: 38px;
         display: block;
         margin-bottom: 10px;
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
