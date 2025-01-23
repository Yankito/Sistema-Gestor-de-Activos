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
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link href="{{asset('assets/estiloLogin.css')}}" rel="stylesheet">
    </head>

    @section('content')
        <section class="h-100 gradient-form" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="card-body p-md-5 mx-md-4">
                                        <div class="text-center">
                                            <img src="{{asset('pictures/Logo Empresas Iansa.png')}}"
                                                style="width: 300px;" alt="logo">
                                        </div>

                                        <h2>Registrar nuevo activo</h2>

                                        <form action="/activos" method="POST">
                                            @csrf

                                            <!-- Nro Serie -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="nroSerie">Nro. Serie</label>
                                                <input type="text" name="nroSerie" id="nroSerie" required class="form-control" />
                                            </div>

                                            <!-- Marca -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="marca">Marca</label>
                                                <input type="text" name="marca" id="marca" required class="form-control" />
                                            </div>

                                            <!-- Modelo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="modelo">Modelo</label>
                                                <input type="text" name="modelo" id="modelo" required class="form-control" />
                                            </div>

                                            <!-- Tipo de Activo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="tipoActivo">Tipo de Activo</label>
                                                <select name="tipoActivo" id="tipoActivo" class="form-control" required>
                                                    <option value="LAPTOP">Laptop</option>
                                                    <option value="DESKTOP">Desktop</option>
                                                    <option value="MONITOR">Monitor</option>
                                                    <option value="IMPRESORA">Impresora</option>
                                                    <option value="CELULAR">Celular</option>
                                                    <option value="OTRO">Otro</option>
                                                </select>
                                            </div>

                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="accesorios">Seleccione los accesorios que tiene:</label>
                                                <select name="accesorios[]" id="accesorios" class="form-control" multiple>
                                                    <option value="docking">Docking</option>
                                                    <option value="parlanteJabra">Parlante Jabra</option>
                                                    <option value="discoDuroExte">Disco Duro Externo</option>
                                                    <option value="impresoraExclusiva">Impresora Exclusiva</option>
                                                    <option value="monitor">Monitor</option>
                                                    <option value="mouse">Mouse</option>
                                                    <option value="teclado">Teclado</option>
                                                </select>
                                            </div>

                                            <!-- Justificación Doble Activo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="justificacionDobleActivo">Justificación de Doble Activo (opcional)</label>
                                                <textarea name="justificacionDobleActivo" id="justificacionDobleActivo" class="form-control"></textarea>
                                            </div>

                                            <!-- Precio -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="precio">Precio</label>
                                                <input type="number" name="precio" id="precio" required class="form-control" />
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
                </div>
            </div>
        </section>
    @endsection
</html>
