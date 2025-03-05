@extends('layouts.app')

@section('content')
<section class = "content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Exportar Datos</h3>
                </div>

                <div class="card-body">
                    <label for="tablasSeleccionadas">Selecciona la tabla a exportar:</label>
                    <div id="tablasSeleccionadas" class="d-flex justify-content-center mt-3">
                        <div class="option" data-value="activos">
                            <i class="fas fa-laptop fa-2x"></i>
                            <div>Tabla de Activos</div>
                            <i class="fas fa-info-circle" style="color: rgba(255, 255, 255, 0.7);"
                                data-toggle="tooltip" data-placement="bottom" title="Reporte de activos con el detalle de sus atributos y con su respectivo responsable y usuarios.">
                            </i>
                        </div>
                        <div class="option" data-value="personas">
                            <i class="fas fa-user fa-2x"></i>
                            <div>Tabla de Personas</div>
                            <i class="fas fa-info-circle" style="color: rgba(255, 255, 255, 0.7);"
                                data-toggle="tooltip" data-placement="bottom" title="Reporte de personas con sus respectivos datos personales.">
                            </i>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button id="btnExcel" class="btn mx-2" style="background-color: #36cc36; color: white;">Exportar a Excel</button>
                        <button id="btnCSV" class="btn mx-2" style="background-color: #34848c; color: white;">Exportar a CSV</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        let selectedTable = null;

        $('.option').click(function () {
            $('.option').removeClass('selected');
            $(this).addClass('selected');
            selectedTable = $(this).data('value');
            $('#btnExcel, #btnCSV').prop('disabled', false); // Habilitar botones
        });

        $('#btnExcel, #btnCSV').click(function () {
            if (!selectedTable) {
                alert('Por favor, selecciona una tabla para exportar.');
                return;
            }

            const formato = $(this).attr('id') === 'btnExcel' ? 'excel' : 'csv';
            exportarTabla(selectedTable, formato);
        });

        function exportarTabla(tabla, formato) {
            // Mostrar ícono de carga
            $('#btnExcel, #btnCSV').html('<i class="fas fa-spinner fa-spin"></i> Exportando...').prop('disabled', true);

            // Simular una solicitud al servidor
            setTimeout(() => {
                window.location.href = `/exportar/${tabla}/${formato}`;
                // Restaurar botones después de la exportación
                $('#btnExcel').html('Exportar a Excel').prop('disabled', false);
                $('#btnCSV').html('Exportar a CSV').prop('disabled', false);
            }, 1000); // Simula un retraso de 1 segundo
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        $('[data-toggle="tooltip"]').tooltip(); // Inicializa los tooltips de Bootstrap
    });
</script>

<style>
    .option {
        cursor: pointer;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin: 5px;
        text-align: center;
        width: 150px;
        transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .option:hover {
        background-color: #34848c !important;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .option.selected {
        background-color: #34848c !important;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .option i {
        margin-bottom: 10px;
        font-size: 24px;
    }

    .option div {
        font-size: 16px;
        font-weight: bold;
    }

    #tablasSeleccionadas {
        gap: 15px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }
</style>
@endsection
