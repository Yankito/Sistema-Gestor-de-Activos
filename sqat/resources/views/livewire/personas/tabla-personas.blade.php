<div style = "overflow-x:auto">
    <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline">
    <thead>
    <tr>
        <th>Acciones</th>
        @foreach(["Rut", "Nombre de usuario", "Nombres", "Primer Apellido", "Segundo Apellido", "Supervisor", "Empresa", "Estado empleado", "Centro Costo", "Denominacion", "Titulo Puesto", "Fecha Inicio", "Usuario TI", "Ubicacion"] as $index => $columna)
            <th>
            {{ $columna }}
            <!-- boton filtro -->
            <button class="filter-btn" data-index="{{ $index + 1}}">
                <i class="fas fa-filter"></i>
            </button>
            <div class="filter-container" id="filter-{{ $index + 1}}">
                <input type="text" class="column-search" data-index="{{ $index + 1}}" placeholder="Buscar...">
                <div class="checkbox-filters" data-index="{{ $index + 1}}"></div>
            </div>
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

<script>
    document.addEventListener('livewire:navigated', function() {
        Livewire.on('actualizarFila', function() {
            toastr.success('Estado cambiado correctamente.');
        });
    });
</script>
