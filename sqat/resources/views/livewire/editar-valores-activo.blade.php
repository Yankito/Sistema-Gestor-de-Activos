<div>
<div class="modal-body">
@if (isset($activo))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <h2>Editar activo</h2>
    <form wire:submit.prevent="updateValoresActivo" id="formulario-editar-valores">
        @csrf

        <div class = "row">

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nro_serie" onclick="toggleEditField.call(this, 'data-target')"></i>
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
                        <select wire:model="ubicacion" id="ubicacion_select" class="form-control" disabled>
                            <option value="" {{ is_null($activo->ubicacion) ? 'selected' : '' }}>Sin ubicacion</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{$ubicacion->id}}" >
                                    {{$ubicacion->sitio}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Responsable -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="responsable_de_activo">Responsable</label>
                    <div class="d-flex">
                    <select wire:model="responsable_de_activo" wire:change="actualizarUbicacion($event.target.value)" id="responsable_de_activo_select" class="form-control select2bs4" {{ $activo->estado == 4 ? 'disabled' : '' }} disabled>
                            <option value="" {{ is_null($activo->responsable_de_activo) ? 'selected' : '' }}>Sin Responsable</option>
                            @foreach($personas as $persona)
                                <option value="{{$persona->id}}">
                                    {{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" >Guardar Cambios</button>
        </div>
    </form>
@endif
</div>


<script>


    document.addEventListener('DOMContentLoaded', function () {
        $('#modal-editar-valores-activos').on('hidden.bs.modal', function () {
            Livewire.dispatch('cerrarModalValores'); // Emite el evento a Livewire
        });
    });

    document.addEventListener('livewire:navigated', function() {
        function toggleEditField ($dataTarget) {
            console.log('toggleEditField');
            let inputId = this.getAttribute($dataTarget);
            let inputField = document.getElementById(inputId);

            if (!inputField) return;

            if (inputField.tagName === "SELECT") {
                inputField.disabled = !inputField.disabled;
            } else {
                inputField.readOnly = !inputField.readOnly;
            }

            // Cambiar icono de lápiz a check
            this.classList.toggle('fa-pencil-alt');
            this.classList.toggle('fa-check');
            this.classList.toggle('text-primary');
            this.classList.toggle('text-success');
        }

        // Para asegurarse de que funcione después de una actualización de Livewire
        Livewire.on('modal-valores-cargado', () => {
            toggleEditField();
        });
    });

    document.addEventListener('livewire:navigated', function() {

        Livewire.on('cerrar-modal-valores', () => {
            $('#formulario-editar-valores').closest('.modal').modal('hide');
            console.log('cerrar modal');
            toastr.success('Los cambios se han guardado correctamente.');
        });

        Livewire.on('modal-valores-cargado', () => {
            console.log('modal valores cargado');
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            $('#modal-editar-valores-activos').modal('show');

            Livewire.dispatch('editar-campo');
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
</div>
