    <td class="action-btns">
        @if ($dato->estado === 1)
            <button type="button" class="btn btn-primary btn-sm" wire:click="cambiarEstado('{{ $dato->id }}', 2)">
                <i class="fas fa-arrow-right"></i>
            </button>
        @elseif ($dato->estado === 2)
            <button type="button" class="btn btn-primary btn-sm" wire:click="cambiarEstado('{{ $dato->id }}', 3)">
                <i class="fas fa-arrow-right"></i>
            </button>
        @elseif ($dato->estado === 3 || $dato->estado === 4)
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo('{{ $dato->id }}')">
                <i class="fas fa-edit"></i>
            </button>
        @elseif ($dato->estado === 5 || $dato->estado === 6)
            <button type="button" class="btn btn-success btn-sm" wire:click="cambiarEstado('{{ $dato->id }}', 7)">
                <i class="fas fa-undo"></i>
            </button>
        @elseif ($dato->estado === 7)
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default" onclick="cargarActivo('{{ $dato->id }}')">
                <i class="fas fa-edit"></i>
            </button>
        @elseif ($dato->estado === 8 || $dato->estado === 9 || $dato->estado === 10)
            <button type="button" class="btn btn-secondary btn-sm" disabled>
                <i class="fas fa-check-circle"></i>
            </button>
        @endif

    </td>
    <td>{{ $dato->nro_serie }}</td>
    <td>{{ $dato->marca }}</td>
    <td>{{ $dato->modelo }}</td>
    <td>{{ number_format($dato->precio, 0, ',', '.') }}</td>
    <td>{{ $dato->tipo_de_activo }}</td>
    <td>
        <span class="estado-badge
            {{ $dato->estado === 1 ? 'estado-adquirido' : '' }}
            {{ $dato->estado === 2 ? 'estado-preparacion' : '' }}
            {{ $dato->estado === 3 ? 'estado-disponible' : '' }}
            {{ $dato->estado === 4 ? 'estado-asignado' : '' }}
            {{ $dato->estado === 5 ? 'estado-perdido' : '' }}
            {{ $dato->estado === 6 ? 'estado-robado' : '' }}
            {{ $dato->estado === 7 ? 'estado-devuelto' : '' }}
            {{ $dato->estado === 8 ? 'estado-paraBaja' : '' }}
            {{ $dato->estado === 9 ? 'estado-donado' : '' }}
            {{ $dato->estado == 10 ? 'estado-vendido' : '' }}">
            {{ $dato->estadoRelation->nombre_estado }}
        </span>
    </td>
    <td>{{ $dato->usuarioDeActivo->rut ?? '' }}</td>
    <td>{{ $dato->responsableDeActivo->rut ?? '' }}</td>
    <td>{{ $dato->ubicacionRelation->sitio }}</td>
    <td>{{ $dato->ubicacionRelation->soporte_ti }}</td>
    <td>{{ $dato->justificacion_doble_activo }}</td>
