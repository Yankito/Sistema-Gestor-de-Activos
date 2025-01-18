<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación</title>
    <!-- Agrega tus archivos CSS aquí -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <nav>
            <!-- Aquí puedes agregar tu barra de navegación, enlaces, etc. -->
            <ul>
            </ul>
        </nav>
    </header>

    <!-- Mostrar el nombre del usuario logueado si está autenticado -->
    <div>
        @if (Auth::check())
            <p>Bienvenido, {{ Auth::user()->nombres }} {{ Auth::user()->primerApellido }}</p>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        @endif
    </div>

    <!-- Contenido de la página -->
    <div class="content">
        @yield('content')  <!-- Aquí se inserta el contenido de cada página -->
    </div>

    <footer>
        <!-- Puedes agregar un pie de página aquí -->
        <p>&copy; 2025 Hola gente de iansa</p>
    </footer>

    <!-- Agrega tus archivos JS aquí -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
