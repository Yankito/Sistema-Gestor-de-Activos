$(document).ready(function () {
    // Declare table variable in the global scope
    let table;
    let userIsAdmin = $('#tabla').data('user-is-admin');
    let tipoTabla = $('#tabla').data('tipo-tabla');

    // Initialize DataTable
    if (!$.fn.DataTable.isDataTable('#tabla')) {
        table = $("#tabla").DataTable({
            fixedHeader: true,
            scrollX: true, // Habilitar scroll horizontal
            responsive: false,
            lengthChange: false,
            autoWidth: true,
            searching: true,
            pageLength: $('#tabla').data('page-length'),
            order: [[1,'asc ']],
            destroy: true,  // Permite reinicializar la tabla sin errores
            retrieve: true, // Recupera la instancia existente en lugar de crear una nueva
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    width: '80px', // Set the width of column 0 to 100px
                    visible: userIsAdmin // Replace with a valid JavaScript variable or condition
                }
            ],
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                infoFiltered: "(filtrado de _MAX_ registros en total)",
                loadingRecords: "Cargando...",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "No hay datos disponibles en la tabla",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            buttons: [
                crearBotonExportacion("copy", "Copiar"),
                crearBotonExportacion("csv", "CSV"),
                crearBotonExportacion("excel", "Excel"),
                crearBotonExportacion("print", "Imprimir"),
                { extend: "colvis", text: "Visibilidad de columnas" }
            ]
        });

        // Add buttons to the interface
        table.buttons().container().appendTo('#tabla_wrapper .col-md-6:eq(0)');
    }

    function crearBotonExportacion(tipo, texto) {
        return {
            extend: tipo,
            title: "Iansa - Tabla de " + tipoTabla + " - " + new Date().toLocaleDateString('en-GB').split('/').reverse().join('-'),
            text: texto,
            exportOptions: {
            columns: ':not(:first)',
            format: {
                header: function (data, columnIdx) {
                    let tempDiv = document.createElement("div");
                    tempDiv.innerHTML = data;
                    return tempDiv.childNodes[0].nodeValue.trim();
                    }
                }
            }
        };
    }

    // Store original positions of filter containers
    let filterPositions = {};

    // Show/hide filters on button click (toggle functionality)
    $('.filter-btn').click(function(event) {
        event.stopPropagation(); // Detener la propagación del evento
        let index = $(this).data('index');
        let filterContainer = $(`#filter-${index}`);

        // Obtener la posición del botón de filtro
        let buttonPosition = $(this).offset();
        let scrollTop = $(window).scrollTop(); // Obtener el desplazamiento vertical

        // Ajustar la posición del contenedor de filtros
        filterContainer.css({
            "top": buttonPosition.top - scrollTop + $(this).outerHeight(), // Ajustar con el scrollTop
            "left": buttonPosition.left,
            "position": "fixed" // Usar posición fija para que se ajuste con el scroll
        });

        // Llamar a la función toggleFilterContainer con el desplazamiento
        toggleFilterContainer(filterContainer, event, index);
    });

    // Función para alternar la visibilidad del contenedor de filtros
    function toggleFilterContainer(filterContainer, event, index) {
        // Si ya está en la parte superior, simplemente alternar la visibilidad
        if (filterContainer.is(':visible')) {
            filterContainer.hide(); // Ocultar si ya está visible
        } else {
            // Ocultar otros filtros
            $('.filter-container').not(filterContainer).hide();
            filterContainer.show(); // Mostrar el contenedor de filtros
        }

        event.stopPropagation(); // Detener la propagación del evento
    }



    // Cerrar el filtro al hacer clic fuera del contenedor
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.filter-container').length && !$(event.target).closest('.filter-btn').length) {
            $('.filter-container').hide(); // Ocultar todos los filtros
        }
    });

    // Generate checkboxes for each column
    $('.checkbox-filters').each(function () {
        let index = $(this).data('index');
        let uniqueValues = new Set();

        // Obtener valores únicos de la columna
        table.column(index).nodes().each(function (node) {
            let cellText = $(node).text().trim(); // Extraer solo el texto visible
            uniqueValues.add(cellText);
        });

        // Limpiar contenedor antes de agregar nuevos checkboxes
        let checkboxContainer = $(this);
        checkboxContainer.empty();

        // Agregar checkbox "Seleccionar todo"
        checkboxContainer.append(`
            <div>
                <input type="checkbox" class="select-all" data-index="${index}" checked>
                <label>Seleccionar todo</label>
            </div>
        `);

        // Agregar checkboxes individuales
        [...uniqueValues].forEach(value => {
            checkboxContainer.append(`
                <div>
                    <input type="checkbox" class="filter-checkbox" data-index="${index}" value="${value}">
                    <label>${value}</label>
                </div>
            `);
        });

        let isChecked = $(`.select-all[data-index="${index}"]`).is(':checked');

        $(`.filter-checkbox[data-index="${index}"]`).each(function () {
            $(this).prop('checked', isChecked);
        });

    });

    // Evento para aplicar el filtro
    $(document).on('click', '.aplicar-filtro', function (event) {
        console.log("Aplicando filtro...");
        event.stopPropagation(); // Detener la propagación del evento
        let index = $(this).data('index');
        applyFilter(index);
        $(`#filter-${index}`).hide(); // Ocultar el contenedor de filtros
        updateCheckboxes();
    });

    // Funcion para actualizar los checkbox de todas las columnas y eliminar checkbox de columnas ocultas
    function updateCheckboxes() {
        // Obtener los datos visibles en la tabla filtrada
        let visibleData = table.rows({ search: 'applied' }).data();

        // Iterar sobre cada columna para actualizar los checkboxes
        table.columns().every(function (index) {
            // Verificar si la columna tiene un filtro activo
            let isFilterActive = $(`.filter-btn[data-index="${index}"]`).hasClass('filter-active');

            // Si la columna tiene un filtro activo, no actualizamos sus checkboxes
            if (isFilterActive) {
                return; // Saltar esta columna
            }

            let uniqueValues = new Set();

            // Obtener valores únicos de la columna en los datos visibles
            visibleData.column(index).nodes().each(function (node) {
                let cellText = $(node).text().trim(); // Extraer solo el texto visible
                uniqueValues.add(cellText);
            });

            // Limpiar contenedor antes de agregar nuevos checkboxes
            let checkboxContainer = $(`.checkbox-filters[data-index="${index}"]`);
            checkboxContainer.empty();

            // Agregar checkbox "Seleccionar todo"
            checkboxContainer.append(`
                <div>
                    <input type="checkbox" class="select-all" data-index="${index}" checked>
                    <label>Seleccionar todo</label>
                </div>
            `);

            // Agregar checkboxes individuales
            [...uniqueValues].forEach(value => {
                checkboxContainer.append(`
                    <div>
                        <input type="checkbox" class="filter-checkbox" data-index="${index}" value="${value}" checked>
                        <label>${value}</label>
                    </div>
                `);
            });

            // Actualizar el estado del checkbox "Seleccionar todo"
            updateSelectAllCheckbox(index);
        });
    }

    // Función para actualizar el estado del checkbox "Seleccionar todo"
    function updateSelectAllCheckbox(index) {
        let uniqueCheckboxes = new Set();
        $(`.filter-checkbox[data-index="${index}"]`).each(function () {
            uniqueCheckboxes.add($(this).val());
        });
        let totalCheckboxes = uniqueCheckboxes.size;

        let checkedCheckboxes = $(`.filter-checkbox[data-index="${index}"]:checked`).length;

        let selectAll = $(`.select-all[data-index="${index}"]`);
        selectAll.prop('checked', totalCheckboxes === checkedCheckboxes);
    }

    // Evento para seleccionar/deseleccionar todos los checkboxes
    $(document).on('change', '.select-all', function (event) {
        event.stopPropagation(); // Detener la propagación del evento
        let index = $(this).data('index');
        let isChecked = $(this).is(':checked');

        $(`.filter-checkbox[data-index="${index}"]`).each(function () {
            $(this).prop('checked', isChecked);
        });

        // Aplicar el filtro después de actualizar los checkboxes
        //applyFilter(index);
    });

    // Search inside checkboxes (but NOT the table)
    $(document).on('input', '.column-search', function(event) {
        event.stopPropagation(); // Detener la propagación del evento
        let searchText = $(this).val().toLowerCase();
        let index = $(this).data('index');

        $(`.checkbox-filters[data-index="${index}"] div`).each(function() {
            let text = $(this).find("label").text().toLowerCase();
            $(this).toggle(text.includes(searchText));
        });
    });

    // Evento para actualizar filtro cuando cambian los checkboxes individuales
    $(document).on('change', '.filter-checkbox', function (event) {
        event.stopPropagation(); // Detener la propagación del evento
        let index = $(this).data('index');

        updateSelectAllCheckbox(index);

        //applyFilter(index);
    });

    // Función para aplicar el filtro en la tabla
    function applyFilter(index) {
        let selectedValues = $(`.filter-checkbox[data-index="${index}"]:checked`).map(function () {
            return $.fn.dataTable.util.escapeRegex($(this).val());
        }).get();

        if (selectedValues.length > 0) {
            table.column(index).search(selectedValues.join('|'), true, false).draw();
            // Resaltar el ícono de filtrado
        } else {
            table.column(index).search('^$', true, false).draw(); // Esto oculta todos los elementos si nada está seleccionado
        }
        let cantidadCheckboxes = $(`.filter-checkbox[data-index="${index}`).length;
        if(selectedValues.length === cantidadCheckboxes){
            $(`.filter-btn[data-index="${index}"]`).removeClass('filter-active');
        }
        else{
            $(`.filter-btn[data-index="${index}"]`).addClass('filter-active');
        }
    }

    // Evento para limpiar todos los filtros
    $('#clear-filters').click(function () {
        table.columns().search('').draw();
        $('.filter-checkbox').prop('checked', true);
        $('.select-all').prop('checked', true);
        $('.filter-container').hide();
        updateCheckboxes();
        eliminarFilterActive();
    });

    function eliminarFilterActive() {
        $('.filter-btn').removeClass('filter-active');
    }

    // Debounce scroll event (vertical y horizontal)
    let scrollTimeout;
    $(window).on('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            $('.filter-container').hide();
        }, 100); // Ajusta el retraso según sea necesario
    });

    // Detectar scroll horizontal en el contenedor de scroll de la tabla
    $('#tabla .dataTables_scrollBody').on('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            $('.filter-container').hide();
        }, 100); // Ajusta el retraso según sea necesario
    });
});
