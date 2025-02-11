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
        $.ajax({
            url: "/activos/cambiarEstado",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                activo_id: activoId,
                nuevo_estado: nuevoEstado
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar la fila correspondiente
                    const fila = $('tr[data-id="' + activoId + '"]');
                    console.log(response);
                    fila.find('td').each(function(index) {
                        console.log(index);
                        switch(index) {
                            case 0:
                                if (response.activoModificado.estado === 1) {
                                    $(this).html('<button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado(\'' + activoId + '\', 2)"><i class="fas fa-arrow-right"></i></button>');
                                } else if (response.activoModificado.estado === 2) {
                                    $(this).html('<button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado(\'' + activoId + '\', 3)"><i class="fas fa-arrow-right"></i></button>');
                                } else if (response.activoModificado.estado === 3 || response.activoModificado.estado === 4) {
                                    $(this).html('<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo(\'' + activoId + '\')"><i class="fas fa-edit"></i></button>');
                                } else if (response.activoModificado.estado === 5 || response.activoModificado.estado === 6) {
                                    $(this).html('<button type="button" class="btn btn-success btn-sm" onclick="cambiarEstado(\'' + activoId + '\', 7)"><i class="fas fa-undo"></i></button>');
                                } else if (response.activoModificado.estado === 7) {
                                    $(this).html('<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo(\'' + activoId + '\')"><i class="fas fa-edit"></i></button>');
                                } else if (response.activoModificado.estado === 8 || response.activoModificado.estado === 9 || response.activoModificado.estado === 10) {
                                    $(this).html('<button type="button" class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle"></i></button>');
                                }
                            case 1:
                                $(this).html(response.activoModificado.numero_serie);
                                break;
                            case 2:
                                $(this).html(response.activoModificado.marca);
                                break;
                            case 3:
                                $(this).html(response.activoModificado.modelo);
                                break;
                            case 4:
                                $(this).html(response.activoModificado.precio);
                                break;
                            case 5:
                                $(this).html(response.activoModificado.tipo);
                                break;
                            case 6:
                                $(this).html('<span class="estado-badge ' + obtenerClaseEstado(nuevoEstado) + '">' + response.activoModificado.estado_relation.nombre_estado + '</span>');
                                break;
                            case 7:
                                $(this).html(response.activoModificado.usuario);
                                break;
                            case 8:
                                $(this).html(response.activoModificado.responsable);
                                break;
                            case 9:
                                $(this).html(response.activoModificado.sitio);
                                break;
                            case 10:
                                $(this).html(response.activoModificado.soporte_ti);
                                break;
                            case 11:
                                $(this).html(response.activoModificado.justificacion);
                                break;
                        }
                    });
                    Swal.fire("Éxito", "Estado cambiado correctamente", "success");
                } else {
                    Swal.fire("Error", "No se pudo cambiar el estado", "error");
                }
            },
            error: function() {
                Swal.fire("Error", "Error de conexión con el servidor", "error");
            }
        });
    }

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
