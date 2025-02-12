<tr wire:key="activo-{{ $activo->id }}" wire:poll>
    @include('activos.filasTabla', ['dato' => $activo])
</tr>
