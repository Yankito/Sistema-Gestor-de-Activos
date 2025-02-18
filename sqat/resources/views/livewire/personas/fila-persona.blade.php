<tr wire:key="persona-{{ $persona->id }}" wire:poll>
    <td class="action-btns">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" wire:click="editarPersona('{{ $persona->id }}')">
            <i class="fas fa-edit"></i>
        </button>
    </td>
    <td>{{ $persona->rut }}</td>
    <td>{{ $persona->nombre_usuario }}</td>
    <td>{{ $persona->nombres }}</td>
    <td>{{ $persona->primer_apellido }}</td>
    <td>{{ $persona->segundo_apellido }}</td>
    <td>{{ $persona->supervisor }}</td>
    <td>{{ $persona->empresa }}</td>
    <td>{{ $persona->estado_empleado }}</td>
    <td>{{ $persona->centro_costo }}</td>
    <td>{{ $persona->denominacion }}</td>
    <td>{{ $persona->titulo_puesto }}</td>
    <td>{{ $persona->fecha_inicio }}</td>
    <td>{{ $persona->usuario_ti }}</td>
    <td>{{ $persona->ubicacionRelation->sitio }}</td>
</tr>
