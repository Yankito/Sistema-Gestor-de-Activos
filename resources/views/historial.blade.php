<!-- resources/views/registros/index.blade.php -->
@extends('layouts.app')
<!doctype html>
<html lang="es">
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Historial de Registros</h3>
                    </div>

                    <div class="card-body">
                        <form method="GET" action="{{ route('historial') }}" id="filterForm">
                            <div class="form-group">
                                <label for="filter_date">Filtrar por día:</label>
                                <input type="date" id="filter_date" name="filter_date" class="form-control" value="{{ request('filter_date') }}">
                            </div>
                        </form>

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Acción</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registros as $registro)
                                    <tr>
                                        <td>
                                             <!-- Mostrar el valor de tipo_cambio para depuración -->
                                            @if ($registro->encargadoCambio)
                                                {{ $registro->encargadoCambio->nombres }} {{ $registro->encargadoCambio->primer_apellido }} <!-- Nombre del encargado del cambio -->
                                            @else
                                                ID desconocido
                                            @endif
                                            @if ($registro->tipo_cambio == 'ASIGNACION')
                                                asignó el activo
                                            @elseif ($registro->tipo_cambio == 'DESVINCULACION')
                                                dio de baja el activo
                                            @elseif (in_array($registro->tipo_cambio, ['ADQUIRIDO', 'PREPARACION', 'DISPONIBLE', 'ASIGNADO', 'PERDIDO', 'ROBADO', 'DEVUELTO', 'PARA_BAJA', 'DONADO', 'VENDIDO']))
                                                cambió el estado del activo a "{{ strtolower($registro->tipo_cambio) }}"
                                            @elseif (in_array($registro->tipo_cambio, ['IMPORTÓ ACTIVOS', 'IMPORTÓ PERSONAS', 'IMPORTÓ ASIGNACIONES']))
                                                {{ $registro->tipo_cambio }}
                                            @else
                                                realizó un cambio en el activo
                                            @endif

                                            @if ($registro->activoRelation)
                                                "{{ $registro->activoRelation->nro_serie }}" <!-- Número de serie del activo -->
                                            @endif

                                            @if($registro->personaRelation)
                                                a {{ $registro->personaRelation->nombre_completo }} <!-- Nombre de la persona involucrada -->
                                            @endif

                                            <!-- Mostrar el estado anterior y el nuevo estado si es un cambio de estado -->
                                            @if ($registro->tipo_cambio == 'CAMBIO_ESTADO')
                                                (Estado anterior: {{ $registro->estado_anterior }}, Nuevo estado: {{ $registro->nuevo_estado }})
                                            @endif
                                        </td>
                                        <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td> <!-- Fecha formateada -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- scripts -->
<script>
    document.getElementById('filter_date').addEventListener('change', function() {
        document.getElementById('filterForm').submit(); // Envía el formulario automáticamente
    });
</script>
@endsection
</html>
