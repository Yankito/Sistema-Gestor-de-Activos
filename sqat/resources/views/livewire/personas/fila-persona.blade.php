<tr>
    <style>
        td {
            font-size: 12px;
        }
    </style>
    <td class="action-btns">
        @if($user->es_administrador)
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" wire:click="editarPersona('{{ $persona->id }}')">
                <i class="fas fa-user-edit"></i>
            </button>
        @endif
    </td>

    <td>{{ $persona->user }}</td>
    <td>{{ $persona->rut }}</td>
    <td>{{ $persona->nombre_completo }}</td>
    <td>{{ $persona->nombre_empresa }}</td>
    <td>
        <span class="estado-badge
            {{ $persona->estado_empleado === 1 ? 'estado-activo' : '' }}
            {{ $persona->estado_empleado === 0 ? 'estado-inactivo' : '' }}">
            {{ $persona->estado_empleado === 1    ? 'Activo' : 'Inactivo' }}
        </span>
    </td>
    <td>{{ $persona->fecha_ing}}</td>
    <td>{{ $persona->fecha_ter}}</td>
    <td>{{ $persona->cargo }}</td>
    <td>{{ $persona->ubicacionRelation->sitio }}</td>
    <td>{{ $persona->correo }}</td>
</tr>
