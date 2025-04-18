<!doctype html>
<html lang="es">
    <head>
        <title>Inicio de Sesión</title>
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

        <style>
            .gradient-custom-2 {
                background-color: #005856; /* Color sólido */
            }
        </style>

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

                        <div class="text-center mb-3">
                        <img src="{{asset('pictures/Logo Empresas Iansa.png')}}"
                            style="width: 300px;" alt="logo">

                        </div>
                        <h3 class="text-center mb-4" style="color: #005856;" >Sistema de Gestión de Activos Tecnológicos</h3>
                        <form action="/login" method="POST">
                            @csrf
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="correo">Correo</label>
                                <input input type="email" name="correo" id="correo" required class="form-control"/>
                            </div>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="contrasena">Contraseña</label>
                                <input type="password" name="contrasena" id="contrasena" required class="form-control" />
                            </div>

                            @if(session('error'))
                                <p style="color: red">{{ session('error') }}</p>
                            @endif
                            <div class="text-center pt-1 mb-5 pb-1">
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">
                                    Iniciar Sesión
                                </button>
                            </div>

                        </form>

                    </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                        <h4 class="mb-4" style="font-family: 'Ubuntu', sans-serif;">Alimentamos al mundo con lo mejor de nuestra tierra</h4>
                        <p class="small mb-0" style="font-family: 'Ubuntu', sans-serif;">Promovemos la nutrición con productos que nacen de nuestra tierra, buscando impactar positivamente en las personas, el agro y los animales con un espíritu innovador y sostenible.</p>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </section>
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>


