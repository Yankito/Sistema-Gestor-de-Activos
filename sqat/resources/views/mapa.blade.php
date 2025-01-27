<div class="card bg-gradient-primary">
    <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
        <h3 class="card-title">
            <i class="fas fa-map-marker-alt mr-1"></i>
            Mapa de Chile
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                <i class="far fa-calendar-alt"></i>
            </button>
            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div id="chile-map" style="height: 450px; width: 100%;"></div>
    </div>

    <div class="card-footer bg-transparent">
        <p class="text-white text-center">Mapa interactivo de Chile</p>
        <div id="coords" class="text-white text-center"></div> <!-- Mostrar coordenadas -->
    </div>
</div>

<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/export-data.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/cl/cl-all.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Array generado dinámicamente desde PHP
        const ubicaciones = @json($ubicaciones);
        const cantidadPorUbicacion = @json($cantidadPorUbicacion);

        // Transformar datos para Highcharts
        const puntosUbicaciones = ubicaciones.map(ubicacion => ({
            name: ubicacion.sitio,
            lat: parseFloat(ubicacion.latitud),
            lon: parseFloat(ubicacion.longitud),
            activos: cantidadPorUbicacion[ubicacion.sitio] || 0,
            color: '#FF0000'
        }));

        // Configuración del mapa
        const chart = Highcharts.mapChart('chile-map', {
            chart: {
                map: 'countries/cl/cl-all', // Mapa de Chile
                panning: {
                    enabled: true
                },
                panKey: 'shift' // Habilitar arrastre con Shift
            },
            title: {
                text: 'Mapa de Chile'
            },
            mapNavigation: {
                enabled: true,
                enableMouseWheelZoom: true // Permitir zoom con el scroll
            },
            tooltip: {
                headerFormat: '<b>{point.key}</b><br>',
                pointFormat: 'Activos: {point.activos}' // Mostrar cantidad de activos en el tooltip
            },
            series: [
                {
                    name: 'Regiones',
                    color: '#E0E0E0',
                    enableMouseTracking: true
                },
                {
                    type: 'mappoint',
                    name: 'Ubicaciones',
                    data: puntosUbicaciones, // Usar los puntos generados
                    marker: {
                        symbol: 'circle',
                        radius: 8,
                        fillColor: '#FF0000', // Rojo para destacar el punto
                        lineColor: '#000000',
                        lineWidth: 1
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}', // Mostrar solo el nombre del lugar en la etiqueta
                        color: '#FFFFFF',
                        style: {
                            textOutline: '1px contrast'
                        }
                    }
                }
            ]
        });
    });
</script>
