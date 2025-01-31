@extends('layouts.app')
<!doctype html>
<html lang="es">
    <head>
        <title>Title</title>
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
                        <div class="card-body p-md-5 mx-md-4">

                            <div class="text-center">
                            <img src="{{asset('pictures/Logo Empresas Iansa.png')}}"
                                style="width: 300px;" alt="logo">
                            </div>

                            <h2>Registrar nuevo usuario</h2>

                            <form action="/register" method="POST" id = "formRegistro">
                                @csrf
                                <div class = "row">
                                    <div class = "col-md-6">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="correo">Correo electrónico</label>
                                            <input type="email" name="correo" id="correo" required class="form-control"/>
                                            <span id="correoRepetido" class="text-danger" style="display:none;">El correo ya se encuentra registrado.</span>
                                            @if($errors->has('correo'))
                                                <div class="alert alert-danger">
                                                    <p>{{ $errors->first('correo') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="nombres">Nombres:</label>
                                            <input type="text" name="nombres" id="nombres" required class="form-control"><br><br>
                                        </div>
                                    </div>
                                </div>

                                <div class = "row">
                                    <div class = "col-md-6">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="primerApellido">Primer apellido:</label>
                                            <input type="text" name="primerApellido" id="primerApellido" required class="form-control"><br><br>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="segundoApellido">Segundo apellido (opcional):</label>
                                            <input type="text" name="segundoApellido" id="segundoApellido" class="form-control"><br><br>
                                        </div>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class = "col-md-6">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="contrasena">Contraseña:</label>
                                            <input type="password" name="contrasena" id="contrasena" required class="form-control"><br><br>
                                        </div>
                                    </div>
                                    <div class = "col-md-6">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="esAdministrador">¿Es administrador?</label>
                                            <input type="checkbox" name="esAdministrador" id="esAdministrador" value="1"><br><br>
                                        </div>
                                    </div>
                                </div>
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit" id="botonRegistrar">Registrar</button>
                            </form>
                            <a href="/dashboard" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-danger">Volver atrás</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let correoInput = document.getElementById("correo");
            let mensajeError = document.createElement("div");
            mensajeError.className = "text-danger";
            correoInput.parentNode.appendChild(mensajeError);

            correoInput.addEventListener("input", function () {
                let correo = correoInput.value;
                let regex = /^[a-zA-Z0-9._%+-]+@iansa\.cl$/;

                if (!regex.test(correo) && correo.length > 0) {
                    mensajeError.textContent = "Solo se pueden registrar correos con dominio @iansa.cl";
                } else {
                    mensajeError.textContent = "";
                }
            });
        });

        async function comprobarCorreoRepetido(correo) {
            // Comprobar si el correo ya existe en la base de datos
            try {
                let response = await fetch('/register/' + correo);
                if (response.ok) {
                    let data = await response.json();
                    return data.exists;
                } else {
                    throw new Error('Error al comprobar el correo');
                }
            } catch (error) {
                console.error('Error:', error);
                return false;
            }
        }

        document.getElementById('correo').addEventListener('blur', async function() {
            var correo = this.value;
            var repetidoSpan = document.getElementById('correoRepetido');
            if(await comprobarCorreoRepetido(correo) ) {
                repetidoSpan.style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                repetidoSpan.style.display = 'none';
                this.classList.remove('is-invalid');
            }

        });

        document.getElementById('formRegistro').addEventListener('submit', async function(event) {
            event.preventDefault();
            var correo = document.getElementById('correo').value;
            var repetidoSpan = document.getElementById('correoRepetido');

            if(await comprobarCorreoRepetido(correo)) {
                console.log('Correo repetido');
                repetidoSpan.style.display = 'block';
                document.getElementById('correo').classList.add('is-invalid');
                document.getElementById('correo').focus();
            }
            else{
                this.submit();
            }

        });



    </script>

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

    <style>
        .is-invalid {
            border: 1px solid red;
            background-color: #ffe6e6;
        }
        form .form-label {
            font-size: 15px;
            color: #4b4b4b;
        }
    </style>

    @endsection
</html>
