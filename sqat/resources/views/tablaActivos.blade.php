<!DOCTYPE html>
<html>
<head>
    <title>Tabla de Activos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Lista de Activos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número de serie</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Precio</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>responsable</th>
                    <th>Docking</th>
                    <th>Parlante jabra</th>
                    <th>HDD externo</th>
                    <th>Impresora exclusiva</th>
                    <th>Monitor</th>
                    <th>Mouse</th>
                    <th>Teclado</th>
                    <th>Justificación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activos as $activo)
                <tr>
                    <td>{{ $activo->nroSerie }}</td>
                    <td>{{ $activo->marca }}</td>
                    <td>{{ $activo->modelo }}</td>
                    <td>{{ $activo->precio }}</td>
                    <td>{{ $activo->tipoActivo }}</td>
                    <td>{{ $activo->estado }}</td>
                    <td>{{ $activo->usuarioDeActivo }}</td>
                    <td>{{ $activo->responsableDeActivo }}</td>
                    <td>{{ $activo->docking }}</td>
                    <td>{{ $activo->parlanteJabra }}</td>
                    <td>{{ $activo->discoDuroExt }}</td>
                    <td>{{ $activo->impresoraExclusiva }}</td>
                    <td>{{ $activo->monitor }}</td>
                    <td>{{ $activo->mouse }}</td>
                    <td>{{ $activo->teclado }}</td>
                    <td>{{ $activo->justificacionDobleActivo }}</td>
                    <td>{{ $activo->precio }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>