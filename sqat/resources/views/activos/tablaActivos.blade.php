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
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.1/css/fixedHeader.bootstrap4.min.css">


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
            background-color: yellow;
        }
        .estado-disponible {
            background-color: #0aa40d;
        }

        .estado-asignado {
            background-color: #0a5964;
        }

        .estado-perdido {
            background-color: gray;
        }

        .estado-robado {
            background-color: black;
        }

        .estado-devuelto {
            background-color: pink;
        }

        .estado-paraBaja {
            background-color: red;
        }

        .estado-donado {
            background-color: blue;
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
                        @foreach($activos as $dato)
                            <tr>
                                <td class="action-btns">
                                @if ($dato->estado === 1) {{-- ADQUIRIDO --}}
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado('{{ $dato->id }}', 2)">
                                        <i class="fas fa-arrow-right"></i> <!-- Pasar a PREPARACIÓN -->
                                    </button>
                                @elseif ($dato->estado === 2) {{-- PREPARACIÓN --}}
                                    <button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado('{{ $dato->id }}', 3)">
                                        <i class="fas fa-arrow-right"></i> <!-- Pasar a DISPONIBLE -->
                                    </button>
                                @elseif ($dato->estado === 3 || $dato->estado === 4) {{-- DISPONIBLE --}}
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo('{{ $dato->id }}')">
                                        <i class="fas fa-edit"></i> <!-- Editar -->
                                    </button>

                                @elseif ($dato->estado === 5 || $dato->estado === 6) {{-- PERDIDO o ROBADO --}}
                                    <button type="button" class="btn btn-success btn-sm" onclick="cambiarEstado('{{ $dato->id }}', 7)">
                                        <i class="fas fa-undo"></i> <!-- Volver a DEVUELTO -->
                                    </button>
                                @elseif ($dato->estado === 7) {{-- DEVUELTO --}}
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo('{{ $dato->id }}')">
                                        <i class="fas fa-edit"></i> <!-- Editar -->
                                    </button>
                                @elseif ($dato->estado === 8 || $dato->estado === 9 || $dato->estado === 10) {{-- Estados finales --}}
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-check-circle"></i> <!-- Estado finalizado -->
                                    </button>
                                @endif


                                </td>
                                <td>{{ $dato->nro_serie }}</td>
                                <td>{{ $dato->marca }}</td>
                                <td>{{ $dato->modelo }}</td>
                                <td>{{ number_format($dato->precio, 0, ',', '.') }}</td>
                                <td>{{ $dato->tipo_de_activo }}</td>
                                <td>
                                    <span class="estado-badge
                                        {{ $dato->estado === 1 ? 'estado-adquirido' : '' }}
                                        {{ $dato->estado === 2 ? 'estado-preparacion' : '' }}
                                        {{ $dato->estado === 3 ? 'estado-disponible' : '' }}
                                        {{ $dato->estado === 4 ? 'estado-asignado' : '' }}
                                        {{ $dato->estado === 5 ? 'estado-perdido' : '' }}
                                        {{ $dato->estado === 6 ? 'estado-robado' : '' }}
                                        {{ $dato->estado === 7 ? 'estado-devuelto' : '' }}
                                        {{ $dato->estado === 8 ? 'estado-paraBaja' : '' }}
                                        {{ $dato->estado === 9 ? 'estado-donado' : '' }}
                                        {{ $dato->estado == 10 ? 'estado-vendido' : '' }}">
                                        {{ $dato->estadoRelation->nombre_estado }}
                                    </span>
                                </td>
                                <td>{{ $dato->usuarioDeActivo->rut ?? '' }}</td>
                                <td>{{ $dato->responsableDeActivo->rut ?? '' }}</td>
                                <td>{{ $dato->ubicacionRelation->sitio }}</td>
                                <td>{{ $dato->ubicacionRelation->soporte_ti }}</td>
                                <td>{{ $dato->justificacion_doble_activo }}</td>
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


    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            @if (isset($activo))
              @include('activos.editarActivo')
            @endif
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

    function deshabilitar(id) {
        const datos = JSON.parse('{!! json_encode($activos) !!}');
        $('#estado').val(activos[id].estado);
        $('#form-cambiarEstado').attr('action', `/activos/deshabilitar/${id}`);
        $('#modal-cambiarEstado').modal('show');
    }

    function cambiarEstado(activoId, nuevoEstado) {
        console.log(activoId, nuevoEstado);
        const datos = JSON.parse('{!! json_encode($estados) !!}');
        Swal.fire({
            title: "¿Estás seguro?",
            text: `El estado del activo cambiará a ${datos.find(estado => estado.id === nuevoEstado).nombre_estado}.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('activo_id').value = activoId;
                document.getElementById('nuevo_estado').value = nuevoEstado;
                document.getElementById('cambiarEstadoForm').submit();
            }
        });
    }


</script>
@endsection
</html>
