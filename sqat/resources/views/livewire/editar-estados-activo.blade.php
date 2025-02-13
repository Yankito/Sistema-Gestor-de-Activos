<div class="modal-body">
@if (isset($activo))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <h2>Editar activo</h2>
    <form wire:submit.prevent="updateActivo" id="formulario-editar">
        @csrf

        <div class = "row">
            @if($activo->estado != 7)
                <!-- Responsable -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-outline mb-4 flex-grow-1">
                        <label class="form-label" for="responsable_de_activo">Responsable</label>
                        <div class="d-flex">
                        <select wire:model="responsable_de_activo" wire:change="actualizarUbicacion($event.target.value)" id="responsable_de_activo_select" class="form-control select2bs4" {{ $activo->estado == 4 ? 'disabled' : '' }}>
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

                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-outline mb-4 flex-grow-1">
                        <label class="form-label" for="ubicacion">Ubicación</label>
                        <div class="d-flex">
                            <select wire:model="ubicacion" id="ubicacion_select" class="form-control">
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
            @endif


            @if ($activo->estado == 4)
                <div class="action-btns">
                    <button type="button" data-dismiss="modal" class="btn btn-warning btn-sm" wire:click="cambiarEstado({{ $activo->id }}, 7)">
                        <i class="fas fa-exchange-alt"></i> <!-- Pasar a DEVUELTO -->
                        Devolución
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm" wire:click="cambiarEstado({{ $activo->id }}, 5)">
                        <i class="fas fa-question-circle"></i> <!-- Pasar a PERDIDO -->
                        Extraviado
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-dark btn-sm" wire:click="cambiarEstado({{ $activo->id }}, 6)">
                        <i class="fas fa-user-secret"></i> <!-- Pasar a ROBADO -->
                        Robado
                    </button>
                </div>
            @elseif ($activo->estado == 7)
                <div class="action-btn">
                <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 8)">
                    <i class="fas fa-arrow-down"></i> <!-- Pasar a PARA BAJA -->
                    Dar de baja
                </button>
                <button type="button" data-dismiss="modal" class="btn btn-info btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 9)">
                    <i class="fas fa-hand-holding-heart"></i> <!-- Pasar a DONADO -->
                    Donar
                </button>
                <button type="button" data-dismiss="modal" class="btn btn-success btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 10)">
                    <i class="fas fa-dollar-sign"></i> <!-- Pasar a VENDIDO -->
                    Vender
                </button>
                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 2)">
                    <i class="fas fa-undo"></i> <!-- Volver a PREPARACIÓN -->
                    Volver a preparación
                </button>
                </div>

            @endif
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
        $('#modal-editar-activos-estados').on('hidden.bs.modal', function () {
            Livewire.dispatch('cerrarModal'); // Emite el evento a Livewire
        });
    });

    document.addEventListener('livewire:navigated', function() {
        Livewire.on('cerrar-modal', () => {
            $('#formulario-editar').closest('.modal').modal('hide');
            document.getElementById('formulario-editar').reset();
            toastr.success('Los cambios se han guardado correctamente.');
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



</script>
