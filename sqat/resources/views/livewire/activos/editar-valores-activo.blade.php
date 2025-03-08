<div>
<div class="modal-body">
@if (isset($activo))
    <h2>Editar activo</h2>
    <form wire:submit.prevent="updateValoresActivo" id="formulario-editar-valores">
        @csrf

        <div class = "row">

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="nro_serie" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="nro_serie">Nro. Serie</label>
                    <input wire:model="nro_serie" type="text" id="nro_serie" required class="form-control" value="{{ $activo->nro_serie }}" readonly />
                </div>
            </div>

            <!-- Marca -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="marca" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="marca">Marca</label>
                    <input wire:model="marca" type="text" id="marca" required class="form-control" value="{{ $activo->marca }}" readonly />
                </div>
            </div>

            <!-- Modelo -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="modelo" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="modelo">Modelo</label>
                    <input wire:model="modelo" type="text" id="modelo" required class="form-control" value="{{ $activo->modelo }}" readonly />
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="tipo_de_activo" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="tipo_de_activo">Tipo de Activo</label>
                    <div class="d-flex">
                        <select wire:model="tipo_de_activo" id="tipo_de_activo" class="form-control" required disabled>
                            @foreach ( $tiposDeActivo as $tipo )
                                <option value="{{ $tipo->id }}" {{ $tipo->id == $activo->tipo_de_activo ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Precio -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="precio" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="precio">Precio</label>
                    <input wire:model="precio" type="number" id="precio" required class="form-control" value="{{ $activo->precio }}" readonly />
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="ubicacion" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="ubicacion">Ubicación</label>
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

            <!-- Responsable -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="responsable_de_activo" style="cursor: pointer;"></i>
                <div class="form-outline mb-4 flex-grow-1">
                    <label class="form-label" for="responsable_de_activo">Responsable</label>
                    <select wire:model="responsable_de_activo" wire:change="actualizarUbicacion($event.target.value)" id="responsable_de_activo" class="form-control select2bs4" {{ $activo->estado == 4 ? 'disabled' : '' }} disabled>
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

        <div class = "row">

            @if ($activo->tipoDeActivo->caracteristicasAdicionales->count() > 0)
                <label class="form-label">Características Adicionales (opcionales)</label>
                @foreach ($valoresAdicionales as $index => $valorAdicional)
                    <div class="col-md-6 d-flex align-items-center">
                        <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="caracteristica_{{ $index }}"></i>
                        <div class="form-outline mb-4 flex-grow-1">
                            <label class="form-label" for="caracteristica_{{ $index }}">{{ $valorAdicional['nombre_caracteristica'] }}</label>

                            <input
                                wire:model="valoresAdicionales.{{ $index}}.valor"
                                type="text"
                                id="caracteristica_{{ $index }}"
                                class="form-control"
                                value="{{ $valorAdicional ? $valorAdicional['valor'] : '' }}"
                                readonly
                            />
                        </div>
                    </div>
                @endforeach
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

    document.addEventListener('livewire:navigated', function() {

        $('#modal-editar-valores-activos').on('hidden.bs.modal', function () {
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
            $(function () {
                $('#responsable_de_activo').on('change', function () {
                    console.log('cambio: ' + $(this).val());
                    Livewire.dispatch('setResponsable', [$(this).val() ]);
                });
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
