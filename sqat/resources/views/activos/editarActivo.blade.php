<div class="modal-body">
    <h2>Editar activo</h2>
    <form action="{{ route('activos.update', $activo->id) }}" method="POST">
        @csrf

        <div class = "row">
            @if($activo->estado != 7)
                <!-- Responsable -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-outline mb-4 flex-grow-1">
                        <label class="form-label" for="responsable_de_activo">Responsable</label>
                        <div class="d-flex">
                            <select name="responsable_de_activo" id="responsable_de_activo" class="form-control select2bs4">
                                <option value="" {{ is_null($activo->responsable_de_activo) ? 'selected' : '' }}>Sin Responsable</option>
                                @foreach($personas as $persona)
                                    <option value="{{$persona->id}}"
                                        data-ubicacion="{{$persona->ubicacion ? $persona->ubicacionRelation->id : ''}}"
                                        {{ $persona->id == $activo->responsable_de_activo ? 'selected' : '' }}>
                                        {{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="btnEliminarResponsable" class="btn btn-danger ml-2">
                                <i class="fas fa-x"></i>
                            </button>
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


        </>


        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

// Eliminar responsable correctamente
document.getElementById('btnEliminarResponsable').addEventListener('click', function() {
    let responsableSelect = document.getElementById('responsable_de_activo');
    let responsableHidden = document.getElementById("responsable_de_activo_hidden");

    if (responsableSelect && responsableHidden) {
        responsableSelect.value = "";
        responsableHidden.value = "";
    }
});

document.getElementById('responsable_de_activo').addEventListener('change', function() {
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
});



</script>
