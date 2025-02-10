<!DOCTYPE html>
<html lang="es">
    <head>
        <link rel="icon" href="{{ asset('pictures/iconoIansa.png') }}" type="image/x-icon">

        <!-- CSS de AdminLTE -->
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

        <!-- JS de AdminLTE -->
        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="vendor/adminlte/dist/css/adminlte.min.css?v=3.2.0">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/daterangepicker/daterangepicker.css">
        <!-- summernote -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/summernote/summernote-bs4.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="vendor/adminlte/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />

        <!-- Leaflet CSS -->
        <link
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            rel="stylesheet"
        />



        <!-- Leaflet JavaScript -->
        <script
            src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            crossorigin="anonymous"
        ></script>


        <!--<link rel="stylesheet" href="vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css"> -->
                <!-- favicon -->
        <link rel="icon" href="{{asset('pictures/iconoIansa.png')}}" type="image/png">

    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="dashboard" class="nav-link">Inicio</a>
            </li>

            </ul>


        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary">
                <!-- Brand Logo lleva al dashboard -->
                <a href="/dashboard" class="brand-link">
                <img src="pictures/Logo Empresas Iansa.png" alt="AdminLTE Logo"  width="200" height="auto"  style="opacity: .8">
                </a>
                <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-2 pb-2 mb-2 d-flex">
                    <div class="image">
                    <img src="pictures/perfil.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                    <a href="#" class="d-block">{{ $user->nombres }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2" style="margin-right: 10px;">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="#" class="nav-link active" style="background-color: #0a5964;">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Menú
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/dashboard" class="nav-link">Inicio</a>
                            </ul>

                            @if($user->es_administrador)
                                <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/register" class="nav-link">Registrar Admin</a>
                                </ul>
                            @endif
                        <!-- tablas -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Tablas
                                <i class="fas fa-angle-left right"></i>
                            </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class = "nav-item">
                                    <a href = "/tablaDatos" class = "nav-link">
                                    <i class="nav-icon fas fa-table"></i>
                                    <p>Datos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/tablaActivos" class="nav-link">
                                    <i class="nav-icon fas fa-laptop"></i>
                                    <p>Activos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/tablaPersonas" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>Personas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- registrar persona -->
                        @if($user->es_administrador)
                            <li class="nav-item">
                                <a href="/registrarActivo" class="nav-link">
                                <i class="nav-icon fas fa-laptop-medical"></i>
                                <p>
                                    Dar activo de alta
                                </p>
                                </a>
                            </li>

                            <!-- registrar persona -->
                            <li class="nav-item">
                                <a href="/registrarPersona" class="nav-link">
                                <i class="nav-icon fas fa-user-plus"></i>
                                <p>
                                    Asignar activo a persona nueva
                                </p>
                                </a>
                            </li>

                            <!-- registrar ubicacion-->
                            <li class="nav-item">
                                <a href="/registrarUbicacion" class="nav-link">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>
                                    Registrar Ubicación
                                </p>
                                </a>
                            </li>

                            <!--importar Excell-->
                            <li class="nav-item">
                                <a href="/importar" class="nav-link">
                                <i class="nav-icon fas fa-file-import"></i>
                                <p>
                                    Importar Datos
                                </p>
                                </a>
                            </li>

                            <!--importar Excell-->
                            <li class="nav-item">
                                <a href="/reportes" class="nav-link">
                                <i class = "nav-icon fas fa-file-export"></i>
                                <p>
                                    Reportes
                                </p>
                                </a>
                            </li>
                        @endif
                        
                            <form action="/logout" method="POST" class= "d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="color: inherit;" onmouseover="this.style.color='red';" onmouseout="this.style.color='#c2c7d0';"> <i class="fas fa-sign-out-alt"> Cerrar sesión</i></button>
                            </form>
                    </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
                <!-- /.sidebar -->
            </div>
        </aside>

        <!-- Aquí se mostrarán las vistas dinámicas -->
        <div class="content-wrapper">
            @yield('content')
        </div>


      <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Rosario Norte 615, Piso 23. Las Condes Santiago - Chile - Tel. 800 540 099 .</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    <!-- ./wrapper -->
    </div>

        <!-- jQuery -->
        <script src="vendor/adminlte/plugins/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="vendor/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
        $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- ChartJS -->
        <script src="vendor/adminlte/plugins/chart.js/Chart.min.js"></script>
        <!-- Sparkline -->
        <script src="vendor/adminlte/plugins/sparklines/sparkline.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="vendor/adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
        <!-- daterangepicker -->
        <script src="vendor/adminlte/plugins/moment/moment.min.js"></script>
        <script src="vendor/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="vendor/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Summernote -->
        <script src="vendor/adminlte/plugins/summernote/summernote-bs4.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="vendor/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="vendor/adminlte/dist/js/adminlte.js?v=3.2.0"></script>


        <!-- DataTables  & Plugins -->
        <script src="vendor/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="vendor/adminlte/plugins/jszip/jszip.min.js"></script>
        <script src="vendor/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="vendor/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

        <!-- Select2 -->
        <script src="vendor/adminlte/plugins/select2/js/select2.full.min.js"></script>

        <!-- Highcharts -->
        <script src="https://code.highcharts.com/maps/highmaps.js"></script>
        <script src="https://code.highcharts.com/mapdata/countries/cl/cl-all.js"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="vendor/adminlte/dist/js/pages/dashboard.js"></script>


        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

            })

        </script>

        <style>
            .required-asterisk {
                color: red;
            }
        </style>




</body>
</html>
