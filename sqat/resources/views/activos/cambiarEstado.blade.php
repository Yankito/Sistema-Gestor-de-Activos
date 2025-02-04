<div class="modal-body">
    Cambiar Estado
    <form action="{{ route('activos.update', $activo->id) }}" method="POST">
        @csrf
        <div class="col-md-6 d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary mr-2 toggle-edit" data-target="estado"></i>
            <div class="form-outline mb-4 flex-grow-1">
                <label class="form-label" for="estado">Estado</label>
                <div class="d-flex">
                    <select name="estado" id="estado" class="form-control" required disabled>
                        <option value="ROBADO">Robado</option>
                        <option value="PARA BAJA">Para baja</option>
                        <option value="DONADO">Donado</option>
                    </select>
                    <input type="hidden" name="estado" id="estado_hidden" value="{{ $activo->estado }}">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
    </form>
</div>

<script>
document.querySelectorAll('.toggle-edit').forEach(icon => {
    icon.addEventListener('click', function() {
        let inputId = this.getAttribute('data-target');
        let inputField = document.getElementById(inputId);

        if (inputField.tagName === "SELECT") {
            inputField.disabled = !inputField.disabled;

            // Buscar el input hidden relacionado y actualizar su valor
            let hiddenInput = document.getElementById(inputId + "_hidden");
            if (hiddenInput) {
                hiddenInput.value = inputField.value;
            }

            // Escuchar cambios en el select para actualizar el input hidden
            inputField.addEventListener("change", function() {
                if (hiddenInput) {
                    hiddenInput.value = inputField.value;
                }
            });
        } else {
            inputField.readOnly = !inputField.readOnly;
        }

        this.classList.toggle('fa-pencil-alt');
        this.classList.toggle('fa-check');
        this.classList.toggle('text-primary');
        this.classList.toggle('text-success');
    });
});
</script>
