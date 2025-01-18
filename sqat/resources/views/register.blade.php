@extends('layouts.app')  <!-- Esto extiende el layout app.blade.php -->

@section('content')
    <h2>Registrar nuevo usuario</h2>

    <form action="/register" method="POST">
        @csrf
        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" id="correo" required><br><br>

        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" id="nombres" required><br><br>

        <label for="primerApellido">Primer apellido:</label>
        <input type="text" name="primerApellido" id="primerApellido" required><br><br>

        <label for="segundoApellido">Segundo apellido (opcional):</label>
        <input type="text" name="segundoApellido" id="segundoApellido"><br><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required><br><br>

        <label for="esAdministrador">¿Es administrador?</label>
        <input type="checkbox" name="esAdministrador" id="esAdministrador" value="1"><br><br>

        <button type="submit">Registrar</button>
    </form>
@endsection
