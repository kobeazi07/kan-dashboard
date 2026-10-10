<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Project SLF PT. KAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- SEO Optimization -->
    <meta name="description" content="Spark Admin - Premium Bootstrap 5 Admin Dashboard Template">
    <meta name="author" content="Spark Admin Team">

    <!-- Favicon -->
    @include('includes.style')
</head>

<body>

    <!-- ==========================================
         START: Sidebar Component
         Highly polished, dark-green sticky navigation
         ========================================== -->
    @include('includes.sidebar')
    <!-- ==========================================
         END: Sidebar Component
         ========================================== -->


    <!-- ==========================================
         START: Main Content Area
         ========================================== -->
    <div class="main-wrapper">

        <!-- START: Top Navbar Component -->
        @include('includes.navbar')
        <!-- END: Top Navbar Component -->

        <!-- START: Dashboard Header Banner -->
        @yield('konten')
        <!-- END: Main Layout Grid -->

        <!-- START: Footer Component -->
        @include('includes.footer')
        <!-- END: Footer Component -->

    </div>
    <!-- ==========================================
         END: Main Content Area
         ========================================== -->

    <!-- Local Third-Party Libraries Script dependencies -->
    @include('includes.script')
</body>

</html>
