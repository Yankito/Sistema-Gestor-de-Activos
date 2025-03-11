@extends('layouts.app')
<!doctype html>
<html lang="es">
<head>
      <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
</head>
@section('content')
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="card-body p-md-5 mx-md-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('pictures/Logo Empresas Iansa.png') }}" style="width: 300px;" alt="logo">
                            </div>
                            <h2>Registrar Tipo de Activo</h2>

                            <form action="/tipos-activo" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="nombre">Nombre del Tipo</label>
                                            <input type="text" name="nombre" id="nombre" required class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="caracteristicasAdicionales">Características Adicionales</label>
                                            <select name="caracteristicasAdicionales[]" id="caracteristicasAdicionales" class="form-control" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-block mb-3" type="submit" style="background: #005856; border: #005856;">Registrar Tipo</button>
                            </form>

                            <!-- Buscador -->
                            <div class="row d-flex justify-content-center mt-5">
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title text-center">Lista de Tipos de Activo</h4>

                                            <!-- Input de búsqueda -->
                                            <div class="mb-3">
                                                <input type="text" id="searchInput" class="form-control" placeholder="Buscar tipo de activo...">
                                            </div>

                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Características Adicionales</th> <!-- Nueva columna -->
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tipoActivoTable">
                                                    @foreach($tiposActivo as $tipo)
                                                        <tr>
                                                            <td>{{ $tipo->nombre }}</td>
                                                            <td>
                                                                @if($tipo->caracteristicasAdicionales->count() > 0)
                                                                    <ul>
                                                                        @foreach($tipo->caracteristicasAdicionales as $caracteristica)
                                                                            <li>{{ $caracteristica->nombre_caracteristica }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <span class="text-muted">Sin características adicionales</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <form class="mr-2">
                                                                        <button type="button" class="btn btn-info">
                                                                            <i class="fas fa-pencil-alt toggle-edit"
                                                                                style="cursor: pointer;"
                                                                                data-toggle="modal"
                                                                                data-target="#modalEditarTipoActivo"
                                                                                data-id="{{ $tipo->id }}"
                                                                                data-caracteristicas="{{ json_encode($tipo->caracteristicasAdicionales) }}">
                                                                            </i>
                                                                        </button>
                                                                    </form>
                                                                    <form action="{{ route('tipos-activo.destroy', $tipo->hashed_id) }}" method="POST" id="delete-form-{{ $tipo->hashed_id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $tipo->hashed_id }}')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if($tiposActivo->isEmpty())
                                                <p class="text-center">No hay tipos de activos registrados.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Script de búsqueda -->
                            <script>
                                document.getElementById("searchInput").addEventListener("keyup", function() {
                                    let filter = this.value.toLowerCase();
                                    let rows = document.querySelectorAll("#tipoActivoTable tr");

                                    rows.forEach(row => {
                                        let text = row.textContent.toLowerCase();
                                        row.style.display = text.includes(filter) ? "" : "none";
                                    });
                                });

                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para mostrar características adicionales -->
        <div class="modal fade" id="modalEditarTipoActivo" tabindex="-1" aria-labelledby="modalEditarTipoActivoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarTipoActivoLabel">Características del Tipo de Activo</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/agregarCaracteristicas" method="POST">
                        @csrf
                        <div class="modal-body">
                            <ul id="listaCaracteristicas" class="list-group">
                                <!-- Aquí se agregarán dinámicamente las características -->
                            </ul>
                            <!-- Agregar el select de tags -->
                            <div class="form-group mt-3">
                                <label for="caracteristicasAdicionalesModal">Añadir Características Adicionales</label>
                                <select name="caracteristicasAdicionales[]" id="caracteristicasAdicionalesModal" class="form-control" multiple="multiple"></select>
                            </div>

                            <input type="hidden" name="tipoActivoId" id="tipoActivoId">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')

    @if(session('success'))
        <script>
            $(document).ready(function () {
                    toastr.success("{{ session('success') }}");
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            $(document).ready(function () {
                toastr.error("{{ session('error') }}");
            });
        </script>
    @endif



    <script>
        function confirmDelete(tipoId) {
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar este tipo de activo?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn-confirm',
                    cancelButton: 'btn-cancel'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviamos el formulario
                    document.getElementById('delete-form-' + tipoId).submit();
                }
            });
        }

        function confirmDeleteCarac(tipoId) {
            Swal.fire({
                title: '¿Estás seguro de que deseas eliminar esta característica adicional?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn-confirm',
                    cancelButton: 'btn-cancel'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviamos el formulario
                    document.getElementById('deleteCarac-form-' + tipoId).submit();
                }
            });
        }


        $(document).ready(function() {
            $('#caracteristicasAdicionales').select2({
                tags: true,  // Permite agregar texto libre como etiquetas
                tokenSeparators: [','], // Separar etiquetas por comas
                placeholder: "Añadir características",
                width: '100%', // Ajusta el ancho
                minimumResultsForSearch: Infinity, // Desactiva el buscador
                createTag: function(params) {
                    // Asegura que no se muestren las etiquetas en el dropdown
                    return { id: params.term, text: params.term, newTag: true };
                },
                allowClear: true,  // Permite limpiar la selección
                dropdownCssClass: "select2-no-results" // Oculta la sección de resultados
            });

            // Establecer el display del dropdown a 'none' para ocultarlo por completo
            $('#caracteristicasAdicionales').on('select2:open', function (e) {
                $(".select2-dropdown").css("display", "none");
            });
        });
        $(document).ready(function() {
            $('#caracteristicasAdicionalesModal').select2({
                tags: true,  // Permite agregar texto libre como etiquetas
                tokenSeparators: [','], // Separar etiquetas por comas
                placeholder: "Añadir características",
                width: '100%', // Ajusta el ancho
                minimumResultsForSearch: Infinity, // Desactiva el buscador
                createTag: function(params) {
                    // Asegura que no se muestren las etiquetas en el dropdown
                    return { id: params.term, text: params.term, newTag: true };
                },
                allowClear: true,  // Permite limpiar la selección
                dropdownCssClass: "select2-no-results" // Oculta la sección de resultados
            });

            // Establecer el display del dropdown a 'none' para ocultarlo por completo
            $('#caracteristicasAdicionalesModal').on('select2:open', function (e) {
                $(".select2-dropdown").css("display", "none");
            });
        });
        $(document).ready(function () {
            $('.toggle-edit').click(function () {
                console.log('Clicked on pencil icon');  // Verifica si se dispara el evento

                let id = $(this).data('id');  // Obtener el ID del activo
                let caracteristicas = $(this).data('caracteristicas');

                // Asignar el ID al campo oculto en el formulario del modal
                $('#tipoActivoId').val(id);

                $('#listaCaracteristicas').empty();  // Limpiar las características previas

                console.log(caracteristicas);
                if (caracteristicas.length > 0) {
                    caracteristicas.forEach(caracteristica => {
                        $('#listaCaracteristicas').append(`
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                ${caracteristica.nombre_caracteristica}
                                <form action="/caracteristicaAdicional/${caracteristica.hashed_id}" method="POST" id="deleteCarac-form-${caracteristica.id}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteCarac('${caracteristica.id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                </div>
                        </li>
                        `);
                    });
                } else {
                    $('#listaCaracteristicas').append(`<li class="list-group-item text-muted">No hay características adicionales.</li>`);
                }

                // Abrir el modal
                $('#modalEditarTipoActivo').modal('show');
            });
        });




    </script>

    <style>
        .select2-container .select2-selection__choice {
            background-color: #00C01E !important;  /* Color de fondo de la etiqueta */
            color: white !important;  /* Color de texto de la etiqueta */
            border: none !important;  /* Eliminar borde */
        }

        .select2-selection__choice__remove{
            color: white !important;  /* Color de texto de la 'x' de la etiqueta */
        }

        .btn-confirm {
            background-color: #005856 !important;
            color: white !important;
            border: none !important;
        }

        .btn-cancel {
            background-color: #ccc !important;
            color: white !important;
            border: none !important;
        }
    </style>

@endsection

</html>
