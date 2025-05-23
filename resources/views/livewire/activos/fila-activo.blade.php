<tr>
    <style>
        td {
            font-size: 12px;
        }
        i {
            flex-shrink: 0; /* Evita que el icono se reduzca de tamaño */
            width: 16px; /* Asegura un tamaño uniforme del icono */
            text-align: center;
        }
    </style>
    <td>
        @if($user->es_administrador)
            @if ($activo->estado === 1)
                <button type="button" class="btn btn-info btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 2)">
                    <i class="fas fa-arrow-right"></i>
                </button>
            @elseif ($activo->estado === 2)
                <button type="button" class="btn btn-info btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 3)">
                    <i class="fas fa-arrow-right"></i>
                </button>
            @elseif ($activo->estado === 3)
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" wire:click="editarActivo('{{ $activo->id }}')">
                    <i class="fas fa-user-plus"></i>
                </button>
            @elseif ($activo->estado === 4)
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" wire:click="editarActivo('{{ $activo->id }}')">
                    <i class="fas fa-user-minus"></i>
                </button>
            @elseif ($activo->estado === 5 || $activo->estado === 6)
                <button type="button" class="btn btn-success btn-sm" wire:click="cambiarEstado('{{ $activo->id }}', 7)">
                    <i class="fas fa-undo"></i>
                </button>
            @elseif ($activo->estado === 7)
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" wire:click="editarActivo('{{ $activo->id }}')">
                    <i class="fas fa-cogs"></i>
                </button>
            @elseif ($activo->estado === 8 || $activo->estado === 9 || $activo->estado === 10)
                <button type="button" class="btn btn-secondary btn-sm" disabled>
                    <i class="fas fa-check-circle"></i>
                </button>
            @endif
            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" wire:click="editarActivoValores('{{ $activo->id }}')">
                <i class="fas fa-edit"></i>
            </button>
        @endif
    </td>
    <td>{{ $activo->nro_serie }}</td>
    <td>{{ $activo->marca }}</td>
    <td>{{ $activo->modelo }}</td>
    <td>{{ number_format($activo->precio, 0, ',', '.') }}</td>
    <td>{{ $activo->tipoDeActivo->nombre }}</td>
    <td>
        <span class="estado-badge
            {{ $activo->estado === 1 ? 'estado-adquirido' : '' }}
            {{ $activo->estado === 2 ? 'estado-preparacion' : '' }}
            {{ $activo->estado === 3 ? 'estado-disponible' : '' }}
            {{ $activo->estado === 4 ? 'estado-asignado' : '' }}
            {{ $activo->estado === 5 ? 'estado-perdido' : '' }}
            {{ $activo->estado === 6 ? 'estado-robado' : '' }}
            {{ $activo->estado === 7 ? 'estado-devuelto' : '' }}
            {{ $activo->estado === 8 ? 'estado-paraBaja' : '' }}
            {{ $activo->estado === 9 ? 'estado-donado' : '' }}
            {{ $activo->estado == 10 ? 'estado-vendido' : '' }}">
            {{ $activo->estadoRelation->nombre_estado }}
        </span>
    </td>
    <td style="white-space: nowrap;">
        @php
            $usuarios = $activo->usuarioDeActivo;
            $totalUsuarios = $usuarios->count();
        @endphp

        @if ($totalUsuarios > 0)
            <ul>
                @foreach ($usuarios->take(3) as $usuario)
                    <li style="font-size: 12px;">{{ $usuario->nombre_completo }} ({{ $usuario->user }})</li>
                @endforeach

                @if ($totalUsuarios > 3)
                        <div id="usuarios-{{ $activo->id }}" class="collapse">
                            @foreach ($usuarios->skip(3) as $usuario)
                                <li style="font-size: 12px;">{{ $usuario->nombre_completo }} ({{ $usuario->user }})</li>
                            @endforeach
                        </div>
                    <button type="button" class="btn btn-link btn-sm" data-toggle="collapse" data-target="#usuarios-{{ $activo->id }}">
                        Ver más
                    </button>
                @endif
            </ul>

        @else
            <span style="font-size: 12px;">Sin usuarios</span>
        @endif
    </td>
    <td>
        {{ $activo->responsableDeActivo->nombre_completo ?? 'Sin responsable' }} ({{ $activo->responsableDeActivo->user ?? 'Sin usuario' }})
    </td>
    <td>{{ $activo->ubicacionRelation->sitio }}</td>
    <td>{{ $activo->ubicacionRelation->soporte_ti }}</td>
    <td>{{ $activo->justificacion_doble_activo }}</td>
    <td>
        @php
            $totalValores = $activo->valoresAdicionales->count();
        @endphp
        @foreach ($activo->valoresAdicionales->take(3) as $valor)
            <ul>
                <li style="font-size: 12px;">{{ $valor->idCaracteristica->nombre_caracteristica }}: {{ $valor->valor }}</li>
            </ul>
        @endforeach
        @if ($totalValores > 3)
            <div id="valores-{{ $activo->id }}" class="collapse">
                @foreach ($activo->valoresAdicionales->skip(3) as $valor)
                    <ul>
                        <li style="font-size: 12px;">{{ $valor->idCaracteristica->nombre_caracteristica }}: {{ $valor->valor }}</li>
                    </ul>
                @endforeach
            </div>
            <button type="button" class="btn btn-link btn-sm" data-toggle="collapse" data-target="#valores-{{ $activo->id }}">
                    Ver más
            </button>
        @endif
    </td>
</tr>
