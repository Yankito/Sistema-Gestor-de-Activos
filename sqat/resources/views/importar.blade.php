@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title mb-0">Importar Asignación de Activos</h3>
                        </div>
                        <div class="card-body">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </div>
                            @endif

                            <!-- Error Message -->
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <!-- Excel Import Form -->
                            <form id="importForm" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="archivo_excel" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-file-import"></i> Importar Datos
                                </button>
                            </form>

                            <!-- Imported Data Table -->
                            @if (session('asignaciones') && count(session('asignaciones')) > 0)
                                <hr class="my-4">
                                <h4 class="mb-3">Datos Importados</h4>
                                <div class="table-responsive">
                                    <table id="tablaDatos" class="table table-bordered table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Responsable</th>
                                                <th>Usuario Activo</th>
                                                <th>Número de Serie</th>
                                                <th>Estado</th>
                                                <th>Justificación Doble Activo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('asignaciones') as $asignacion)
                                                <tr>
                                                    <td>{{ $asignacion['responsable'] }}</td>
                                                    <td>{{ $asignacion['usuario_activo'] }}</td>
                                                    <td>{{ $asignacion['numero_serie'] }}</td>
                                                    <td>{{ $asignacion['estado'] }}</td>
                                                    <td>{{ $asignacion['justificacion'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Errors Table -->
                            @if (session('errores') && count(session('errores')) > 0)
                                <hr class="my-4">
                                <h4 class="mb-3 text-danger">❌ Errores en la Importación</h4>
                                <div class="table-responsive">
                                    <table id="tablaErrores" class="table table-bordered table-danger table-hover">
                                        <thead>
                                            <tr>
                                                <th>Motivo del Error</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('errores') as $error)
                                                <tr>
                                                    <td>{{ $error }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Download Template Button -->
                            <div class="text-center mt-4">
                                <a href="{{ route('descargar.excel') }}" class="btn btn-secondary">
                                    <i class="fas fa-file-excel mr-2"></i> Descargar Plantilla Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#tablaDatos, #tablaErrores').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        });
    </script>
@endsection