$(document).ready(function () {
    if ($.fn.DataTable.isDataTable("#tabla")) {
        $("#tabla").DataTable().destroy();
    }

    let table = $("#tabla").DataTable({
        "fixedHeader": true,
        "scrollX": true,
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
    $('.filter-btn').click(function(event) {
        let index = $(this).data('index');
        let filterContainer = $(`#filter-${index}`);

        $('.filter-container').not(filterContainer).hide();

        $(`#filter-${index}`).toggle();

            // Obtener posición del botón y ajustar la posición del filtro
            let btnOffset = $(this).offset();
            filterContainer.css({
                "top": btnOffset.top + $(this).outerHeight() + "px", // Justo debajo del botón
                "left": btnOffset.left + "px",
                "position": "absolute",
                "z-index": "1000",
                "display": "block"
            });
        event.stopPropagation();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('.filter-container').length) {
            $('.filter-container').hide();
        }
    });

    // Filtrado por texto en columnas
    $('.column-search').on('keyup', function() {
        let index = $(this).data('index');
        table.column(index).search(this.value).draw();
    });

    // Filtrado por checkboxes en columnas
    table.columns().every(function(index) {
        let column = this;
        let uniqueValues = new Set();

        column.data().each(function(value) {
            uniqueValues.add(value);
        });

        let checkboxContainer = $(`.checkbox-filters[data-index="${index}"]`);
        uniqueValues.forEach(value => {
            checkboxContainer.append(
                `<label><input type="checkbox" class="column-checkbox" data-index="${index}" value="${value}"> ${value}</label><br>`
            );
        });

        checkboxContainer.on('change', 'input', function() {
            let checkedValues = checkboxContainer.find('input:checked')
                .map(function() {
                    return $.fn.dataTable.util.escapeRegex($(this).val());
                }).get().join('|');

            column.search(checkedValues.length ? `^(${checkedValues})$` : '', true, false).draw();
        });
    });
});

