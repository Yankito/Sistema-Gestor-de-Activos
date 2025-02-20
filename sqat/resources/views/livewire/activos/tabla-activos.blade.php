<div style="overflow-x:auto;">
    <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline">
        <thead>
            <tr>
                <th>Acciones</th>
                @foreach(["nro_serie" => "Número de serie", "marca" => "Marca", "modelo" => "Modelo", "precio" => "Precio", "tipo_de_activo" => "Tipo", "estado" => "Estado", "usuario_de_activo" => "Usuario", "responsable_de_activo" => "Responsable", "ubicacionRelation.sitio" => "Sitio", "ubicacionRelation.soporte_ti" => "Soporte TI", "justificacion_doble_activo" => "Justificación"] as $columna => $nombre)

                    <th wire:click="ordenarPor('{{ $columna }}')" style="cursor: pointer;">
                        {{ $nombre }}
                        @if($sortColumn === $columna)
                            @if($sortDirection === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @else
                                <i class="fas fa-sort-down"></i>
                            @endif
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody >
            @foreach($activos as $activo)
                @livewire('activos.fila-activo', ['activo' => $activo], key($activo->id))
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('livewire:navigated', function() {
        Livewire.on('actualizarFila', function() {
            toastr.success('Estado cambiado correctamente.');
        });
        Livewire.on('eventoOrdenar', function(columna) {
            console.log("Evento recibido:", columna);
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
