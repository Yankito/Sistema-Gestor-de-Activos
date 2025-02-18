@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iansa | Tabla Personas</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vendor/adminlte/dist/css/adminlte.min.css?v=3.2.0">
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
    .estado-activo {
        background-color: #0aa40d;
    }

    .estado-inactivo {
        background-color: #808080;
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
                <h3 class="card-title">Tabla de personas</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @livewire('personas.tabla-personas')
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
        <div class="modal fade" id="modal-editar-valores-persona">
            <div class="modal-dialog">
            <div class="modal-content">
                @livewire('personas.editar-valores-persona')
            </div>
            </div>
        </div>

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

    <script src="{{ asset('js/tablas.js') }}"></script>
@endsection
</html>
