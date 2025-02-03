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
                <div style = "overflow-x:auto;">
                  <table id="tabla" class="table">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación"] as $index => $columna)
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
                        @foreach($datos as $dato)
                            <tr>
                                <td class="action-btns">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default"
                                onclick="cargarActivo({{ $dato->id }})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                    <button class="btn btn-primary btn-sm" onclick="editar({{ $dato->id }})">
                                    <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminar({{ $dato->id }})">
                                    <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </td>
                                <td>{{ $dato->nroSerie }}</td>
                                <td>{{ $dato->marca }}</td>
                                <td>{{ $dato->modelo }}</td>
                                <td>{{ $dato->precio }}</td>
                                <td>{{ $dato->tipoDeActivo }}</td>
                                <td>{{ $dato->estado }}</td>
                                <td>{{ $dato->rutUsuario}}</td>
                                <td>{{ $dato->rutResponsable}}</td>
                                <td>{{ $dato->sitio }}</td>
                                <td>{{ $dato->soporteTI }}</td>
                                <td>{{ $dato->justificacionDobleActivo }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                      <th>Acciones</th> <!-- Nueva columna para los botones -->
                      <th>Número de serie</th>
                      <th>Marca</th>
                      <th>Modelo</th>
                      <th>Precio</th>
                      <th>Tipo</th>
                      <th>Estado</th>
                      <th>Usuario</th>
                      <th>responsable</th>
                      <th>Sitio</th>
                      <th>Soporte TI</th>
                      <th>Justificación</th>
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


    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            @if (isset($activo))
              @include('activos.editarActivo')
            @endif
          </div>
        </div>
      </div>

    @endsection



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

<!-- AdminLTE for demo purposes -->
<script src="vendor/adminlte/dist/js/demo.js"></script>
<!-- Page specific script -->

<script src="{{ asset('js/tablas.js') }}"></script>

<script>
    function cargarActivo(id) {
        $.ajax({
            url: `/activos/${id}/editar`, // Ruta para obtener el activo por ID
            type: 'GET',
            success: function(data) {
                $('#modal-default .modal-content').html(data); // Carga el contenido del modal con la vista `editarActivo`
            },
            error: function() {
                alert('Error al cargar los datos del activo.');
            }
        });
    }


  function editar(id) {
    $.ajax({
      url: 'editarActivo/' + id,
      type: 'GET',
      success: function (data) {
        $("#editarContenido").html(data); // Carga la vista en el modal
        $("#editarModal").modal("show"); // Muestra el modal
      },
      error: function () {
        alert("Error al cargar la vista de edición.");
      },
    });
  }

  function eliminar(id) {
    // Aquí puedes agregar la lógica para eliminar el elemento
    if (confirm('¿Estás seguro de que quieres eliminar este activo?')) {
      alert('Eliminar: ' + id);
    }
  }
</script>

</html>
