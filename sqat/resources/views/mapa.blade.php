<div class="card bg-gradient-primary">
    <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
        <h3 class="card-title">
            <i class="fas fa-map-marker-alt mr-1"></i>
            Mapa de activos por ubicación
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div id="chile-map" style="height: 450px; width: 100%;"></div>
    </div>

</div>

<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/export-data.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/cl/cl-all.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Array generado dinámicamente desde PHP
        const ubicaciones = JSON.parse('{!! json_encode($ubicaciones) !!}');
        const cantidadPorUbicacion = JSON.parse('{!! json_encode($cantidadPorUbicacion) !!}');

        // Transformar datos para Highcharts
        const puntosUbicaciones = ubicaciones.map(ubicacion => ({
            name: ubicacion.sitio,
            lat: parseFloat(ubicacion.latitud),
            lon: parseFloat(ubicacion.longitud),
            activos: cantidadPorUbicacion[ubicacion.sitio] || 0,
            color: '#FF0000'
        }));

        // Calcular automáticamente el centro y zoom en función de las ubicaciones
        const latitudes = puntosUbicaciones.map(punto => punto.lat);
        const longitudes = puntosUbicaciones.map(punto => punto.lon);
        const minLat = Math.min(...latitudes);
        const maxLat = Math.max(...latitudes);
        const minLon = Math.min(...longitudes);
        const maxLon = Math.max(...longitudes);

        const centerLat = (minLat + maxLat) / 2;
        const centerLon = (minLon + maxLon) / 2;

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
                enableMouseWheelZoom: true, // Permitir zoom con el scroll
                    buttons: {
                    zoomIn: {
                        onclick: function () {
                            this.mapView.zoomBy(1); // Zoom in
                            console.log('Zoom In: Nivel actual de zoom:', this.mapView.zoom);
                        }
                    },
                    zoomOut: {
                        onclick: function () {
                            this.mapView.zoomBy(-1); // Zoom out
                            console.log('Zoom Out: Nivel actual de zoom:', this.mapView.zoom);
                        }
                    }
                }
            },
            mapView: {
                // Definir las coordenadas iniciales del centro del mapa y el zoom
                center: {
                    x: centerLon,
                    y: centerLat
                },
                zoom: -2

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
                        fillColor: '#0aa40d', // Rojo para destacar el punto
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
        console.log('Centro calculado:', { centerLat, centerLon });
        chart.mapView.addEventListener('afterSetExtremes', function () {
        console.log('Nivel actual de zoom:', this.zoom);
    });

    });
</script>
