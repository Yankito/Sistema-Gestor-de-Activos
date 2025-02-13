$(document).ready(function () {
    // Declare table variable in the global scope
    let table;

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
            order: $('#tabla').data('order'),
            buttons: [
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

        // Add buttons to the interface
        table.buttons().container().appendTo('#tabla_wrapper .col-md-6:eq(0)');
    }

    // Show/hide filters on button click (toggle functionality)
    $('.filter-btn').click(function(event) {
        let index = $(this).data('index');
        let filterContainer = $(`#filter-${index}`);

        // If the scroll position is not at the top, scroll to the top first
        if ($(window).scrollTop() !== 0) {
            $('html, body').animate({ scrollTop: 0 }, 'fast', function() {
                // After scrolling to the top, show the filter container
                toggleFilterContainer(filterContainer, event);
            });
        } else {
            // If already at the top, just toggle the filter container
            toggleFilterContainer(filterContainer, event);
        }

        event.stopPropagation();
    });

    // Function to toggle the filter container
    function toggleFilterContainer(filterContainer, event) {
        if (filterContainer.is(':visible')) {
            filterContainer.hide(); // Hide if already visible
        } else {
            // Hide other filters
            $('.filter-container').not(filterContainer).hide();

            // Get button position
            const btnRect = event.target.getBoundingClientRect();
            let filterHeight = filterContainer.outerHeight();
            let filterWidth = filterContainer.outerWidth();
            let windowHeight = $(window).height();
            let windowWidth = $(window).width();
            let scrollY = window.scrollY;
            let scrollX = window.scrollX;

            // Calculate top position
            let topPosition = btnRect.bottom + scrollY;
            if (topPosition + filterHeight > scrollY + windowHeight) {
                topPosition = btnRect.top + scrollY - filterHeight;
            }
            topPosition = Math.max(scrollY, Math.min(topPosition, scrollY + windowHeight - filterHeight));

            // Calculate left position
            let leftPosition = btnRect.left + scrollX;
            if (leftPosition + filterWidth > scrollX + windowWidth) {
                leftPosition = btnRect.right + scrollX - filterWidth;
            }
            leftPosition = Math.max(scrollX, Math.min(leftPosition, scrollX + windowWidth - filterWidth));

            // Apply the position
            filterContainer.css({
                "top": `${topPosition}px`,
                "left": `${leftPosition}px`
            }).show(); // Show the filter container
        }
    }

    // Hide filter container when clicking the close button
    $('.filter-container').on('click', '.close-filter', function() {
        $(this).closest('.filter-container').hide();
    });

    // Prevent filter container from hiding on checkbox click
    $('.filter-container').click(function(event) {
        event.stopPropagation();
    });

    // Generate checkboxes for each column
    $('.checkbox-filters').each(function() {
        let index = $(this).data('index');
        let uniqueValues = new Set();

        table.column(index).data().each(function(value) {
            uniqueValues.add(value);
        });

        let checkboxContainer = $(this);
        checkboxContainer.append(`
            <div>
                <input type="checkbox" class="select-all" data-index="${index}">
                <label>Seleccionar todo</label>
            </div>
        `);
        uniqueValues.forEach(value => {
            checkboxContainer.append(`
                <div>
                    <input type="checkbox" class="filter-checkbox" data-index="${index}" value="${value}">
                    <label>${value}</label>
                </div>
            `);
        });
    });

    // Select/Deselect all checkboxes
    $('.checkbox-filters').on('change', '.select-all', function() {
        let index = $(this).data('index');
        let isChecked = $(this).is(':checked');
        $(`.filter-checkbox[data-index="${index}"]`).prop('checked', isChecked).trigger('change');
    });

    // Search inside checkboxes (but NOT the table)
    $('.column-search').on('input', function() {
        let searchText = $(this).val().toLowerCase();
        let index = $(this).data('index');

        $(`.checkbox-filters[data-index="${index}"] div`).each(function() {
            let text = $(this).find("label").text().toLowerCase();
            $(this).toggle(text.includes(searchText));
        });
    });

    // Filter table based on selected checkboxes
    $('.filter-checkbox').on('change', function() {
        let index = $(this).data('index');
        let selectedValues = $(`.filter-checkbox[data-index="${index}"]:checked`).map(function() {
            return $(this).val().replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
        }).get();

        if (selectedValues.length > 0) {
            table.column(index).search(selectedValues.join('|'), true, false, false).draw();
        } else {
            // If no checkboxes are selected, hide all rows
            table.column(index).search('^$', true, false, false).draw();
        }
    });

    // Clear all filters when the "Clear Filters" button is clicked
    $('#clear-filters').click(function() {
        // Clear all column searches
        table.columns().search('').draw();

        // Uncheck all filter checkboxes
        $('.filter-checkbox').prop('checked', false);

        // Hide all filter containers
        $('.filter-container').hide();
    });

    // Debounce scroll event
    let scrollTimeout;
    $(window).on('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            $('.filter-container').hide();
        }, 1); // Adjust the delay as needed
    });
});