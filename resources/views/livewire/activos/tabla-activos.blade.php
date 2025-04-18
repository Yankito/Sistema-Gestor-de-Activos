<div>
    <style>
        .filter-active {
            color: #ff0000; /* Cambia el color del ícono a rojo */
            font-weight: bold; /* Hace que el ícono sea más grueso */
        }
    </style>

    <div style="overflow-x:auto;">
        <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline" data-user-is-admin="{{ $user->es_administrador }}" data-tipo-tabla="Activos">
            <thead>
                <tr>
                    <th>Acciones</th>
                    @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación","Valores Adicionales"] as $index => $columna)
                        <th>
                            {{ $columna }}
                            <!-- boton filtro -->
                            <button class="filter-btn" data-index="{{ $index + 1 }}">
                                <i class="fas fa-filter"></i>
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($activos as $activo)
                    @livewire('activos.fila-activo', ['activo' => $activo], key($activo->id))
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Contenedores de filtros fuera de la tabla -->
    @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación","Valores Adicionales"] as $index => $columna)
        <div class="filter-container" id="filter-{{ $index + 1}}">
            <div class="action-btn">
                <input type="text" class="column-search" data-index="{{ $index + 1 }}" placeholder="Buscar {{ $columna }}">
                <button class="btn btn-primary aplicar-filtro" data-index="{{ $index + 1}}">Aplicar</button>
            </div>
            <div class="checkbox-filters" data-index="{{ $index + 1}}"></div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('livewire:navigated', function() {
        Livewire.on('actualizarFila', function() {
            toastr.success('Estado cambiado correctamente.');
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
