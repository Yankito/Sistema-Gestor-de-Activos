$(document).ready(function () {
    // Declare table variable in the global scope
    let table;
    let userIsAdmin = $('#tabla').data('user-is-admin');
    let tipoTabla = $('#tabla').data('tipo-tabla');

    // Initialize DataTable
    if (!$.fn.DataTable.isDataTable('#tabla')) {
        table = $("#tabla").DataTable({
            fixedHeader: true,
            scrollX: true,
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
            buttons: [
                {
                    extend: "copy",
                    title: "Iansa - Tabla de " + tipoTabla + " - " + new Date().toLocaleDateString(),
                    text: "Copiar",
                    exportOptions: {
                        columns: ':not(:first)',
                        format: {
                            header: function (data, columnIdx) {
                                // Extraer solo el texto principal del encabezado
                                let tempDiv = document.createElement("div");
                                tempDiv.innerHTML = data; // Convertir el HTML en un nodo DOM
                                return tempDiv.childNodes[0].nodeValue.trim(); // Extraer solo el texto principal
                            }
                        }
                    }
                },
                {
                    extend: "csv",
                    title: "Iansa - Tabla de " + tipoTabla + " - " + new Date().toLocaleDateString(),
                    text: "CSV",
                    exportOptions: {
                        columns: ':not(:first)',
                        format: {
                            header: function (data, columnIdx) {
                                // Extraer solo el texto principal del encabezado
                                let tempDiv = document.createElement("div");
                                tempDiv.innerHTML = data; // Convertir el HTML en un nodo DOM
                                return tempDiv.childNodes[0].nodeValue.trim(); // Extraer solo el texto principal
                            }
                        }
                    }
                },
                {
                    extend: "excel",
                    title: "Iansa - Tabla de " + tipoTabla + " - " + new Date().toLocaleDateString(),
                    text: "Excel",
                    exportOptions: {
                        columns: ":not(:first)",
                        format: {
                            header: function (data, columnIdx) {
                                // Extraer solo el texto principal del encabezado
                                let tempDiv = document.createElement("div");
                                tempDiv.innerHTML = data; // Convertir el HTML en un nodo DOM
                                return tempDiv.childNodes[0].nodeValue.trim(); // Extraer solo el texto principal
                            },
                        }
                    }
                },
                {
                    extend: "print",
                    title: "Iansa - Tabla de " + tipoTabla + " - " + new Date().toLocaleDateString(),
                    text: "Imprimir",
                    exportOptions: {
                        columns: ":not(:first)",
                        format: {
                            header: function (data, columnIdx) {
                                // Extraer solo el texto principal del encabezado
                                let tempDiv = document.createElement("div");
                                tempDiv.innerHTML = data; // Convertir el HTML en un nodo DOM
                                return tempDiv.childNodes[0].nodeValue.trim(); // Extraer solo el texto principal
                            }
                        }
                    }
                },
                { extend: "colvis", text: "Visibilidad de columnas" }
            ]
        });

        // Add buttons to the interface
        table.buttons().container().appendTo('#tabla_wrapper .col-md-6:eq(0)');
    }

    // Store original positions of filter containers
    let filterPositions = {};

    // Show/hide filters on button click (toggle functionality)
    $('.filter-btn').click(function(event) {
        let index = $(this).data('index');
        let filterContainer = $(`#filter-${index}`);

        // Obtener la posición del botón de filtro
        let buttonPosition = $(this).offset();
        let buttonHeight = $(this).outerHeight();

        // Posicionar el contenedor de filtros debajo del botón
        filterContainer.css({
            "top": buttonPosition.top + buttonHeight + 5, // 5px de margen
            "left": buttonPosition.left
        });

        // Llamar a la función toggleFilterContainer con el desplazamiento
        toggleFilterContainer(filterContainer, event, index);
    });

    // Función para alternar la visibilidad del contenedor de filtros
    function toggleFilterContainer(filterContainer, event, index) {
        // Si la posición de desplazamiento no está en la parte superior, desplazar al principio
        if ($(window).scrollTop() !== 0) {
            $('html, body').animate({ scrollTop: 0 }, 'fast', function() {
                // Después de desplazar a|l principio, mostrar el contenedor de filtros
                if (filterContainer.is(':visible')) {
                    filterContainer.hide(); // Ocultar si ya está visible
                } else {
                    // Ocultar otros filtros
                    $('.filter-container').not(filterContainer).hide();
                    filterContainer.show(); // Mostrar el contenedor de filtros
                }
            });
        } else {
            // Si ya está en la parte superior, simplemente alternar la visibilidad
            if (filterContainer.is(':visible')) {
                filterContainer.hide(); // Ocultar si ya está visible
            } else {
                // Ocultar otros filtros
                $('.filter-container').not(filterContainer).hide();
                filterContainer.show(); // Mostrar el contenedor de filtros
            }
        }

        event.stopPropagation();
    }

    // Generate checkboxes for each column
    $('.checkbox-filters').each(function () {
        let index = $(this).data('index');
        let uniqueValues = new Set();

        // Obtener valores únicos de la columna
        table.column(index).data().each(function (value) {
            uniqueValues.add(value);
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
    $(document).on('change', '.select-all', function () {
        let index = $(this).data('index');
        let isChecked = $(this).is(':checked');

        $(`.filter-checkbox[data-index="${index}"]`).each(function () {
            $(this).prop('checked', isChecked);
        });

        // Aplicar el filtro después de actualizar los checkboxes
        applyFilter(index);
    });

    // Search inside checkboxes (but NOT the table)
    $(document).on('input', '.column-search', function() {
        let searchText = $(this).val().toLowerCase();
        let index = $(this).data('index');

        $(`.checkbox-filters[data-index="${index}"] div`).each(function() {
            let text = $(this).find("label").text().toLowerCase();
            $(this).toggle(text.includes(searchText));
        });
    });

    // Evento para actualizar filtro cuando cambian los checkboxes individuales
    $(document).on('change', '.filter-checkbox', function () {
        let index = $(this).data('index');

        updateSelectAllCheckbox(index);

        let checkedValues = [];
        $(`.filter-checkbox[data-index="${index}"]:checked`).each(function () {
            checkedValues.push($(this).val());
        });

        applyFilter(index, checkedValues);
    });



    // Función para aplicar el filtro en la tabla
    function applyFilter(index) {
        let selectedValues = $(`.filter-checkbox[data-index="${index}"]:checked`).map(function () {
            return $.fn.dataTable.util.escapeRegex($(this).val());
        }).get();

        if (selectedValues.length > 0) {
            table.column(index).search(selectedValues.join('|'), true, false).draw();
        } else {
            table.column(index).search('^$', true, false).draw(); // Esto oculta todos los elementos si nada está seleccionado
        }
    }

    // Evento para limpiar todos los filtros
    $('#clear-filters').click(function () {
        table.columns().search('').draw();
        $('.filter-checkbox').prop('checked', false);
        $('.select-all').prop('checked', false);
        $('.filter-container').hide();
    });

    // Debounce scroll event
    let scrollTimeout;
    $(window).on('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            $('.filter-container').hide();
        }, 0); // Adjust the delay as needed
    });
});
