@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Exportar Datos</h3>

    <label for="tablasSeleccionadas">Selecciona la tabla a exportar:</label>
    <div id="tablasSeleccionadas" class="form-check">
        <div class="option" data-value="activos">
            <i class="fas fa-laptop"></i>
            Tabla de Activos
        </div>
    </div>
    <div class="form-check">
        <div class="option" data-value="personas">
            <i class="fas fa-user"></i>
            Tabla de Personas
        </div>
    </div>
    <div class="form-check">
        <div class="option" data-value="activos_personas">
            <i class="fas fa-users"></i>
            Activos + Personas
        </div>
    </div>

    <div class="mt-3">
        <button id="btnExcel" class="btn btn-success">Exportar a Excel</button>
        <button id="btnCSV" class="btn btn-primary">Exportar a CSV</button>

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
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
        display: inline-block;
    }

    .option.selected {
        background-color: #007bff;
        color: white;
    }

    .option i {
        margin-right: 10px;
    }
</style>
@endsection