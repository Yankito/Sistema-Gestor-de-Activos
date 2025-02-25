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

        <link href="{{asset('assets/estiloLogin.css')}}" rel="stylesheet">

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
                                                    <option value="LAPTOP">LAPTOP</option>
                                                    <option value="DESKTOP">DESKTOP</option>
                                                    <option value="MONITOR">MONITOR</option>
                                                    <option value="IMPRESORA">IMPRESORA</option>
                                                    <option value="CELULAR">CELULAR</option>
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
                                                    <option value="{{$persona->id}}" data-ubicacion-id="{{$persona->ubicacion}}">{{$persona->rut}}: {{$persona->nombre_completo}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

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

            // Resetea el valor del campo 'responsable' antes de enviar el formulario
            document.querySelector('form').addEventListener('submit', function() {
                var asignarResponsable = document.getElementById('asignarResponsable');
                var responsableSelect = document.getElementById('responsable');

                if (!asignarResponsable.checked) {
                    responsableSelect.value = null;  // No envía el valor cuando no está marcado
                }
            });

            $(document).ready(function () {
                $('#responsable').select2(); // Inicializa Select2 si no lo está

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
    @endsection

    <!-- Estilos -->
    <style>
        form .form-label {
            font-size: 15px;
            color: #4b4b4b;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>




</html>
