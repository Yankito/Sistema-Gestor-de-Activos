<div class="modal-body">
    <h2>Editar activo</h2>
    <form action="{{ route('activos.update', $activo->id) }}" method="POST">
        @csrf

        <div class = "row">

            <div class="col-md-6 d-flex align-items-center">
                    <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nro_serie"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="nro_serie">Nro. Serie</label>
                    <input type="text" name="nro_serie" id="nro_serie" required class="form-control" value="{{ $activo->nro_serie }}" readonly />
                </div>
            </div>


             <!-- Marca -->
             <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="marca"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="marca">Marca</label>
                    <input type="text" name="marca" id="marca" required class="form-control" value="{{ $activo->marca }}" readonly />
                </div>
            </div>

            <!-- Modelo -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="modelo"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="modelo">Modelo</label>
                    <input type="text" name="modelo" id="modelo" required class="form-control" value="{{ $activo->modelo }}" readonly />
                </div>
            </div>


            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="tipo_de_activo"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="tipo_de_activo">Tipo de Activo</label>
                    <div class="d-flex">
                        <select name="tipo_de_activo" id="tipo_de_activo" class="form-control" required disabled>
                            <option value="LAPTOP">Laptop</option>
                            <option value="DESKTOP">Desktop</option>
                            <option value="MONITOR">Monitor</option>
                            <option value="IMPRESORA">Impresora</option>
                            <option value="CELULAR">Celular</option>
                            <option value="OTRO">Otro</option>
                        </select>
                        <input type="hidden" name="tipo_de_activo" id="tipo_de_activo_hidden" value="{{ $activo->tipo_de_activo }}">
                    </div>
                </div>
            </div>

            <!-- Precio -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="precio"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="precio">Precio</label>
                    <input type="number" name="precio" id="precio" required class="form-control" value="{{ $activo->precio }}" readonly />
                </div>
            </div>


            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="ubicacion"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="ubicacion">Ubicación</label>
                    <div class="d-flex">
                        <select name="ubicacion" id="ubicacion" class="form-control" disabled>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="ubicacion" id="ubicacion_hidden" value="{{ $activo->ubicacion }}">
                    </div>
                </div>
            </div>


            <!-- Responsable -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="responsable_de_activo"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="responsable_de_activo">Responsable</label>
                    <div class="d-flex">
                        <select name="responsable_de_activo" id="responsable_de_activo" class="form-control select2bs4" disabled>
                            <option value="" {{ is_null($activo->responsable_de_activo) ? 'selected' : '' }}>Sin Responsable</option>
                            @foreach($personas as $persona)
                                <option value="{{$persona->id}}" {{ $persona->id == $activo->responsable_de_activo ? 'selected' : '' }}>
                                    {{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="responsable_de_activo" id="responsable_de_activo_hidden" value="{{ $activo->responsable_de_activo }}">
                        <button type="button" id="btnEliminarResponsable" class="btn btn-danger ml-2">
                            <i class="fas fa-x fa-bounce"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
</script>
