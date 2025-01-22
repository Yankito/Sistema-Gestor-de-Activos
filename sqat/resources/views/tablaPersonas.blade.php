<!DOCTYPE html>
<html>
<head>
    <title>Lista de Personas</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Personas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personas as $persona)
                <tr>
                    <td>{{ $persona->rut }}</td>
                    <td>{{ $persona->nombres }}</td>
                    <td>{{ $persona->primerApellido }}</td>
                    <td>
                        <a href="{{ route('dashboard', $persona->id) }}" class="btn btn-primary">Editar</a>
                        <form  method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

