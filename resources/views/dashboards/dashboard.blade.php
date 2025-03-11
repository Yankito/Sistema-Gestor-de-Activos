@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
    <!-- CSS de AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">


  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.7.2/proj4.js"></script> <!-- Incluir la librería proj4js -->

</head>

@section('content')

  <!-- Content Wrapper. Contains page content -->

    <!-- /.content-header -->
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img src="pictures/Logo Empresas Iansa.png" alt="AdminLTELogo" height="100" width="300">
    </div>

    <!-- Main content -->
    @livewire('dashboards.dashboard-filtros', ['vista' => "GENERAL"])

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

    <script>
        function updateUbicacion(id) {
            console.log('cambio: ' + id);
            Livewire.dispatch('cambiarDashboard', ["UBICACION",id]);
        }

        function updateTipoDeActivo(id) {
            console.log('cambio: ' + id);
            Livewire.dispatch('cambiarDashboard', ["TIPO_DE_ACTIVO",id]);
        }

        function updateGeneral() {
            console.log('cambio: GENERAL');
            Livewire.dispatch('cambiarDashboard', ["GENERAL"]);
        }

        document.addEventListener("DOMContentLoaded", function() {
            $('[data-toggle="tooltip"]').tooltip(); // Inicializa los tooltips de Bootstrap
        });
    </script>
@endsection




</html>
