@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
    <!-- CSS de AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <!-- JS de AdminLTE -->
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.7.2/proj4.js"></script> <!-- Incluir la librería proj4js -->

</head>

@section('content')

  <!-- Content Wrapper. Contains page content -->

    <!-- /.content-header -->
    <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="pictures/Logo Empresas Iansa.png" alt="AdminLTELogo" height="100" width="200">
        </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">


            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-lg-5 connectedSortable ui-sortable">
                <div style="cursor: pointer;" onclick="window.location.href='/dashboardUbicacion';">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner text-center">
                        <h3>{{$cantidadActivos}}</h3>
                        <p>Activos</p>
                    </div>
                    <div class="icon" style="cursor: pointer;">
                        <i class="ion ion-laptop"></i>
                    </div>
                    @if($user->es_administrador)
                        <a href="/registrarActivo" class="small-box-footer">Dar activo de alta <i class="fas fa-arrow-circle-right"></i></a>
                    @else
                        <a href="#" class="small-box-footer">       <i class="fas fa-arrow-circle-right"></i></a>
                    @endif
                    </div>
                </div>
                <div class="card bg-gradient-info">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-th mr-1"></i>
                            Cantidad de activos por estado
                        </h3>

                        <div class="card-tools">
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer bg-transparent">

                        <div class="row">
                            @foreach($cantidadPorEstados as $estado => $cantidad)
                                <div class="col-md-6">
                                    <div class="progress-group">
                                        {{ ucfirst(strtolower($estado)) }}
                                        <span class="float-right"><b>{{ $cantidad }}</b>/{{ $cantidadActivos }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: {{ $cantidadActivos != 0 ? ($cantidad / $cantidadActivos) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                    <!-- /.row -->
                    </div>
                    <!-- /.card-footer -->
                </div>
            </div>


            <div class="col-lg-7 connectedSortable ui-sortable">

                <!-- Aquí se incluye el mapa -->
                @include('mapa')
            </div>

        </div>



        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                @foreach($tiposDeActivo as $tipoDeActivo => $cantidad)
                    <div class="col-lg-3 col-6" style="cursor: pointer;" onclick="window.location.href='/dashboardTipo?tipo={{ ucfirst($tipoDeActivo) }}';">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $cantidad }}</h3>
                                <p>{{ ucfirst(mb_strtolower($tipoDeActivo, 'UTF-8')) }}</p>
                            </div>
                            <div class="icon" style="cursor: pointer;">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
    <!-- /.content -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: "{{session('title')}}",
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection




</html>
