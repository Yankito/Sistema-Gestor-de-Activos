<div class="modal-body">
    <h2>Editar activo</h2>
    <form id="formulario-editar" action="{{ route('activos.update', $activo->id) }}" method="POST">
        @csrf

        <div class = "row">
            @if($activo->estado != 7)
                <!-- Responsable -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-outline mb-4 flex-grow-1">
                        <label class="form-label" for="responsable_de_activo">Responsable</label>
                        <div class="d-flex">
                            <select name="responsable_de_activo" id="responsable_de_activo" class="form-control select2bs4" {{ $activo->estado == 4 ? 'disabled' : '' }}>
                                <option value="" {{ is_null($activo->responsable_de_activo) ? 'selected' : '' }}>Sin Responsable</option>
                                @foreach($personas as $persona)
                                    <option value="{{$persona->id}}"
                                        data-ubicacion="{{$persona->ubicacion ? $persona->ubicacionRelation->id : ''}}"
                                        {{ $persona->id == $activo->responsable_de_activo ? 'selected' : '' }}>
                                        {{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-outline mb-4 flex-grow-1">
                        <label class="form-label" for="ubicacion">Ubicación</label>
                        <div class="d-flex">
                            <select name="ubicacion" id="ubicacion" class="form-control">
                                <option value="" {{ is_null($activo->ubicacion) ? 'selected' : '' }}>Sin ubicacion</option>
                                @foreach($ubicaciones as $ubicacion)
                                    <option value="{{$ubicacion->id}}" {{$ubicacion->id == $activo->ubicacion ? 'selected' : ''}}>
                                        {{$ubicacion->sitio}}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="ubicacion" id="ubicacion_hidden" value="{{ $activo->ubicacion }}">
                        </div>
                    </div>
                </div>
            @endif


            @if ($activo->estado == 4)
                <div class="action-btns">
                <button type="button" class="btn btn-warning btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 7)">
                        <i class="fas fa-exchange-alt"></i> <!-- Pasar a DEVUELTO -->
                        Devolución
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 5)">
                        <i class="fas fa-question-circle"></i> <!-- Pasar a PERDIDO -->
                        Extraviado
                    </button>
                    <button type="button" class="btn btn-dark btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 6)">
                        <i class="fas fa-user-secret"></i> <!-- Pasar a ROBADO -->
                        Robado
                    </button>
                </div>
            @elseif ($activo->estado == 7)
                <div class="action-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 8)">
                    <i class="fas fa-arrow-down"></i> <!-- Pasar a PARA BAJA -->
                    Dar de baja
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 9)">
                    <i class="fas fa-hand-holding-heart"></i> <!-- Pasar a DONADO -->
                    Donar
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 10)">
                    <i class="fas fa-dollar-sign"></i> <!-- Pasar a VENDIDO -->
                    Vender
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cambiarEstado('{{ $activo->id }}', 2)">
                    <i class="fas fa-undo"></i> <!-- Volver a PREPARACIÓN -->
                    Volver a preparación
                </button>
                </div>

            @endif


        </div>


        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    document.getElementById('formulario-editar').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    const formData = new FormData(this); // Recoge los datos del formulario

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Para indicar que la solicitud es AJAX
        }
    })
    .then(response => response.json()) // Maneja la respuesta JSON
    .then(response => {
        if (response.success) {
            // Actualiza la fila correspondiente
            const fila = $('tr[data-id="' + JSON.parse('{!! json_encode($activo->id) !!}') + '"]');
            //mostrar si la fila fue cargada correctamente
            if (fila.length) {
                console.log("Fila cargada correctamente");
            } else {
                console.log("Error al cargar la fila");
            }
            console.log(JSON.parse('{!! json_encode($activo->id) !!}') );
            console.log(response);
            fila.find('td').each(function(index) {
                console.log(index);
                switch(index) {
                    case 0:
                        if (response.activoModificado.estado === 1) {
                            $(this).html('<button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado(\'' + response.activoModificado.id + '\', 2)"><i class="fas fa-arrow-right"></i></button>');
                        } else if (response.activoModificado.estado === 2) {
                            $(this).html('<button type="button" class="btn btn-primary btn-sm" onclick="cambiarEstado(\'' + response.activoModificado.id + '\', 3)"><i class="fas fa-arrow-right"></i></button>');
                        } else if (response.activoModificado.estado === 3 || response.activoModificado.estado === 4) {
                            $(this).html('<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo(\'' + response.activoModificado.id + '\')"><i class="fas fa-edit"></i></button>');
                        } else if (response.activoModificado.estado === 5 || response.activoModificado.estado === 6) {
                            $(this).html('<button type="button" class="btn btn-success btn-sm" onclick="cambiarEstado(\'' + response.activoModificado.id + '\', 7)"><i class="fas fa-undo"></i></button>');
                        } else if (response.activoModificado.estado === 7) {
                            $(this).html('<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo(\'' + response.activoModificado.id + '\')"><i class="fas fa-edit"></i></button>');
                        } else if (response.activoModificado.estado === 8 || response.activoModificado.estado === 9 || response.activoModificado.estado === 10) {
                            $(this).html('<button type="button" class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle"></i></button>');
                        }
                        break;
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
    })
    .catch(error => {
        Swal.fire("Error", "Error de conexión con el servidor", "error");
    });
});

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


<script>
document.querySelectorAll('.toggle-edit').forEach(icon => {
    icon.addEventListener('click', function() {
        let inputId = this.getAttribute('data-target');
        let inputField = document.getElementById(inputId);
        if (inputField.tagName === "SELECT") {
            inputField.disabled = !inputField.disabled;

            // Buscar el input hidden relacionado y actualizar su valor
            let hiddenInput = document.getElementById(inputId + "_hidden");
            if (hiddenInput) {
                hiddenInput.value = inputField.value;
            }

            // Escuchar cambios en el select para actualizar el input hidden
            inputField.addEventListener("change", function() {
                if (hiddenInput) {
                    hiddenInput.value = inputField.value;
                }
            });
        } else {
            inputField.readOnly = !inputField.readOnly;
        }

        this.classList.toggle('fa-pencil-alt');
        this.classList.toggle('fa-check');
        this.classList.toggle('text-primary');
        this.classList.toggle('text-success');
    });
});

document.getElementById('responsable_de_activo').addEventListener('change', function() {
    console.log(this.options[this.selectedIndex]);
    let selectedPersona = this.options[this.selectedIndex];
    let ubicacionId = selectedPersona.getAttribute('data-ubicacion');

    // Actualizar el select de ubicación con la nueva ubicación
    let ubicacionSelect = document.getElementById('ubicacion');
    ubicacionSelect.value = ubicacionId;

    // Actualizar el input hidden
    let ubicacionHidden = document.getElementById("ubicacion_hidden");
    if (ubicacionHidden) {
        ubicacionHidden.value = ubicacionId;
    }
    console.log(ubicacionHidden.value);
});

document.getElementById('ubicacion').addEventListener('change', function() {
    let ubicacionHidden = document.getElementById("ubicacion_hidden");

    ubicacionHidden.value = this.value;

    console.log(ubicacionHidden.value);
});



</script>
