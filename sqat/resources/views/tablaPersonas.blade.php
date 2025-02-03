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
                <div style = "overflow-x:auto">
                  <table id="tabla" class="table">
                    <thead>
                    <tr>
                        @foreach(["Rut", "Nombre de usuario", "Nombres", "Primer Apellido", "Segundo Apellido", "Supervisor", "Empresa", "Estado empleado", "Centro Costo", "Denominacion", "Titulo Puesto", "Fecha Inicio", "Usuario TI", "Ubicacion"] as $index => $columna)
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
                              <td>{{ $persona->rut }}</td>
                              <td>{{ $persona->nombreUsuario }}</td>
                              <td>{{ $persona->nombres }}</td>
                              <td>{{ $persona->primerApellido }}</td>
                              <td>{{ $persona->segundoApellido }}</td>
                              <td>{{ $persona->supervisor }}</td>
                              <td>{{ $persona->empresa }}</td>
                              <td>{{ $persona->estadoEmpleado }}</td>
                              <td>{{ $persona->centroCosto }}</td>
                              <td>{{ $persona->denominacion }}</td>
                              <td>{{ $persona->tituloPuesto }}</td>
                              <td>{{ $persona->fechaInicio }}</td>
                              <td>{{ $persona->usuarioTI }}</td>
                              <td>{{ $persona->ubicacion }}</td>
                          </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                      <th>rut</th>
                      <th>nombreUsuario</th>
                      <th>nombres</th>
                      <th>primerApellido</th>
                      <th>segundoApellido</th>
                      <th>supervisor</th>
                      <th>empresa</th>
                      <th>estadoEmpleado</th>
                      <th>centroCosto</th>
                      <th>denominacion</th>
                      <th>tituloPuesto</th>
                      <th>fechaInicio</th>
                      <th>usuarioTI</th>
                      <th>ubicacion</tr>
                    </tr>
                    </tfoot>
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



<!-- jQuery -->
<script src="vendor/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<!-- AdminLTE App -->
<script src="vendor/adminlte/dist/js/adminlte.min.js?v=3.2.0"></script>
<!-- AdminLTE for demo purposes -->
<script src="vendor/adminlte/dist/js/demo.js"></script>
<script src="{{ asset('js/tablas.js') }}"></script>
</html>
