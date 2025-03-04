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


        <style>
            .dropdown-menu {
                max-height: 200px; /* Ajusta la altura según necesites */
                overflow-y: auto;
            }
        </style>


    </head>

    @section('content')

        <div class="preloader flex-column justify-content-center align-items-center">
            <img src="pictures/Logo Empresas Iansa.png" alt="AdminLTELogo" height="100" width="200">
        </div>
        @section('navbar-custom')
            <div class="col-sm-12">
                <div class="breadcrumb float-sm-right">
                    <a class="nav-link me-2" data-toggle="dropdown" href="#">
                        <i class="fas fa-th mr-1"></i>
                        Cambiar ubicación
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
                        <a href="/dashboard" class="dropdown-item dropdown-footer">DASHBOARD GENERAL</a>
                    </div><!-- /.col -->
                </div>

            </div>
        @endsection

        @livewire('dashboards.dashboard-filtros', ['vista' => 'UBICACION', 'valor' => $ubicacion->id])
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

    document.addEventListener("DOMContentLoaded", function() {
        $('[data-toggle="tooltip"]').tooltip(); // Inicializa los tooltips de Bootstrap
    });
</script>
