<div>
<div class="modal-body">
@if (isset($activo))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <h2>Editar activo</h2>
    <form wire:submit.prevent="updateValoresActivo" id="formulario-editar-valores">
        @csrf

        <div class = "row">

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nro_serie" ></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="nro_serie">Nro. Serie</label>
                    <input wire:model="nro_serie" type="text" id="nro_serie" required class="form-control" value="{{ $activo->nro_serie }}" readonly />
                </div>
            </div>

            <!-- Marca -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="marca"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="marca">Marca</label>
                    <input wire:model="marca" type="text" id="marca" required class="form-control" value="{{ $activo->marca }}" readonly />
                </div>
            </div>

            <!-- Modelo -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="modelo"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="modelo">Modelo</label>
                    <input wire:model="modelo" type="text" id="modelo" required class="form-control" value="{{ $activo->modelo }}" readonly />
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="tipo_de_activo"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="tipo_de_activo">Tipo de Activo</label>
                    <div class="d-flex">
                        <select wire:model="tipo_de_activo" id="tipo_de_activo" class="form-control" required disabled>
                            <option value="LAPTOP">LAPTOP</option>
                            <option value="DESKTOP">DESKTOP</option>
                            <option value="MONITOR">MONITOR</option>
                            <option value="IMPRESORA">IMPRESORA</option>
                            <option value="CELULAR">CELULAR</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Precio -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="precio"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="precio">Precio</label>
                    <input wire:model="precio" type="number" id="precio" required class="form-control" value="{{ $activo->precio }}" readonly />
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="ubicacion"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="ubicacion">Ubicación</label>
                    <div class="d-flex">
                        <select wire:model="ubicacion" id="ubicacion" class="form-control" disabled>
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
                                    {{$persona->rut}}: {{$persona->nombre_completo}}
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

        Livewire.on('cerrar-modal-valores', (data) => {
            $('#formulario-editar-valores').closest('.modal').modal('hide');
            console.log('cerrar modal');
            console.log(data);
            if(data[0]['success']===true)
                toastr.success(data[0]['mensaje']);
            else
                toastr.error(data[0]['mensaje']);
        });

        Livewire.on('modal-valores-cargado', () => {
            console.log('modal valores cargado');
            $(function () {
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
            });
            $('#modal-editar-valores-activos').modal('show');

            $(function () {
                $('.toggle-edit').on('click',function toggleEditField (event) {
                    console.log('toggleEditField');
                    let dataTarget = event.target.getAttribute('data-target')
                    let inputField = document.getElementById(dataTarget);

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
                });
            });

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
