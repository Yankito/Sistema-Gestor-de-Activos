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

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2>Registrar nueva Persona</h2>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="grupoCheckbox">
                                        <label class="form-check-label" for="grupoCheckbox">Grupo</label>
                                    </div>
                                </div>
                                <form action="/personas" method="POST" id="formPersona">
                                    @csrf
                                    <div class = "row" style="display: flex; flex-wrap: wrap;" id="row1">
                                        <div class="col-md-6">
                                            <!-- RUT -->
                                            <div class="form-outline mb-4" id="rutSection">
                                                <label class="form-label" for="rut">RUT </label>
                                                <small class="text-muted">Formato: 12345678-9</small>
                                                <input type="text" name="rut" id="rut" required class="form-control" />
                                                <span id="rutError" class="text-danger" style="display:none;">RUT inválido. Use el formato 12345678-9.</span>
                                                <span id="rutRepetido" class="text-danger" style="display:none;">El RUT ya se encuentra registrado.</span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                        <!-- Nombre de Usuario -->
                                            <div class="form-outline mb-4">
                                            <label class="form-label" for="user">Username </label>
                                                <input type="text" name="user" id="user" required class="form-control" />
                                                <span id="userRepetido" class="text-danger" style="display:none;">El user ya se encuentra registrado.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row" style="display: flex; flex-wrap: wrap;" id="row2">
                                        <div class="col-md-6">
                                            <!-- Nombres -->
                                            <div class="form-outline mb-4">
                                            <label class="form-label" for="nombres">Nombres </label>
                                                <input type="text" name="nombres" id="nombres" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Primer Apellido -->
                                            <div class="form-outline mb-4"  id= "primerApellidoSection">
                                            <label class="form-label" for="primer_apellido">Primer Apellido </label>
                                                <input type="text" name="primer_apellido" id="primer_apellido" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Segundo Apellido -->
                                    <div class = "row"style="display: flex; flex-wrap: wrap;" id="row3">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4" id="segundoApellidoSection">
                                                <label class="form-label" for="segundo_apellido" >Segundo Apellido</label>
                                                <input type="text" name="segundo_apellido" id="segundo_apellido" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Empresa -->
                                            <div class="form-outline mb-4" id="empresaSection">
                                            <label class="form-label" for="nombre_empresa" >Empresa </label>
                                                <input type="text" name="nombre_empresa" id="nombre_empresa" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row" style="display: flex; flex-wrap: wrap;" id="row4">
                                        <div class = "col-md-6">
                                            <!-- Cargo -->
                                            <div class="form-outline mb-4" id="cargoSection">
                                                <label class="form-label" for="cargo" >Cargo</label>
                                                <input type="text" name="cargo" id="cargo" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Fecha Inicio -->
                                            <div class="form-outline mb-4" id="fecha_ingSection">
                                                <label class="form-label" for="fecha_ing">Fecha Inicio</label>
                                                <input type="date" name="fecha_ing" id="fecha_ing" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row" style="display: flex; flex-wrap: wrap;" id="row5">
                                        <div class = "col-md-6">
                                            <!-- Correo -->
                                            <div class="form-outline mb-4" id="correoSection">
                                                <label class="form-label" for="correo" >Correo</label>
                                                <input type="email" name="correo" id="correo" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row" style="display: flex; flex-wrap: wrap;" id="row6">
                                        <div class = "col-md-6">
                                             <!-- Ubicación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="ubicacion">Ubicación</label>
                                                <select name="ubicacion" id="ubicacion" class="form-control" required>
                                                    <!-- Aquí debes cargar las ubicaciones disponibles -->
                                                    @foreach($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}} - {{$ubicacion->soporte_ti}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Activo -->
                                            <div class="form-outline mb-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="activo">Activos</label>
                                                    <select name="activo" id="activo" class="form-control select2bs4" style="width: 100%;">
                                                        <option value="" selected>SIN ACTIVO</option>
                                                        @foreach($activos as $activo)
                                                            @if ($activo->estado == 3)
                                                                <option value="{{$activo->id}}">{{$activo->nro_serie}}</option>
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
                                                        <option value="{{$persona->id}}">{{$persona->rut}}: {{$persona->nombre_completo}}</option>
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
                                                        @if ($activo->estado == 3)
                                                            <option value="{{$activo}}">{{$activo->id}} - {{ $activo->nro_serie }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Contenedor para las justificaciones dinámicas -->
                                            <div id="justificacionContainer"></div>
                                        </div>

                                    </div>
                                    <!-- Botón de Enviar -->
                                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3 text-center" type="submit" id="botonRegistrar" onclick="this.disabled=true; this.form.submit();">Registrar Persona</button>
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
                        console.log(selectedActivos);
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
                    var activo = JSON.parse(activo);
                    console.log("activo: ", activo);
                    console.log("activo id: ", activo.id);
                    console.log("activo nro_serie: ", activo.nro_serie);
                    label.textContent = 'Justificación para ' + activo.nro_serie;

                    var input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'justificaciones[' + activo.id + ']';
                    input.classList.add('form-control');
                    input.id = 'justificaciones[' + activo.id + ']';

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
                    if (activo.estado === 3 && parseInt(activo.id) !== parseInt(activoSeleccionado)) {
                        var option = document.createElement('option');
                        option.value = JSON.stringify({ id: activo.id, nro_serie: activo.nro_serie });
                        option.textContent = activo.nro_serie;
                        console.log(option.value);
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

            function validarRUT(rut) {
                // Eliminar puntos y guion

                // Validación con expresión regular
                const rutRegex = /^[0-9]{1,2}[0-9]{6}-[0-9kK]$/;
                if (!rutRegex.test(rut)) {
                    return false;
                }

                rut = rut.replace(/[.\-]/g, '');

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
                // Comprobar si el RUT ya existe en la base de datos, excepto el RUT 11111111-1
                if (rut === '11111111-1') {
                    return false; // No se considera repetido
                }
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

                if (!validarRUT(rut) && rut != '11111111-1') {
                    errorSpan.style.display = 'block';
                    this.classList.add('is-invalid');
                    repetidoSpan.style.display = 'none';
                } else {
                    errorSpan.style.display = 'none';
                    this.classList.remove('is-invalid');
                    if (rut !== '11111111-1' && await comprobarRutRepetido(rut)) {
                        repetidoSpan.style.display = 'block';
                        this.classList.add('is-invalid');
                    } else {
                        repetidoSpan.style.display = 'none';
                        this.classList.remove('is-invalid');
                    }
                }
            });

            async function comprobarUserRepetido(user) {
                // Comprobar si el user ya existe en la base de datos
                try {
                    let response = await fetch('/personas/user/' + user);
                    if (response.ok) {
                        let data = await response.json();
                        return data['exists'];
                    } else {
                        throw new Error('Error al comprobar el user');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    return false;
                }
            }

            document.getElementById('user').addEventListener('blur', async function() {
                var user = this.value;
                var repetidoSpan = document.getElementById('userRepetido');
                if(await comprobarUserRepetido(user)) {
                    repetidoSpan.style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    repetidoSpan.style.display = 'none';
                    this.classList.remove('is-invalid');
                }
            });


            document.getElementById('formPersona').addEventListener('submit', async function(event) {
                event.preventDefault();

                var rut = document.getElementById('rut').value;
                var errorSpan = document.getElementById('rutError');
                var repetidoSpan = document.getElementById('rutRepetido');
                var user = document.getElementById('user').value;
                var repetidoUserSpan = document.getElementById('userRepetido');

                if (!validarRUT(rut) && rut!='11111111-1') {
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
                        if(await comprobarUserRepetido(user)) {
                            repetidoUserSpan.style.display = 'block';
                            document.getElementById('user').classList.add('is-invalid');
                            document.getElementById('user').focus();
                            event.preventDefault();
                        }
                        else{
                            this.submit();
                        }
                    }
                }

            });
            document.getElementById('grupoCheckbox').addEventListener('change', function() {
                var rutSection = document.getElementById('rutSection');
                var primerApellidoSection = document.getElementById('primerApellidoSection');
                var segundoApellidoSection = document.getElementById('segundoApellidoSection');
                var empresaSection = document.getElementById('empresaSection');
                var cargoSection = document.getElementById('cargoSection');
                var correoSection = document.getElementById('correoSection');
                var fechaIngSection = document.getElementById('fecha_ingSection');

                if (this.checked) {
                    // Ocultar secciones y eliminar el atributo required
                    rutSection.style.display = 'none';
                    rutSection.querySelector('input').removeAttribute('required');

                    primerApellidoSection.style.display = 'none';
                    primerApellidoSection.querySelector('input').removeAttribute('required');

                    segundoApellidoSection.style.display = 'none';
                    segundoApellidoSection.querySelector('input').removeAttribute('required');

                    empresaSection.style.display = 'none';
                    empresaSection.querySelector('input').removeAttribute('required');

                    cargoSection.style.display = 'none';
                    cargoSection.querySelector('input').removeAttribute('required');

                    correoSection.style.display = 'none';
                    correoSection.querySelector('input').removeAttribute('required');

                    fechaIngSection.style.display = 'none';
                    fechaIngSection.querySelector('input').removeAttribute('required');

                    // Asignar valores por defecto
                    document.getElementById('rut').value = '11111111-1';
                    document.getElementById('primer_apellido').value = '';
                    document.getElementById('segundo_apellido').value = '';
                    document.getElementById('nombre_empresa').value = 'EMPRESAS IANSA S.A.';
                    document.getElementById('cargo').value = 'default';
                    document.getElementById('correo').value = 'default@iansa.cl';

                    // Asignar la fecha actual
                    var fechaActual = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
                    document.getElementById('fecha_ing').value = fechaActual;

                    // Reorganizar los campos visibles
                    var row1 = document.getElementById('row1');
                    var row2 = document.getElementById('row2');

                    if (row1) {
                        row1.querySelectorAll('.col-md-6').forEach(function(col) {
                            col.classList.remove('col-md-6');
                            col.classList.add('col-md-12');
                        });
                    }
                    if (row2) {
                        row2.querySelectorAll('.col-md-6').forEach(function(col) {
                            col.classList.remove('col-md-6');
                            col.classList.add('col-md-12');
                        });
                    }
                } else {
                    // Mostrar secciones y restaurar el atributo required
                    rutSection.style.display = 'block';
                    rutSection.querySelector('input').setAttribute('required', true);

                    primerApellidoSection.style.display = 'block';
                    primerApellidoSection.querySelector('input').setAttribute('required', true);

                    segundoApellidoSection.style.display = 'block';
                    segundoApellidoSection.querySelector('input').setAttribute('required', true);

                    empresaSection.style.display = 'block';
                    empresaSection.querySelector('input').setAttribute('required', true);

                    cargoSection.style.display = 'block';
                    cargoSection.querySelector('input').setAttribute('required', true);

                    correoSection.style.display = 'block';
                    correoSection.querySelector('input').setAttribute('required', true);

                    fechaIngSection.style.display = 'block';
                    fechaIngSection.querySelector('input').setAttribute('required', true);

                    // Limpiar valores por defecto (opcional)
                    document.getElementById('rut').value = '';
                    document.getElementById('primer_apellido').value = '';
                    document.getElementById('segundo_apellido').value = '';
                    document.getElementById('nombre_empresa').value = '';
                    document.getElementById('cargo').value = '';
                    document.getElementById('correo').value = '';
                    document.getElementById('fecha_ing').value = '';

                    // Restaurar el diseño original
                    var row1 = document.getElementById('row1');
                    var row2 = document.getElementById('row2');

                    if (row1) {
                        row1.querySelectorAll('.col-md-12').forEach(function(col) {
                            col.classList.remove('col-md-12');
                            col.classList.add('col-md-6');
                        });
                    }
                    if (row2) {
                        row2.querySelectorAll('.col-md-12').forEach(function(col) {
                            col.classList.remove('col-md-12');
                            col.classList.add('col-md-6');
                        });
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
