@extends('layouts.app')
<!doctype html>
<html lang="es">
<head>
    <title>Lista de Ubicaciones</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Lista de Ubicaciones</h2>

        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Sitio</th>
                    <th>Soporte TI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ubicaciones as $ubicacion)
                    <tr>
                        <td>{{ $ubicacion->id }}</td>
                        <td>{{ $ubicacion->sitio }}</td>
                        <td>{{ $ubicacion->soporte_ti }}</td>
                        <td>

                            <a href="{{ route('ubicaciones.modificar', ['id' => $ubicacion->id]) }}" class="btn btn-warning btn-sm">Modificar</a>

                            <form action="{{ route('ubicaciones.eliminar', $ubicacion->hashed_id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn">Eliminar</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="/dashboard" class="btn btn-outline-secondary">Volver atrás</a>
    </div>
@endsection

@section('scripts')

    @if(session('success'))
        <script>
            $(document).ready(function () {
                    toastr.success("{{ session('success') }}");
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            $(document).ready(function () {
                toastr.error("{{ session('error') }}");
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            confirmButton: 'btn-confirm',
                            cancelButton: 'btn-cancel'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('.delete-form').submit();
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .btn-confirm {
            background-color: #005856 !important;
            color: white !important;
            border: none !important;
        }

        .btn-cancel {
            background-color: #ccc !important;
            color: white !important;
            border: none !important;
        }
    </style>
@endsection
</html>
