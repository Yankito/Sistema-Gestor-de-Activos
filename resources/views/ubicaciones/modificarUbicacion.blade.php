@extends('layouts.app')
<!doctype html>
<html lang="es">
<head>
    <title>Modificar Ubicación</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

@section('content')
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="card-body p-md-5 mx-md-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('pictures/Logo Empresas Iansa.png') }}" style="width: 300px;" alt="logo">
                            </div>

                            <h2>Modificar Ubicación</h2>
                            <form action="/ubicacionesUpdate" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $ubicacion->id }}" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="sitio">Nombre Sitio</label>
                                            <input type="text" name="sitio" id="sitio" required class="form-control"
                                                value="{{ $ubicacion->sitio }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="soporte_ti">Soporte TI</label>
                                            <input type="text" name="soporte_ti" id="soporte_ti" class="form-control"
                                                value="{{ $ubicacion->soporte_ti }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="direccion">Dirección</label>
                                        <input type="text" id="direccion" class="form-control" placeholder="Ingrese la dirección"
                                            value="{{ $ubicacion->direccion }}" />

                                        <label class="form-label mt-2" for="ciudad">Ciudad</label>
                                        <input type="text" id="ciudad" class="form-control" placeholder="Ingrese la ciudad"
                                            value="{{ $ubicacion->ciudad }}" />

                                        <button type="button" id="buscarDireccion" class="btn btn-info mt-2">Buscar Ubicación</button>
                                    </div>

                                    <!-- Map Container -->
                                    <div class="col-md-12 mt-3">
                                        <label class="form-label" for="latitud">Seleccione la Ubicación</label>
                                        <div id="map" style="height: 400px; margin-bottom: 15px;"></div>
                                        <input type="hidden" id="latitud" name="latitud" value="{{ $ubicacion->latitud }}" />
                                        <input type="hidden" id="longitud" name="longitud" value="{{ $ubicacion->longitud }}" />
                                    </div>
                                </div>

                                <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3 text-center" type="submit">Actualizar Ubicación</button>
                            </form>

                            <a href="/dashboard" type="button" class="btn btn-outline-danger text-center">Volver atrás</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lat = {{ $ubicacion->latitud ?? -35.4198601 }};
            var lng = {{ $ubicacion->longitud ?? -71.6739799 }};

            var map = L.map('map').setView([lat, lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            function updateMarkerInputs(lat, lng) {
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;
            }

            marker.on('moveend', function (e) {
                updateMarkerInputs(e.target.getLatLng().lat, e.target.getLatLng().lng);
            });

            document.getElementById('buscarDireccion').addEventListener('click', function () {
                var direccion = document.getElementById('direccion').value;
                var ciudad = document.getElementById('ciudad').value;

                if (!direccion || !ciudad) {
                    Swal.fire('Error', 'Ingrese una dirección y ciudad válidas', 'warning');
                    return;
                }

                var url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion + ', ' + ciudad)}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var lat = parseFloat(data[0].lat);
                            var lng = parseFloat(data[0].lon);

                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 14);
                            updateMarkerInputs(lat, lng);

                            Swal.fire('Ubicación encontrada', `Latitud: ${lat}, Longitud: ${lng}`, 'success');
                        } else {
                            Swal.fire('Error', 'No se encontró la ubicación', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la búsqueda:', error);
                        Swal.fire('Error', 'No se pudo conectar con el servicio', 'error');
                    });
            });
        });
    </script>

    <style>
        form .form-label {
            font-size: 15px;
            color: #4b4b4b;
        }

        #map {
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection
</html>
