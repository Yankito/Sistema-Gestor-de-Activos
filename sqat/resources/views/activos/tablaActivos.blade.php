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
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.1/css/fixedHeader.bootstrap4.min.css">

    <style>
        .filter-container {
            display: none;
            background: white;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            z-index: 1000;
            min-width: 200px;
            max-height: 300px;
            overflow-y: auto;
        }
        .filter-btn {
            background: none;
            border: none;
            color: #005856;
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
            background-color: #00b5c4;
        }

        .estado-preparacion {
            background-color: gray;
        }
        .estado-disponible {
            background-color: #00C01E;
        }

        .estado-asignado {
            background-color: #005856;
        }

        .estado-perdido {
            background-color: #808080;
        }

        .estado-robado {
            background-color: #000000;
        }

        .estado-devuelto {
            background-color: #005856;
        }

        .estado-paraBaja {
            background-color: #e22551;
        }

        .estado-donado {
            background-color: #00b5c4;
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

                <!-- Add a "Clear Filters" button -->
                <button id="clear-filters" class="btn btn-danger btn-sm float-right">
                    <i class="fas fa-filter"></i> Limpiar Filtros
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @livewire('activos.tabla-activos')
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
              @livewire('activos.editar-estados-activo')
          </div>
        </div>
    </div>
    <div class="modal fade" id="modal-editar-valores-activos">
        <div class="modal-dialog">
          <div class="modal-content">
              @livewire('activos.editar-valores-activo')
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
<script src="https://cdn.datatables.net/fixedheader/3.2.1/js/dataTables.fixedHeader.min.js"></script>

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
