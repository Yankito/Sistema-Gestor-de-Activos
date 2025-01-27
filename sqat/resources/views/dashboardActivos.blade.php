@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

@section('content')
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
            series: [
                {
                    name: 'Regiones',
                    color: '#E0E0E0',
                    enableMouseTracking: true
                },
                {
                    type: 'mappoint',
                    name: 'Ciudades',
                    data: [
                        {
                            name: 'Rosario Norte',
                            x: 0,
                            y: 0,
                            color: '#FF0000'
                        }
                    ],
                    marker: {
                        symbol: 'circle',
                        radius: 8,
                        fillColor: '#FF0000', // Rojo para destacar el punto
                        lineColor: '#000000',
                        lineWidth: 1
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}', // Mostrar el nombre de la ciudad
                        color: '#FFFFFF',
                        style: {
                            textOutline: '1px contrast'
                        }
                    }
                }
            ]
        });
        Highcharts.addEvent(chart, 'click', function (event) {
            // Obtener el objeto del clic
            const point = chart.pointer.normalize(event); // Normalizar el evento

            // Obtener las coordenadas geográficas desde el punto del mapa
            const latLon = chart.pointer.getCoordinates(point);

            // Verificar si las coordenadas existen
            if (latLon) {
                const lat = latLon.lat;
                const lon = latLon.lon;

                // Mostrar las coordenadas en el área designada
                document.getElementById('coords').innerHTML = `Coordenadas: Lat: ${lat.toFixed(4)}, Lon: ${lon.toFixed(4)}`;
            } else {
                // Si no se pueden obtener las coordenadas
                document.getElementById('coords').innerHTML = `Coordenadas no disponibles`;
            }
        });
    });
</script>
@endsection




</html>

