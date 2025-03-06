@extends('layouts.app')

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
                                <button class="btn btn-primary btn-block mb-3" type="submit">Registrar Tipo</button>
                            </form>
                            <a href="/dashboard" class="btn btn-outline-danger">Volver atrás</a>

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
                                                    </tr>
                                                </thead>
                                                <tbody id="tipoActivoTable">
                                                    @foreach($tiposActivo as $tipo)
                                                        <tr>
                                                            <td>{{ $tipo->nombre }}</td>
                                                            <td>
                                                                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit"
                                                                    style="cursor: pointer;"
                                                                    data-toggle="modal"
                                                                    data-target="#modalEditarTipoActivo"
                                                                    data-id="{{ $tipo->id }}"
                                                                    data-caracteristicas="{{ json_encode($tipo->caracteristicasAdicionales) }}"
                                                                    >
                                                                </i>
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
                    <div class="modal-body">
                        <ul id="listaCaracteristicas" class="list-group">
                            <!-- Aquí se agregarán dinámicamente las características -->
                        </ul>
                         <!-- Agregar el select de tags -->
                        <div class="form-group mt-3">
                            <label for="caracteristicasAdicionalesModal">Añadir Características Adicionales</label>
                            <select name="caracteristicasAdicionales[]" id="caracteristicasAdicionalesModal" class="form-control" multiple="multiple"></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script>
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
        $(document).ready(function () {
            $('.toggle-edit').click(function () {
                console.log('Clicked on pencil icon');  // Verifica si se dispara el evento
                let caracteristicas = $(this).data('caracteristicas');
                $('#listaCaracteristicas').empty();  // Limpiar las características previas

                if (caracteristicas.length > 0) {
                    caracteristicas.forEach(caracteristica => {
                        $('#listaCaracteristicas').append(`<li class="list-group-item">${caracteristica.nombre_caracteristica}</li>`);
                    });
                } else {
                    $('#listaCaracteristicas').append(`<li class="list-group-item text-muted">No hay características adicionales.</li>`);
                }

                // Usar AdminLTE para abrir el modal
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
    </style>

@endsection
