@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tabla Activos</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        .filter-container {
        display: none;
        position: absolute;
        background:white;
        padding: 10 px;
        border: 1px solid #d2d6de;
        z-index: 10;
        }
        .filter-btn {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        font-size: 10px;
        }
        .estado-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: bold;
            color: white;
            text-align: center;
            min-width: 80px;
        }

        .estado-adquirido {
            background-color: purple;
        }

        .estado-preparacion {
            background-color: gray;
        }
        .estado-disponible {
            background-color: #0aa40d;
        }

        .estado-asignado {
            background-color: #0a5964;
        }

        .estado-perdido {
            background-color: #808080;
        }

        .estado-robado {
            background-color: #000000;
        }

        .estado-devuelto {
            background-color: #0a5964;
        }

        .estado-paraBaja {
            background-color: #ff4d4d;
        }

        .estado-donado {
            background-color: #007bff;
        }

        .estado-vendido {
            background-color: green;
        }

    </style>
</head>
    @section('content')
    <section class = "content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tabla de activos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @livewire('tabla-activos')
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>


    <div class="modal fade" id="modal-editar-estados-activos">
        <div class="modal-dialog">
          <div class="modal-content">
              @livewire('editar-estados-activo')
          </div>
        </div>
    </div>
    <div class="modal fade" id="modal-editar-valores-activos">
        <div class="modal-dialog">
          <div class="modal-content">
              @livewire('editar-valores-activo')
          </div>
        </div>
    </div>

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

        <form id="cambiarEstadoForm" action="/activos/cambiarEstado" method="POST">
            @csrf
            <input type="hidden" name="activo_id" id="activo_id">
            <input type="hidden" name="nuevo_estado" id="nuevo_estado">
        </form>
    @endsection


@section('scripts')
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

<!-- Page specific script -->
<script src="{{ asset('js/tablas.js') }}"></script>

<script>


    function obtenerClaseEstado(estado) {
        switch(estado) {
            case 1: return 'estado-adquirido';
            case 2: return 'estado-preparacion';
            case 3: return 'estado-disponible';
            case 4: return 'estado-asignado';
            case 5: return 'estado-perdido';
            case 6: return 'estado-robado';
            case 7: return 'estado-devuelto';
            case 8: return 'estado-paraBaja';
            case 9: return 'estado-donado';
            case 10: return 'estado-vendido';
            default: return '';
        }
    }

</script>
@endsection
</html>
