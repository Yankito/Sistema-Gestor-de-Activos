<!doctype html>
<html lang="en">
    <head>
        <title>Registrar Persona</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
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
                                            <img src="{{asset('pictures/Logo Empresas Iansa.png')}}" style="width: 300px;" alt="logo">
                                        </div>

                                        <h2>Registrar nueva Persona</h2>

                                        <form action="/persona" method="POST">
                                            @csrf

                                            <!-- RUT -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="rut">RUT</label>
                                                <input type="text" name="rut" id="rut" required class="form-control" />
                                            </div>

                                            <!-- Nombre de Usuario -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="nombreUsuario">Nombre de Usuario</label>
                                                <input type="text" name="nombreUsuario" id="nombreUsuario" required class="form-control" />
                                            </div>

                                            <!-- Nombres -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="nombres">Nombres</label>
                                                <input type="text" name="nombres" id="nombres" required class="form-control" />
                                            </div>

                                            <!-- Primer Apellido -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="primerApellido">Primer Apellido</label>
                                                <input type="text" name="primerApellido" id="primerApellido" required class="form-control" />
                                            </div>

                                            <!-- Segundo Apellido -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="segundoApellido">Segundo Apellido</label>
                                                <input type="text" name="segundoApellido" id="segundoApellido" class="form-control" />
                                            </div>

                                            <!-- Supervisor -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="supervisor">Supervisor</label>
                                                <input type="text" name="supervisor" id="supervisor" class="form-control" />
                                            </div>

                                            <!-- Empresa -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="empresa">Empresa</label>
                                                <input type="text" name="empresa" id="empresa" required class="form-control" />
                                            </div>

                                            <!-- Centro Costo -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="centroCosto">Centro Costo</label>
                                                <input type="text" name="centroCosto" id="centroCosto" class="form-control" />
                                            </div>

                                            <!-- Denominación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="denominacion">Denominación</label>
                                                <input type="text" name="denominacion" id="denominacion" class="form-control" />
                                            </div>

                                            <!-- Título Puesto -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="tituloPuesto">Título Puesto</label>
                                                <input type="text" name="tituloPuesto" id="tituloPuesto" class="form-control" />
                                            </div>

                                            <!-- Fecha Inicio -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="fechaInicio">Fecha Inicio</label>
                                                <input type="date" name="fechaInicio" id="fechaInicio" required class="form-control" />
                                            </div>

                                            <!-- Usuario TI -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="usuarioTI">Usuario TI</label>
                                                <select name="usuarioTI" id="usuarioTI" class="form-control" required>
                                                    <option value="1">SI</option>
                                                    <option value="0">NO</option>
                                                </select>
                                            </div>

                                            <!-- Ubicación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="ubicacion">Ubicación</label>
                                                <select name="ubicacion" id="ubicacion" class="form-control" required>
                                                    <!-- Aquí debes cargar las ubicaciones disponibles -->
                                                    @foreach($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}} - {{$ubicacion->soporteTI}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Activo -->
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label class="form-label" >Activo</label>
                                                <select name="activo" id=activo class="form-control" required>
                                                    @foreach($activos as $activo)
                                                        @if ($activo->estado == 'DISPONIBLE')
                                                            <option value="{{$activo->nroSerie}}">{{$activo->nroSerie}} - {{$activo->modelo}} - {{$activo->marca}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>


                                            <!-- Botón de Enviar -->
                                            <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Registrar Persona</button>
                                        </form>

                                        <a href="/dashboard" type="button" class="btn btn-outline-danger">Volver atrás</a>
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
