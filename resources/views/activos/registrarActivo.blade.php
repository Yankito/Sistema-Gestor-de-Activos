@extends('layouts.app')
<!doctype html>
<html lang="es">
    <head>
        <title>Registrar Activo</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->

        <link href="{{asset('assets/.css')}}" rel="stylesheet">

    </head>

    @section('content')
        <section class="h-100 gradient-form" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{asset('pictures/Logo Empresas Iansa.png')}}"
                                        style="width: 300px;" alt="logo">
                                </div>

                                <h2>Dar activo de alta</h2>

                                <form action="/activos" method="POST">
                                    @csrf
                                    <div class = "row">
                                        <div class = " col-md-6">
                                            <!-- Nro Serie -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="nro_serie">Nro. Serie</label>
                                                <input type="text" name="nro_serie" id="nro_serie" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = " col-md-6">
                                            <!-- Marca -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="marca">Marca</label>
                                                <input type="text" name="marca" id="marca" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = " col-md-6">
                                            <!-- Modelo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="modelo">Modelo</label>
                                                <input type="text" name="modelo" id="modelo" required class="form-control" />
                                            </div>
                                        </div>

                                        <div class = " col-md-6">
                                            <!-- Tipo de Activo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="tipo_de_activo">Tipo de Activo</label>
                                                <select name="tipo_de_activo" id="tipo_de_activo" class="form-control" required>
                                                    @foreach($tiposDeActivo as $tipoDeActivo)
                                                        <option value="{{$tipoDeActivo->id}}" data-caracteristicas="{{ json_encode($tipoDeActivo->caracteristicasAdicionales) }}">
                                                        {{$tipoDeActivo->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class = " col-md-6">
                                            <!-- Precio -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="precio">Precio</label>
                                                <input type="number" name="precio" id="precio" required class="form-control" />
                                            </div>
                                        </div>

                                        <div class = " col-md-6">
                                            <!-- Ubicación -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="ubicacion">Ubicación</label>
                                                <select name="ubicacion" id="ubicacion" class="form-control" required>
                                                    @foreach($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-outline mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="asignarResponsable" name="asignarResponsable">
                                            <label class="form-check-label" for="asignarResponsable">Asignar Responsable</label>
                                        </div>
                                    </div>
                                    <div class="form-outline mb-4" id="responsableSection" style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label" for="responsable">Responsable</label>
                                            <select name="responsable" id="responsable" select class="form-control select2bs4">
                                                <option value="" disabled selected>Seleccione un responsable</option>
                                                @foreach($personas as $persona)
                                                    <option value="{{$persona->id}}" data-ubicacion-id="{{$persona->ubicacion}}">{{$persona->user}}: {{$persona->nombre_completo}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="asignarUsuarios" name="asignarUsuarios">
                                            <label class="form-check-label" for="asignarUsuarios">Asignar Usuarios</label>
                                        </div>
                                    </div>
                                    <div class="form-outline mb-4" id="usuariosSection" style="display: none;">
                                        <div class="form-group">
                                            <label class="form-label" for="usuarios">Usuarios</label>
                                            <select name="usuarios[]" id="usuarios_select" class="form-control select2bs4" multiple>
                                                @foreach($personas as $persona)
                                                    <option value="{{$persona->id}}">
                                                        {{$persona->nombre_completo}} ({{$persona->user}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Características Adicionales -->
                                    <div id="caracteristicasAdicionalesSection"></div>


                                    <!-- Botón de Enviar -->
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Registrar Activo</button>
                                </form>

                                <a href="/dashboard" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-danger">Volver atrás</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endsection


        @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if(session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'Aceptar'
                });
            </script>
        @endif

        <script>

            document.getElementById('tipo_de_activo').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const caracteristicas = JSON.parse(selectedOption.getAttribute('data-caracteristicas') || '[]');
                const caracteristicasAdicionalesSection = document.getElementById('caracteristicasAdicionalesSection');

                // Limpiar el contenido anterior
                caracteristicasAdicionalesSection.innerHTML = '';

                if (caracteristicas.length > 0) {
                    // Crear el label solo si hay características adicionales
                    const label = document.createElement('label');
                    label.classList.add('form-label');
                    label.style.fontWeight = 'bold';
                    label.style.marginTop = '10px';
                    label.innerText = 'Características Adicionales (opcionales)';
                    caracteristicasAdicionalesSection.appendChild(label);

                    // Agregar los campos de características adicionales
                    caracteristicas.forEach(function(caracteristica) {
                        const div = document.createElement('div');
                        div.classList.add('form-outline', 'mb-4');

                        const label = document.createElement('label');
                        label.classList.add('form-label');
                        label.innerText = caracteristica.nombre_caracteristica;

                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = `caracteristicas[${caracteristica.id}]`;
                        input.classList.add('form-control');

                        div.appendChild(label);
                        div.appendChild(input);
                        caracteristicasAdicionalesSection.appendChild(div);
                    });
                }
            });


            document.getElementById('asignarResponsable').addEventListener('change', function() {
                var responsableSection = document.getElementById('responsableSection');
                var responsableSelect = document.getElementById('responsable');

                if (this.checked) {
                    responsableSection.style.display = 'block';
                } else {
                    responsableSection.style.display = 'none';
                    // Resetea el valor del select antes de enviar el formulario
                    responsableSelect.value = null;
                }
            });

            document.getElementById('responsableSection').addEventListener('click', function() {
                var responsableSection = document.getElementById('responsableSection');
                var responsable = document.getElementById('responsable');

                // Asegura que el select es visible y luego desplázate hacia él
                if (responsableSection.style.display !== 'none') {
                    setTimeout(() => {
                        responsable.focus(); // Enfocar el select
                        responsable.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Desplazar suavemente hacia el select
                    }, 200);
                }
            });

            document.getElementById('asignarUsuarios').addEventListener('change', function() {
                var usuariosSection = document.getElementById('usuariosSection');
                var usuariosSelect = document.getElementById('usuarios');

                if (this.checked) {
                    usuariosSection.style.display = 'block';
                } else {
                    usuariosSection.style.display = 'none';
                    // Resetea el valor del select antes de enviar el formulario
                    usuariosSelect.value = null;
                }
            });

            document.getElementById('usuariosSection').addEventListener('click', function() {
                var usuariosSection = document.getElementById('usuariosSection');
                var usuarios = document.getElementById('usuarios');

                // Asegura que el select es visible y luego desplázate hacia él
                if (usuariosSection.style.display !== 'none') {
                    setTimeout(() => {
                        usuarios.focus(); // Enfocar el select
                        usuarios.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Desplazar suavemente hacia el select
                    }, 200);
                }
            });

            // Resetea el valor del campo 'responsable' antes de enviar el formulario
            document.querySelector('form').addEventListener('submit', function() {
                var asignarResponsable = document.getElementById('asignarResponsable');
                var responsableSelect = document.getElementById('responsable');

                if (!asignarResponsable.checked) {
                    responsableSelect.value = null;  // No envía el valor cuando no está marcado
                }

                var asignarUsuarios = document.getElementById('asignarUsuarios');
                var usuariosSelect = document.getElementById('usuarios');

                if (!asignarUsuarios.checked) {
                    usuariosSelect.value = null;  // No envía el valor cuando no está marcado
                }
            });

            $(document).ready(function () {

                $('#responsable').on('select2:select', function (e) {
                    var selectedOption = e.params.data.element;  // Opción seleccionada
                    var ubicacionId = $(selectedOption).attr('data-ubicacion-id');  // Obtener ID de ubicación

                    console.log("Ubicación seleccionada:", ubicacionId); // Verificar el valor obtenido

                    if (ubicacionId) {
                        $('#ubicacion').val(ubicacionId).trigger('change'); // Actualizar select de ubicación
                    }
                });
            });
        </script>

    <!-- Estilos -->
    <style>
        form .form-label {
            font-size: 15px;
            color: #4b4b4b;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>


@endsection


</html>
