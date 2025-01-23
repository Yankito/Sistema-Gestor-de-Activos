<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select con Categorías Expandibles</title>
    <style>
        .custom-select {
            position: relative;
            width: 300px;
        }
        .select-display {
            padding: 10px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
        }
        .options-container {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background: #fff;
            z-index: 10;
        }
        .options-container.active {
            display: block;
        }
        .category {
            font-weight: bold;
            padding: 10px;
            background: #f0f0f0;
            cursor: pointer;
        }
        .item {
            padding: 10px;
            cursor: pointer;
        }
        .item:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>

<div class="custom-select">
    <div class="select-display" id="selectDisplay">Seleccione una opción</div>
    <div class="options-container" id="optionsContainer">
        <div class="category" data-category="desktop">Desktop</div>
        <div class="item" data-category="desktop" style="display: none;">Gaming PC</div>
        <div class="item" data-category="desktop" style="display: none;">Workstation</div>
        <div class="item" data-category="desktop" style="display: none;">Mini PC</div>
        <div class="category" data-category="laptop">Laptop</div>
        <div class="item" data-category="laptop" style="display: none;">Ultrabook</div>
        <div class="item" data-category="laptop" style="display: none;">Gaming Laptop</div>
        <div class="item" data-category="laptop" style="display: none;">Business Laptop</div>
        <div class="category" data-category="monitor">Monitor</div>
        <div class="item" data-category="monitor" style="display: none;">4K Monitor</div>
        <div class="item" data-category="monitor" style="display: none;">Gaming Monitor</div>
        <div class="item" data-category="monitor" style="display: none;">Curved Monitor</div>
    </div>
</div>

<script>
    const selectDisplay = document.getElementById('selectDisplay');
    const optionsContainer = document.getElementById('optionsContainer');
    const categories = document.querySelectorAll('.category');
    const items = document.querySelectorAll('.item');

    // Toggle dropdown visibility
    selectDisplay.addEventListener('click', () => {
        optionsContainer.classList.toggle('active');
    });

    // Toggle category items
    categories.forEach(category => {
        category.addEventListener('click', () => {
            const categoryName = category.dataset.category;
            const categoryItems = document.querySelectorAll(`.item[data-category="${categoryName}"]`);
            categoryItems.forEach(item => {
                item.style.display = item.style.display === 'none' ? 'block' : 'none';
            });
        });
    });

    // Handle item selection
    items.forEach(item => {
        item.addEventListener('click', () => {
            selectDisplay.textContent = item.textContent;
            optionsContainer.classList.remove('active');
            items.forEach(i => i.style.display = 'none'); // Hide all items
        });
    });

    // Close dropdown if clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.custom-select')) {
            optionsContainer.classList.remove('active');
        }
    });
</script>

</body>
</html>
