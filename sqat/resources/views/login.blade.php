@extends('layouts.app')  <!-- Esto extiende el layout app.blade.php -->

@section('content')
    <h2>Iniciar sesión</h2>

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <form action="/login" method="POST">
        @csrf
        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" id="correo" required><br><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>
@endsection
