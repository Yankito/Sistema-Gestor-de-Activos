<tr wire:key="activo-{{ $activo->id }}" >
    @include('activos.filasTabla', ['dato' => $activo])
</tr>

<script>
    document.addEventListener('livewire:navigated', function() {
        Livewire.on('cargarModal', function(data) {
            const idFila = JSON.parse('{!! json_encode($activo->id) !!}');
            let id = data[0].id;
            console.log(data);
            if(id == idFila) {
                console.log("ID:", id);
                Livewire.dispatch('refreshModal',  data);
            }
        });
    });
</script>
