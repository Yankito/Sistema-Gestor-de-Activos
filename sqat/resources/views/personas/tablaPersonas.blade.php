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
      color: #007bff;
      cursor: pointer;
      font-size: 10px;
    }
    .dataTables_wrapper .dataTables_fixedHeader {
        overflow: hidden;
        z-index: 1;
    }
    .dataTables_wrapper .dataTables_scrollBody {
        position: relative; /* Asegura que el contenedor de la tabla tenga un z-index menor */
        z-index: 1;
    }
    .filter-container {
        position: fixed;
        z-index: 9999; /* Asegúrate de que esté por encima de otros elementos */
        background-color: white;
        border: 1px solid #ccc;
        padding: 10px;
        display: auto; /* Oculto por defecto */
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
                <!-- Add a "Clear Filters" button -->
                <button id="clear-filters" class="btn btn-danger btn-sm float-right">
                    <i class="fas fa-filter"></i> Limpiar Filtros
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div style = "overflow-x:auto">
                  <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline">
                    <thead>
                      <tr>
                        @foreach(["User","Rut", "Nombre Completo", "Nombre Empresa", "Estado", "Fecha Ingreso Compañia", "Fecha Término Compañia", "Cargo", "Ubicación", "Correo"] as $index => $columna)

                          <th>
                            {{ $columna }}
                            <!-- boton filtro -->
                            <button class="filter-btn" data-index="{{ $index }}">
                              <i class="fas fa-filter"></i>
                            </button>
                            <div class="filter-container" id="filter-{{ $index }}">
                              <input type="text" class="column-search" data-index="{{ $index }}" placeholder="Buscar...">
                              <div class="checkbox-filters" data-index="{{ $index }}"></div>
                            </div>
                          </th>
                        @endforeach
                      </tr>
                      </thead>
                    <tbody>
                      @foreach($personas as $persona)
                          <tr>
                              <td>{{ $persona->user }}</td>
                              <td>{{ $persona->rut }}</td>
                              <td>{{ $persona->nombre_completo }}</td>
                              <td>{{ $persona->nombre_empresa }}</td>
                              <td>{{ $persona->estado_empleado }}</td>
                              <td>{{ $persona->fecha_ing}}</td>
                              <td>{{ $persona->fecha_ter}}</td>
                              <td>{{ $persona->cargo }}</td>
                              <td>{{ $persona->ubicacion }}</td>
                              <td>{{ $persona->correo }}</td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
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

    <script src="{{ asset('js/tablas.js') }}"></script>
@endsection
</html>
