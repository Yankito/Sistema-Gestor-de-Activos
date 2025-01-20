@extends('layouts.app')  <!-- Esto extiende el layout app.blade.php -->
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

    <body>
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

                                            <!-- Estado -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="estado">Estado</label>
                                                <select name="estado" id="estado" class="form-control" required>
                                                    <option value="ASIGNADO">Asignado</option>
                                                    <option value="DISPONIBLE">Disponible</option>
                                                    <option value="ROBADO">Robado</option>
                                                    <option value="PARA BAJA">Para Baja</option>
                                                    <option value="DONADO">Donado</option>
                                                </select>
                                            </div>

                                            <!-- Usuario de Activo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="usuarioDeActivo">Usuario de Activo</label>
                                                <select name="usuarioDeActivo" id="usuarioDeActivo" class="form-control" required>
                                                    <!personas del modelo Persona>
                                                    @foreach($personas as $persona)
                                                        <option value="{{$persona->id}}">{{$persona->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Responsable de Activo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="responsableDeActivo">Responsable de Activo</label>
                                                <input type="text" name="responsableDeActivo" id="responsableDeActivo" class="form-control" />
                                            </div>

                                            <!-- Docking -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="docking">¿Tiene Docking?</label>
                                                <input type="checkbox" name="docking" id="docking" value="1" class="form-check-input" />
                                            </div>

                                            <!-- Parlante Jabra -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="parlanteJabra">¿Tiene Parlante Jabra?</label>
                                                <input type="checkbox" name="parlanteJabra" id="parlanteJabra" value="1" class="form-check-input" />
                                            </div>

                                            <!-- Disco Duro Externo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="discoDuroExt">¿Tiene Disco Duro Externo?</label>
                                                <input type="checkbox" name="discoDuroExt" id="discoDuroExt" value="1" class="form-check-input" />
                                            </div>

                                            <!-- Impresora Exclusiva -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="impresoraExclusiva">¿Tiene Impresora Exclusiva?</label>
                                                <input type="checkbox" name="impresoraExclusiva" id="impresoraExclusiva" value="1" class="form-check-input" />
                                            </div>

                                            <!-- Monitor -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="monitor">¿Tiene Monitor?</label>
                                                <input type="checkbox" name="monitor" id="monitor" value="1" class="form-check-input" />
                                            </div>

                                            <!-- Mouse -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="mouse">¿Tiene Mouse?</label>
                                                <input type="checkbox" name="mouse" id="mouse" value="1" class="form-check-input" />
                                            </div>

                                            <!-- Teclado -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" for="teclado">¿Tiene Teclado?</label>
                                                <input type="checkbox" name="teclado" id="teclado" value="1" class="form-check-input" />
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
    </body>
</html>
