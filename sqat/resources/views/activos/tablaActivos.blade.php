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

        .estado-disponible {
            background-color: green;
        }

        .estado-asignado {
            background-color: red;
        }

        .estado-robado {
            background-color: black;
        }

        .estado-paraBaja {
            background-color: orange;
        }

        .estado-donado {
            background-color: blue;
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
                  <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación"] as $index => $columna)
                                <th>
                                {{ $columna }}
                                <!-- boton filtro -->
                                <button class="filter-btn" data-index="{{ $index + 1 }}">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <div class="filter-container" id="filter-{{ $index + 1}}">
                                    <input type="text" class="column-search" data-index="{{ $index + 1}}" placeholder="Buscar...">
                                    <div class="checkbox-filters" data-index="{{ $index + 1}}"></div>
                                </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datos as $dato)
                            <tr>
                                <td class="action-btns">
                                    @if ($dato->estado === 'DISPONIBLE' || $dato->estado === 'ASIGNADO')
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default"
                                            onclick="cargarActivo('{{ $dato->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success btn-sm" onclick="reactivarActivo('{{ $dato->id }}')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deshabilitar('{{ $dato->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                <td>{{ $dato->nro_serie }}</td>
                                <td>{{ $dato->marca }}</td>
                                <td>{{ $dato->modelo }}</td>
                                <td>{{ number_format($dato->precio, 0, ',', '.') }}</td>
                                <td>{{ $dato->tipo_de_activo }}</td>
                                <td>
                                    <span class="estado-badge
                                        {{ $dato->estado === 'DISPONIBLE' ? 'estado-disponible' : '' }}
                                        {{ $dato->estado === 'ASIGNADO' ? 'estado-asignado' : '' }}
                                        {{ $dato->estado === 'ROBADO' ? 'estado-robado' : '' }}
                                        {{ $dato->estado === 'PARA BAJA' ? 'estado-paraBaja' : '' }}
                                        {{ $dato->estado === 'DONADO' ? 'estado-donado' : '' }}">
                                        {{ $dato->estado }}
                                        @if($dato->estado === 'ROBADO')
                                            <i class="fas fa-skull-crossbones"></i>
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $dato->rut_usuario}}</td>
                                <td>{{ $dato->rut_responsable}}</td>
                                <td>{{ $dato->sitio }}</td>
                                <td>{{ $dato->soporte_ti }}</td>
                                <td>{{ $dato->justificacion_doble_activo }}</td>
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

    <div class="modal fade" id="modal-cambiarEstado">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar Estado</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-cambiarEstado" method="POST">
                    @csrf
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="ASIGNADO" disabled>Asignado</option>
                        <option value="DISPONIBLE" disabled>Disponble</option>
                        <option value="ROBADO">Robado</option>
                        <option value="PARA BAJA">Para baja</option>
                        <option value="DONADO">Donado</option>
                    </select>
                    <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
            </div>
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
    function deshabilitar(id) {
        const datos = JSON.parse('{!! json_encode($datos) !!}');
        $('#estado').val(datos[id].estado);
        $('#form-cambiarEstado').attr('action', `/activos/deshabilitar/${id}`);
        $('#modal-cambiarEstado').modal('show');
    }

    function reactivarActivo(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Este activo será reactivado.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, reactivar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/activos/reactivar/${id}`,
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        Swal.fire("Reactivado", "El activo ha sido reactivado.", "success")
                            .then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire("Error", "No se pudo reactivar el activo.", "error");
                    }
                });
            }
        });
    }


</script>

</html>
