@extends('layouts.app')
<!doctype html>
<html lang="es">
    <head>
        <title>Subdashboard</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link href="{{asset('assets/estiloLogin.css')}}" rel="stylesheet">


        <style>
            .dropdown-menu {
                max-height: 200px; /* Ajusta la altura según necesites */
                overflow-y: auto;
            }
        </style>


    </head>

    @section('content')

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="pictures/Logo Empresas Iansa.png" alt="AdminLTELogo" height="100" width="200">
    </div>

    <section class="content">

        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard de {{$ubicacion->sitio}}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a class="nav-link me-2" data-toggle="dropdown" href="#">
                            <i class="fas fa-th mr-1"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            @foreach ($ubicaciones as $data)
                                <a href="#" class="dropdown-item" onclick="updateUbicacion('{{ $data->id }}')">
                                    {{$data->sitio}}
                                </a>
                            @endforeach
                            <form id="update-ubicacion-form" action="{{ route('actualizar.dashboardUbicacion') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="ubicacion_id" id="ubicacion_id" value="">
                            </form>
                            <a href="/dashboard" class="dropdown-item dropdown-footer">Dashboard General</a>
                        </div><!-- /.col -->
                    </div>

                </div>
            </div>
            <div class="col-sm-6 d-flex align-items-center">


            </div><!-- /.container-fluid -->
        </div>

        <div class="card bg-gradient-info">
            <div class="card-header border-0">
            <h3 class="card-title">
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

            <div class="col-md-6">
                <div class="row">
                    @foreach($cantidadPorEstados as $estado => $cantidad)
                        <div class="col-md-6">
                            <div class="progress-group">
                                {{$estado}}
                                <span class="float-right"><b>{{ $cantidad }}</b>/{{ $cantidadActivos }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: {{ $cantidadActivos != 0 ? ($cantidad / $cantidadActivos) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


            </div>

            <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>

        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                @foreach($tiposDeActivo as $tipoDeActivo => $cantidad)
                    <div class="col-lg-3 col-6" style="cursor: pointer;" onclick="updateTipoDeActivo('{{ ucfirst($tipoDeActivo)}}')">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $cantidad }}</h3>
                                <p>{{ $tipoDeActivo }}</p>
                            </div>
                            <div class="icon" style="cursor: pointer;">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
                <form id="update-tipoDeActivo-form" action="{{ route('actualizar.dashboardTipo') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="tipoDeActivo_id" id="tipoDeActivo_id" value="">
                </form>
            </div>
        </div>
    </section>
    @endsection
</html>

<script>
    function updateUbicacion(id) {
        document.getElementById('ubicacion_id').value = id;
        document.getElementById('update-ubicacion-form').submit();
    }

    function updateTipoDeActivo(id) {
        document.getElementById('tipoDeActivo_id').value = id;
        document.getElementById('update-tipoDeActivo-form').submit();
    }
</script>
