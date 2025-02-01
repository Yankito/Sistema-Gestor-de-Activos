@extends('layouts.app')
<!doctype html>
<html lang="es">
    <head>
        <title>Registrar Ubicación</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

                <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />



    </head>

    @section('content')
        <section class="h-100 gradient-form" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                        <!-- Aquí va el contenido de la página centrado-->
                            <div class = "card-body p-md-5 mx-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{asset('pictures/Logo Empresas Iansa.png')}}" style="width: 300px;" alt="logo">
                                </div>

                                <h2>Registrar nueva Ubicación</h2>
                                <form action="/ubicaciones" method="POST">
                                    @csrf
                                    <div class = "row">

                                        <div class="col-md-6">
                                            <!-- Primer Apellido -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="sitio">Nombre Sitio</label>
                                                <input type="text" name="sitio" id="sitio" required class="form-control" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="soporteTI">Soporte TI</label>
                                                <input type="text" name="soporteTI" id="soporteTI" class="form-control" />
                                            </div>
                                        </div>

                                        <!-- Map Container -->
                                        <div class="col-md-12">
                                            <label class="form-label">Seleccione la Ubicación</label>
                                            <div id="map" style="height: 400px; margin-bottom: 15px;"></div>
                                            <input type="hidden" id="latitud" name="latitud" />
                                            <input type="hidden" id="longitud" name="longitud" />
                                        </div>

                                    </div>
                                    <!-- Botón de Enviar -->
                                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3 text-center" type="submit">Registrar Ubicación</button>
                                </form>
                                <!-- Botón de Volver atrás -->
                                <a href="/dashboard" type="button" class="btn btn-outline-danger text-center">Volver atrás</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize the map
                var map = L.map('map').setView([-35.4198601, -71.6739799], 14); // Default location and zoom level
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                }).addTo(map);

                // Add a marker
                var marker = L.marker([-35.4198601, -71.6739799], { draggable: true }).addTo(map);

                // Update hidden inputs when marker is moved
                function updateMarkerInputs(lat, lng) {
                    document.getElementById('latitud').value = lat;
                    document.getElementById('longitud').value = lng;
                }

                marker.on('moveend', function (e) {
                    var lat = e.target.getLatLng().lat;
                    var lng = e.target.getLatLng().lng;
                    updateMarkerInputs(lat, lng);
                });

                // Initialize with marker's default location
                updateMarkerInputs(-35.4198601, -71.6739799);

                console.log('Mapa inicializado');
                //latitud y longitud obtenidos del marker
                var latitud = document.getElementById('latitud').value;
                var longitud = document.getElementById('longitud').value;
                console.log(latitud);
                console.log(longitud);
            });
        </script>
        <!-- Estilos -->
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
