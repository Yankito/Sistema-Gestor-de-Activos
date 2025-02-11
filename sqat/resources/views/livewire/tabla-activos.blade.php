<div style = "overflow-x:auto;">
    <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline">
    <thead>
        <tr>
            <th>Acciones</th>
            @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación"] as $index => $columna)
                <th>
                {{ $columna }}
                <!-- boton filtro -->
                <button class="filter-btn" data-index="{{ $index + 1 }}">
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
        @foreach($activos as $dato)
            @livewire('fila-activo', ['activo' => $dato], key($dato->id))
        @endforeach
    </tbody>
    </table>
</div>


<script>

document.addEventListener('livewire:navigated', function() {
    Livewire.on('actualizarFila', function(activoActualizado) {
        let activo = activoActualizado[0]; // Obtener el activo actualizado
        console.log("Refrescando fila:", activo.id);

        // Use Livewire.emitTo to refresh the specific row component
        Livewire.dispatch(`fila-activo`, 'refreshFila', activo.id);
    });
});


    Livewire.on('estadoCambiado2', (activoActualizado) => {
        // Actualiza la fila correspondiente con el nuevo estado
        const fila = document.querySelector(`tr[wire\\:key="activo-${activoActualizado.id}"]`);
        const estadoBadge = fila.querySelector('.estado-badge');
        estadoBadge.classList.remove(...estadoBadge.classList);
        estadoBadge.classList.add('estado-badge', obtenerClaseEstado(nuevoEstado));
        fila.find('td').each(function(index) {
            console.log(index);
            switch(index) {
                case 0:
                    if (response.activoModificado.estado === 1) {
                        $(this).html('<button type="button" class="btn btn-primary btn-sm" onclick="wire.click=(\'' + activoId + '\', 2)"><i class="fas fa-arrow-right"></i></button>');
                    } else if (response.activoModificado.estado === 2) {
                        $(this).html('<button type="button" class="btn btn-primary btn-sm" onclick="wire.click=(\'' + activoId + '\', 3)"><i class="fas fa-arrow-right"></i></button>');
                    } else if (response.activoModificado.estado === 3 || response.activoModificado.estado === 4) {
                        $(this).html('<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo(\'' + activoId + '\')"><i class="fas fa-edit"></i></button>');
                    } else if (response.activoModificado.estado === 5 || response.activoModificado.estado === 6) {
                        $(this).html('<button type="button" class="btn btn-success btn-sm" onclick="wire.click=(\'' + activoId + '\', 7)"><i class="fas fa-undo"></i></button>');
                    } else if (response.activoModificado.estado === 7) {
                        $(this).html('<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo(\'' + activoId + '\')"><i class="fas fa-edit"></i></button>');
                    } else if (response.activoModificado.estado === 8 || response.activoModificado.estado === 9 || response.activoModificado.estado === 10) {
                        $(this).html('<button type="button" class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-circle"></i></button>');
                    }
                case 1:
                    $(this).html(response.activoModificado.numero_serie);
                    break;
                case 2:
                    $(this).html(response.activoModificado.marca);
                    break;
                case 3:
                    $(this).html(response.activoModificado.modelo);
                    break;
                case 4:
                    $(this).html(response.activoModificado.precio);
                    break;
                case 5:
                    $(this).html(response.activoModificado.tipo);
                    break;
                case 6:
                    $(this).html('<span class="estado-badge ' + obtenerClaseEstado(nuevoEstado) + '">' + response.activoModificado.estado_relation.nombre_estado + '</span>');
                    break;
                case 7:
                    $(this).html(response.activoModificado.usuario);
                    break;
                case 8:
                    $(this).html(response.activoModificado.responsable);
                    break;
                case 9:
                    $(this).html(response.activoModificado.sitio);
                    break;
                case 10:
                    $(this).html(response.activoModificado.soporte_ti);
                    break;
                case 11:
                    $(this).html(response.activoModificado.justificacion);
                    break;
            }
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

    Livewire.on('estadoCambiado', (activoActualizado) => {
        Swal.fire({
            icon: 'success',
            title: "Estado actualizado correctamente",
            confirmButtonText: 'Aceptar'
        });
    });
</script>
