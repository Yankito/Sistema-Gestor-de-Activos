$(document).ready(function () {
    // Destruir la tabla si ya está inicializada
    if ($.fn.DataTable.isDataTable("#tabla")) {
        $("#tabla").DataTable().destroy();
    }

    // Inicializar la tabla de datos
    var table = $('#tabla').DataTable({
        "fixedHeader": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": true,
        "searching": true, // Asegura que el filtrado esté habilitado
        "buttons": [
            {
                extend: "copy",
                title: "Iansa - Tabla de activos",
                text: "Copiar",
            },
            {
                extend: "csv",
                title: "Iansa - Tabla de activos",
                text: "CSV",
            },
            {
                extend: "excel",
                title: "Iansa - Tabla de activos",
                text: "Excel",
            },
            {
                extend: "print",
                title: "Iansa - Tabla de activos",
                text: "Imprimir",
            },
            {
                extend: "colvis",
                text: "Visibilidad de columnas",
            }
        ]
    });

    // Agregar botones a la interfaz
    table.buttons().container().appendTo('#tabla_wrapper .col-md-6:eq(0)');

    // Manejador para mostrar/ocultar filtros
    $('.filter-btn').click(function (event) {
        event.stopPropagation(); // Detiene que se active la ordenación
        let index = $(this).data('index');
        $(`#filter-${index}`).toggle(); // Muestra u oculta el filtro
    });

    // Evitar que el título active la ordenación si no es el ícono de ordenación
    $('#tabla thead th').on('click', function (event) {
        let target = $(event.target);

        // Si se hace clic en el botón de filtro o en el contenedor del filtro, no ordenar
        if (target.hasClass('filter-btn') || target.closest('.filter-container').length > 0) {
            event.stopPropagation();
            return false;
        }
    });

    // Filtrado por texto en columnas
    $('.column-search').on('keyup', function () {
        let index = $(this).data('index');
        table.column(index).search(this.value).draw();
    });

    // Filtrado por checkboxes en columnas
    table.columns().every(function (index) {
        let column = this;
        let uniqueValues = new Set();

        column.data().each(function (value) {
            uniqueValues.add(value);
        });

        let checkboxContainer = $(`.checkbox-filters[data-index="${index}"]`);
        uniqueValues.forEach(value => {
            checkboxContainer.append(
                `<label><input type="checkbox" class="column-checkbox" data-index="${index}" value="${value}"> ${value}</label><br>`
            );
        });

        checkboxContainer.on('change', 'input', function () {
            let checkedValues = checkboxContainer.find('input:checked')
                .map(function () {
                    return $.fn.dataTable.util.escapeRegex($(this).val());
                }).get().join('|');

            column.search(checkedValues.length ? `^(${checkedValues})$` : '', true, false).draw();
        });
    });
});
