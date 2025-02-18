<div>
<div class="modal-body">
@if (isset($persona))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nombre_usuario"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="nombre_usuario">Nombre de Usuario</label>
                <input wire:model="nombre_usuario" type="text" id="nombre_usuario" required class="form-control" value="{{ $persona->nombre_usuario }}" readonly />
            </div>
        </div>

        <!-- Nombres -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nombres"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="nombres">Nombres</label>
                <input wire:model="nombres" type="text" id="nombres" required class="form-control" value="{{ $persona->nombres }}" readonly />
            </div>
        </div>

        <!-- Primer Apellido -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="primer_apellido"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="primer_apellido">Primer Apellido</label>
                <input wire:model="primer_apellido" type="text" id="primer_apellido" required class="form-control" value="{{ $persona->primer_apellido }}" readonly />
            </div>
        </div>

        <!-- Segundo Apellido -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="segundo_apellido"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="segundo_apellido">Segundo Apellido</label>
                <input wire:model="segundo_apellido" type="text" id="segundo_apellido" class="form-control" value="{{ $persona->segundo_apellido }}" readonly />
            </div>
        </div>

        <!-- Supervisor -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="supervisor"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="supervisor">Supervisor</label>
                <input wire:model="supervisor" type="text" id="supervisor" class="form-control" value="{{ $persona->supervisor }}" readonly />
            </div>
        </div>

        <!-- Empresa -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="empresa"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="empresa">Empresa</label>
                <input wire:model="empresa" type="text" id="empresa" required class="form-control" value="{{ $persona->empresa }}" readonly />
            </div>
        </div>

        <!-- Centro Costo -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="centro_costo"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="centro_costo">Centro Costo</label>
                <input wire:model="centro_costo" type="text" id="centro_costo" required class="form-control" value="{{ $persona->centro_costo }}" readonly />
            </div>
        </div>

        <!-- Denominación -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="denominacion"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="denominacion">Denominación</label>
                <input wire:model="denominacion" type="text" id="denominacion" required class="form-control" value="{{ $persona->denominacion }}" readonly />
            </div>
        </div>

        <!-- Título Puesto -->
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="titulo_puesto"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="titulo_puesto">Título Puesto</label>
                <input wire:model="titulo_puesto" type="text" id="titulo_puesto" required class="form-control" value="{{ $persona->titulo_puesto }}" readonly />
            </div>
        </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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

