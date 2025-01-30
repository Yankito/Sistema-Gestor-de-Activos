@extends('layouts.app')
<!doctype html>
<html lang="en">
    <head>
        <title>Registrar Activo</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->

        <link href="{{asset('assets/estiloLogin.css')}}" rel="stylesheet">

    </head>

    @section('content')
        <section class="h-100 gradient-form" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center mb-4">
                                    <img src="{{asset('pictures/Logo Empresas Iansa.png')}}"
                                        style="width: 300px;" alt="logo">
                                </div>

                                <h2>Registrar nuevo activo</h2>

                                <form action="/activos" method="POST">
                                    @csrf
                                    <div class = "row">
                                        <div class = " col-md-6">
                                            <!-- Nro Serie -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="nroSerie">Nro. Serie</label>
                                                <input type="text" name="nroSerie" id="nroSerie" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = " col-md-6">
                                            <!-- Marca -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="marca">Marca</label>
                                                <input type="text" name="marca" id="marca" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = " col-md-6">
                                            <!-- Modelo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="modelo">Modelo</label>
                                                <select name="modelo" id="modelo" class="form-control" required>
                                                    <option value="LAPTOP">Laptop</option>
                                                    <option value="DESKTOP">Desktop</option>
                                                    <option value="MONITOR">Monitor</option>
                                                    <option value="IMPRESORA">Impresora</option>
                                                    <option value="CELULAR">Celular</option>
                                                    <option value="OTRO">Otro</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class = " col-md-6">
                                            <!-- Precio -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="precio">Precio</label>
                                                <input type="number" name="precio" id="precio" required class="form-control" />
                                            </div>
                                        </div>

                                        <div class = " col-md-6">
                                            <!-- Ubicación -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="ubicacion">Ubicación</label>
                                                <select name="ubicacion" id="ubicacion" class="form-control" required>
                                                    @foreach($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Botón de Enviar -->
                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Registrar Activo</button>
                                </form>

                                <a href="/dashboard" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-danger">Volver atrás</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


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
    <!-- Estilos -->
    <style>
        form .form-label {
            font-size: 15px;
            color: #4b4b4b;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>


</html>
