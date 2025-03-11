@extends('layouts.app')

@section('content')
<section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
                <div class="card rounded-3 text-black">
                    <div class="card-body p-md-5 mx-md-4">
                        <div class="text-center">
                            <img src="{{ asset('pictures/Logo Empresas Iansa.png') }}" style="width: 300px;" alt="logo">
                        </div>

                        <h2>Registrar nuevo usuario</h2>

                        <form action="/register" method="POST" id="formRegistro">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-outline mb-2">
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
                                <div class="col-md-6">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="nombres">Nombres:</label>
                                        <input type="text" name="nombres" id="nombres" required class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="primer_apellido">Primer apellido:</label>
                                        <input type="text" name="primer_apellido" id="primer_apellido" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="segundo_apellido">Segundo apellido:</label>
                                        <input type="text" name="segundo_apellido" id="segundo_apellido" required class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="contrasena">Contraseña:</label>
                                        <input type="password" name="contrasena" id="contrasena" required minlength="6" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline mb-2">
                                        <label class="form-label" for="es_administrador">¿Es administrador?</label>
                                        <input type="checkbox" name="es_administrador" id="es_administrador" value="1">
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit" id="botonRegistrar">Registrar</button>
                        </form>
                        <a href="/dashboard" type="button" class="btn btn-outline-danger">Volver atrás</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
