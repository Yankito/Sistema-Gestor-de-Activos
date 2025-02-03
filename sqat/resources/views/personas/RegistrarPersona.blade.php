@extends('layouts.app')
<!doctype html>
<html lang="es">
    <head>
        <title>Registrar Persona</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

                <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />

    </head>

    @section('content')
        <section class="h-100 gradient-form" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                        <!-- Aquí va el contenido de la página centrado-->
                            <div class = "card-body p-md-5 mx-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{asset('pictures/Logo Empresas Iansa.png')}}" style="width: 300px;" alt="logo">
                                </div>

                                <h2>Registrar nueva Persona</h2>
                                <form action="/personas" method="POST" id="formPersona">
                                    @csrf
                                    <div class = "row">
                                        <div class="col-md-6">
                                            <!-- RUT -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="rut">RUT <span class="required-asterisk">*</span></label>
                                                <label class="form-label">Formato: 12345678-9</label>
                                                <input type="text" name="rut" id="rut" required class="form-control" />
                                                <span id="rutError" class="text-danger" style="display:none;">RUT inválido. Use el formato 12345678-9.</span>
                                                <span id="rutRepetido" class="text-danger" style="display:none;">El RUT ya se encuentra registrado.</span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                        <!-- Nombre de Usuario -->
                                            <div class="form-outline mb-4">
                                            <label class="form-label" for="nombreUsuario">Nombre de Usuario <span class="required-asterisk">*</span></label>
                                                <input type="text" name="nombreUsuario" id="nombreUsuario" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class="col-md-6">
                                            <!-- Nombres -->
                                            <div class="form-outline mb-4">
                                            <label class="form-label" for="nombres">Nombres <span class="required-asterisk">*</span></label>
                                                <input type="text" name="nombres" id="nombres" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Primer Apellido -->
                                            <div class="form-outline mb-4">
                                            <label class="form-label" for="primerApellido">Primer Apellido <span class="required-asterisk">*</span></label>
                                                <input type="text" name="primerApellido" id="primerApellido" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Segundo Apellido -->
                                    <div class = "row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="segundoApellido">Segundo Apellido</label>
                                                <input type="text" name="segundoApellido" id="segundoApellido" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Supervisor -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="supervisor">Supervisor</label>
                                                <input type="text" name="supervisor" id="supervisor" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                            <!-- Empresa -->
                                            <div class="form-outline mb-4">
                                            <label class="form-label" for="empresa">Empresa <span class="required-asterisk">*</span></label>
                                                <input type="text" name="empresa" id="empresa" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Centro Costo -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="centroCosto">Centro Costo <span class="required-asterisk">*</span></label>
                                                <input type="text" name="centroCosto" id="centroCosto" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                            <!-- Denominación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="denominacion">Denominación <span class="required-asterisk">*</span></label>
                                                <input type="text" name="denominacion" id="denominacion" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Título Puesto -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="tituloPuesto">Título Puesto <span class="required-asterisk">*</span></label>
                                                <input type="text" name="tituloPuesto" id="tituloPuesto" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                            <!-- Fecha Inicio -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="fechaInicio">Fecha Inicio</label>
                                                <input type="date" name="fechaInicio" id="fechaInicio" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Usuario TI -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="usuarioTI">Usuario TI</label>
                                                <select name="usuarioTI" id="usuarioTI" class="form-control" required>
                                                    <option value="1">SI</option>
                                                    <option value="0">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                             <!-- Ubicación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="ubicacion">Ubicación</label>
                                                <select name="ubicacion" id="ubicacion" class="form-control" required>
                                                    <!-- Aquí debes cargar las ubicaciones disponibles -->
                                                    @foreach($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}} - {{$ubicacion->soporteTI}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Activo -->
                                            <div class="form-outline mb-4">
                                                <div class="form-group">
                                                    <label class = "form-label">Activos</label>
                                                    <select name="activo" id="activo" class="form-control select2bs4" style="width: 100%;">
                                                        @foreach($activos as $activo)
                                                            @if ($activo->estado == 'DISPONIBLE')
                                                                <option value="{{$activo->id}}">{{$activo->nroSerie}}</option>
                                                            @endif
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
                                                        <option value="{{$persona->id}}">{{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="agregarActivo" name="agregarActivo">
                                                <label class="form-check-label" for="agregarActivo">Agregar Activo adicional</label>
                                            </div>
                                        </div>

                                        <div class="form-outline mb-4" id="activoSection" style="display: none;">
                                            <div class="form-group">
                                                <label class="form-label" for="activosAdicionales">Activos adicionales</label>
                                                <select class="select2bs4" multiple="multiple" data-placeholder="Seleccione un activo" style="width: 100%;" name="activosAdicionales[]" id="activosAdicionales">
                                                    @foreach($activos as $activo)
                                                        @if ($activo->estado == 'DISPONIBLE')
                                                            <option value="{{$activo->id}}">{{$activo->nroSerie}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Contenedor para las justificaciones dinámicas -->
                                            <div id="justificacionContainer"></div>
                                        </div>

                                    </div>
                                    <!-- Botón de Enviar -->
                                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3 text-center" type="submit" id="botonRegistrar">Registrar Persona</button>
                                </form>
                                <!-- Botón de Volver atrás -->
                                <a href="/dashboard" type="button" class="btn btn-outline-danger text-center">Volver atrás</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <script>
            document.getElementById('agregarActivo').addEventListener('change', function() {
                var activoSection = document.getElementById('activoSection');
                if (this.checked) {
                    activoSection.style.display = 'block';
                } else {
                    activoSection.style.display = 'none';
                    // Limpiar justificaciones al desmarcar
                    document.getElementById('justificacionContainer').innerHTML = '';
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Verifica que el select con id 'activosAdicionales' existe
                const selectElement = document.getElementById('activosAdicionales');

                if (selectElement) {
                    // Inicializa select2
                    $(selectElement).select2();

                    // Escucha el cambio de selección usando select2
                    $(selectElement).on('change', function() {
                        var selectedActivos = Array.from(this.selectedOptions).map(option => option.value);
                        updateJustifications(selectedActivos); // Actualiza las justificaciones cada vez que se seleccionen activos
                    });
                } else {
                    console.error('El elemento select con id "activosAdicionales" no se encuentra.');
                }
            });

            // Función que maneja la creación de campos de justificación
            function updateJustifications(selectedActivos) {
                var justificacionContainer = document.getElementById('justificacionContainer');

                // Limpiar cualquier justificación previa
                justificacionContainer.innerHTML = '';

                // Para cada activo seleccionado, generar un campo de justificación
                selectedActivos.forEach(function(activo) {
                    var div = document.createElement('div');
                    div.classList.add('form-group');

                    var label = document.createElement('label');
                    label.classList.add('form-label');
                    label.textContent = 'Justificación para ' + activo;

                    var input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'justificaciones[' + activo + ']';
                    input.classList.add('form-control');
                    input.id = 'justificaciones[' + activo + ']';

                    div.appendChild(label);
                    div.appendChild(input);
                    justificacionContainer.appendChild(div);
                });
            }

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

            // Resetea el valor del campo 'responsable' antes de enviar el formulario
            document.querySelector('form').addEventListener('submit', function() {
                var asignarResponsable = document.getElementById('asignarResponsable');
                var responsableSelect = document.getElementById('responsable');

                if (!asignarResponsable.checked) {
                    responsableSelect.value = null;  // No envía el valor cuando no está marcado
                }
            });


            document.addEventListener('DOMContentLoaded', function() {
                // Verifica que el select con id 'activosAdicionales' existe
                const selectElement = document.getElementById('activo');
                const activosAdicionalesSelect = document.getElementById('activosAdicionales');

                if (selectElement) {
                    // Inicializa select2
                    $(selectElement).select2();

                    // Escucha el cambio de selección usando select2
                    $(selectElement).on('change', function() {
                        updateActivosAdicionales();
                    });
                } else {
                    console.error('El elemento select con id "activosAdicionales" no se encuentra.');
                }
            });

            function updateActivosAdicionales() {
                var activoSeleccionado = document.getElementById('activo').value;
                var activosAdicionalesSelect = document.getElementById('activosAdicionales');

                // Guardar las selecciones actuales
                var seleccionados = Array.from(activosAdicionalesSelect.selectedOptions).map(opt => opt.value);

                // Limpiar el select de activos adicionales
                activosAdicionalesSelect.innerHTML = '';

                // Agregar las opciones filtradas (excluyendo el activo seleccionado)
                var activos = JSON.parse('{!! json_encode($activos) !!}');
                activos.forEach(function(activo) {
                    if (activo.estado === 'DISPONIBLE' && parseInt(activo.id) !== parseInt(activoSeleccionado)) {
                        var option = document.createElement('option');
                        option.value = activo.id;
                        option.textContent = activo.nroSerie;

                        // Restaurar selección si estaba previamente seleccionado
                        if (seleccionados.includes(option.value)) {
                            option.selected = true;
                        }

                        activosAdicionalesSelect.appendChild(option);
                    }
                });

                // eliminar justificacion de activos que ya no están seleccionados
                updateJustifications(Array.from(activosAdicionalesSelect.selectedOptions).map(opt => opt.value));
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateActivosAdicionales(); // Aplicar filtro al cargar la página

                // Manejar el evento de selección de activos adicionales
                $('#activosAdicionales').on('change', function() {
                    var selectedActivos = Array.from(this.selectedOptions).map(option => option.value);
                    updateJustifications(selectedActivos);
                });
            });
        </script>


            <script>
                function validarRUT(rut) {
                    // Eliminar puntos y guion
                    rut = rut.replace(/[.\-]/g, '');

                    // Validación con expresión regular
                    const rutRegex = /^[0-9]{7,8}[-|]?[0-9kK]{1}$/;
                    if (!rutRegex.test(rut)) {
                        return false;
                    }

                    // Verificar el dígito verificador
                    let rutBody = rut.slice(0, -1);
                    let dv = rut.slice(-1).toUpperCase();

                    let suma = 0;
                    let multiplicador = 2;

                    for (let i = rutBody.length - 1; i >= 0; i--) {
                        suma += parseInt(rutBody.charAt(i)) * multiplicador;
                        multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
                    }

                    let dvCalculado = 11 - (suma % 11);
                    if (dvCalculado === 11) dvCalculado = '0';
                    if (dvCalculado === 10) dvCalculado = 'K';

                    return dv === dvCalculado.toString();
                }

                async function comprobarRutRepetido(rut) {
                    // Comprobar si el RUT ya existe en la base de datos
                    try {
                        let response = await fetch('/personas/' + rut);
                        if (response.ok) {
                            let data = await response.json();
                            return data['exists'];
                        } else {
                            throw new Error('Error al comprobar el RUT');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        return false;
                    }
                }

                document.getElementById('rut').addEventListener('blur', async function() {
                    var rut = this.value;
                    var errorSpan = document.getElementById('rutError');
                    var repetidoSpan = document.getElementById('rutRepetido');

                    if (!validarRUT(rut)) {
                        errorSpan.style.display = 'block';
                        this.classList.add('is-invalid');
                        repetidoSpan.style.display = 'none';
                    } else {
                        errorSpan.style.display = 'none';
                        this.classList.remove('is-invalid');
                        if(await comprobarRutRepetido(rut)) {
                            repetidoSpan.style.display = 'block';
                            this.classList.add('is-invalid');
                        } else {
                            repetidoSpan.style.display = 'none';
                            this.classList.remove('is-invalid');
                        }
                    }
                });


                document.getElementById('formPersona').addEventListener('submit', async function(event) {
                    event.preventDefault();

                    var rut = document.getElementById('rut').value;
                    var errorSpan = document.getElementById('rutError');
                    var repetidoSpan = document.getElementById('rutRepetido');

                    if (!validarRUT(rut)) {
                        errorSpan.style.display = 'block';
                        document.getElementById('rut').classList.add('is-invalid');
                        document.getElementById('rut').focus();
                        event.preventDefault();
                    }
                    else{
                        if(await comprobarRutRepetido(rut)) {
                            repetidoSpan.style.display = 'block';
                            document.getElementById('rut').classList.add('is-invalid');
                            document.getElementById('rut').focus();
                            event.preventDefault();
                        }
                        else{
                            this.submit();
                        }
                    }

                });

            </script>

            <style>
                .is-invalid {
                    border: 1px solid red;
                    background-color: #ffe6e6;
                }
                form .form-label {
                    font-size: 15px;
                    color: #4b4b4b;
                }
            </style>

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
    @endsection


</html>
