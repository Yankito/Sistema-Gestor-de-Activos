<div>
    <style>
        .filter-active {
            color: #ff0000; /* Cambia el color del ícono a rojo */
            font-weight: bold; /* Hace que el ícono sea más grueso */
        }
    </style>
    <div style = "overflow-x:auto">
        <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline" data-user-is-admin="{{ $user->es_administrador }}" data-tipo-tabla="Personas">
            <thead>
                <tr>
                    <th>Acciones</th>
                    @foreach(["User","Rut", "Nombre Completo", "Nombre Empresa", "Estado", "Fecha Ingreso Compañia", "Fecha Término Compañia", "Cargo", "Ubicación", "Correo"] as $index => $columna)
                        <th>
                            {{ $columna }}
                            <!-- boton filtro -->
                            <button class="filter-btn" data-index="{{ $index + 1}}">
                                <i class="fas fa-filter"></i>
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($personas as $persona)
                    @livewire('personas.fila-persona', ['persona' => $persona], key($persona->id))
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Contenedores de filtros fuera de la tabla -->
    @foreach(["User","Rut", "Nombre Completo", "Nombre Empresa", "Estado", "Fecha Ingreso Compañia", "Fecha Término Compañia", "Cargo", "Ubicación", "Correo"] as $index => $columna)
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
</script>
