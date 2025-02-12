@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Exportar Datos</h3>

    <label for="tablasSeleccionadas">Selecciona la tabla a exportar:</label>
    <div id="tablasSeleccionadas" class="d-flex justify-content-center mt-3">
        <div class="option" data-value="activos">
            <i class="fas fa-laptop fa-2x"></i>
            <div>Tabla de Activos</div>
        </div>
        <div class="option" data-value="personas">
            <i class="fas fa-user fa-2x"></i>
            <div>Tabla de Personas</div>
        </div>
        <div class="option" data-value="activos_personas">
            <i class="fas fa-users fa-2x"></i>
            <div>Activos + Personas</div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <button id="btnExcel" class="btn btn-success mx-2">Exportar a Excel</button>
        <button id="btnCSV" class="btn btn-primary mx-2">Exportar a CSV</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.option').click(function () {
            $('.option').removeClass('selected');
            $(this).addClass('selected');
        });

        $('#btnExcel').click(function () {
            exportarTabla('excel');
        });

        $('#btnCSV').click(function () {
            exportarTabla('csv');
        });

        function exportarTabla(formato) {
            let tabla = $('.option.selected').data('value');
            if (!tabla) {
                alert('Por favor, selecciona una tabla para exportar.');
                return;
            }

            window.location.href = `/exportar/${tabla}/${formato}`;
        }
    });
</script>

<style>
    .option {
        cursor: pointer;
        padding: 10px; /* Reduce el padding */
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 5px; /* Reduce el margen */
        text-align: center;
        width: 150px;
        transition: background-color 0.3s, color 0.3s;
    }

    .option:hover {
        background-color: #555; /* Oscurece el cuadro al pasar el cursor */
        color: white;
    }

    .option.selected {
        background-color: #007bff;
        color: white;
    }

    .option i {
        margin-bottom: 5px; /* Reduce el margen inferior del icono */
    }

    .option div {
        font-size: 16px;
        font-weight: bold;
    }

    #tablasSeleccionadas {
        gap: 10px; /* Ajusta el espacio entre las opciones */
    }
</style>
@endsection