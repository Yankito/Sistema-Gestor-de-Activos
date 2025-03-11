<div>
<div class="modal-body">
@if (isset($persona))
    <h2>Editar persona</h2>
    <form wire:submit.prevent="updateValoresPersona" id="formulario-editar-valores">
        @csrf
        <div class = "row">

        <!-- RUT -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="rut"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="rut">RUT</label>
                <input wire:model="rut" type="text" id="rut" required class="form-control" value="{{ $persona->rut }}" readonly />
            </div>
        </div>
        <!-- Nombre de Usuario -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="user"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="user">Nombre de Usuario</label>
                <input wire:model="user" type="text" id="user" required class="form-control" value="{{ $persona->user }}" readonly />
            </div>
        </div>

        <!-- Nombre Completo -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nombres"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="nombre_completo">Nombre Completo</label>
                <input wire:model="nombre_completo" type="text" id="nombre_completo" required class="form-control" value="{{ $persona->nombre_completo }}" readonly />
            </div>
        </div>

        <!-- Empresa -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nombre_empresa"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="nombre_empresa">Empresa</label>
                <input wire:model="nombre_empresa" type="text" id="nombre_empresa" required class="form-control" value="{{ $persona->nombre_empresa }}" readonly />
            </div>
        </div>

        <!-- Cargo -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="cargo"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="cargo">Centro Costo</label>
                <input wire:model="cargo" type="text" id="cargo" required class="form-control" value="{{ $persona->cargo }}" readonly />
            </div>
        </div>

        <!-- Correo -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="correo"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="correo">Correo</label>
                <input wire:model="correo" type="text" id="correo" required class="form-control" value="{{ $persona->correo }}" readonly />
            </div>
        </div>

        <!-- Fecha Inicio -->
        <div class = "col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="fecha_ing"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="fecha_ing">Fecha Inicio</label>
                <input wire:model="fecha_ing" type="date" name="fecha_ing" id="fecha_ing" class="form-control" readonly/>
            </div>
        </div>

        <!-- Fecha Termino -->
        <div class = "col-md-6 d-flex align-items-center">
        <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="fecha_ter"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="fecha_ter">Fecha Termino</label>
                <input wire:model="fecha_ter" type="date" name="fecha_ter" id="fecha_ter" class="form-control" readonly/>
            </div>
        </div>


        <!--Ubicación-->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="ubicacion"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="ubicacion">Ubicación</label>
                <div class="d-flex">
                    <select wire:model="ubicacion" id="ubicacion" class="form-control" disabled>
                        <option value="" {{ is_null($persona->ubicacion) ? 'selected' : '' }}>Sin ubicacion</option>
                        @foreach($ubicaciones as $ubicacion)
                            <option value="{{$ubicacion->id}}" >
                                {{$ubicacion->sitio}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        </div>

        <!-- boton desactivar persona-->
        <div class="form-group">
            <label for="estado_empleado">Estado</label>
            <select wire:model="estado_empleado" class="form-control" id="estado_empleado" >
                <option value="1" {{ $persona->estado_empleado == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ $persona->estado_empleado == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
@endif
</div>


<script>


    document.addEventListener('livewire:navigated', function() {

        $('#modal-editar-valores-persona').on('hidden.bs.modal', function () {
            Livewire.dispatch('cerrarModalValores'); // Emite el evento a Livewire
        });

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
            $('#modal-editar-valores-persona').modal('show');

            $(function () {
                $('.toggle-edit').on('click',function toggleEditField (event) {
                    console.log('toggle edit field');
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

