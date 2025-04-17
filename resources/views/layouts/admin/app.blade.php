<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Dashboard')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    {{-- Ganti Icon --}}
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.admin.sidebar')
        <!-- End Sidebar -->

        <!-- Main Panel -->
        <div class="main-panel">
            <div class="main-header">
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        {{-- Search Bar --}}
                        @include('layouts.admin.navbar.searchbar')

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <!--Notification -->
                            @include('layouts.admin.navbar.notification')
                            {{-- End Notification --}}

                            {{-- Quick Action --}}
                            @include('layouts.admin.navbar.quickaction')
                            <!-- End Quick Action -->

                            {{-- profile --}}
                            @include('layouts.admin.navbar.profile')
                            <!-- End Profile -->
                        </ul>

                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">

                    {{-- Header Halaman --}}
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Dashboard</h3>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                            <a href="#" class="btn btn-primary btn-round">Tambah Akun Dosen</a>
                        </div>
                    </div>

                    {{-- Komponen --}}
                    <div class="row">
                        {{-- Visitor section --}}
                        @include('layouts.admin.partials.visitor')
                        {{-- End Visitor section --}}

                        {{-- subscriber section --}}
                        @include('layouts.admin.partials.subscriber')
                        {{-- End subscriber section --}}

                        {{-- Sales section --}}
                        @include('layouts.admin.partials.sales')
                        {{-- End Sales section --}}

                        {{-- Order section --}}
                        @include('layouts.admin.partials.order')
                        {{-- End Order section --}}

                    </div>

                    {{-- upper main --}}
                    <div class="row">
                        {{-- user statistics --}}
                        @include('layouts.admin.main.upperMain.userstatistics')
                        {{-- end user statistics --}}

                        {{-- Daily Sales --}}
                        @include('layouts.admin.main.upperMain.dailysales')
                        {{-- end Daily Sales --}}
                    </div>

                    {{-- Lower Main --}}
                    <div class="row">
                        <div class="col-md-12">
                            {{-- Grafik --}}
                            <div class="card card-round">
                                <!-- user geolocation -->
                                @include('layouts.admin.main.lowerMain.headercard')
                                <!-- end user geolocation -->
                                
                                <!--Table-->
                                @include('layouts.admin.main.lowerMain.grafik')
                            </div>
                            {{-- End Grafik --}}
                        </div>
                    </div>

                    <div class="row">
                        {{-- New Customer --}}
                        @include('layouts.admin.main.lowerMain.customer')
                        {{-- End New Customer --}}

                        {{-- Transaction History --}}
                        @include('layouts.admin.main.lowerMain.transactionhistory')
                        {{-- End Transaction History --}}
                    </div>
                </div>
            </div>

            <!-- Footer -->
            @include('layouts.admin.footer')
            <!-- End Footer -->

        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>
    
    <script>
        $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#177dff",
            fillColor: "rgba(23, 125, 255, 0.14)",
        });

        $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#f3545d",
            fillColor: "rgba(243, 84, 93, .14)",
        });

        $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#ffa534",
            fillColor: "rgba(255, 165, 52, .14)",
        });
    </script>
</body>

</html>
