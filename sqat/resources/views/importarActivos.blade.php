@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Importar Datos de Activos</h3>
                        </div>
                        <div class="card-body" style="overflow-x: auto;">
                            <!-- Mensaje de éxito -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <!-- Pantalla de carga -->
                            <div class="overlay" id="loadingOverlay" style="display: none;">
                                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                <div class="text-bold pt-2">Cargando...</div>
                            </div>

                            <!-- Formulario para cargar archivo Excel -->
                            <form id="importForm" action="{{ route('importar.excel.activos') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="archivo_excel" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-import"></i> Importar Datos
                                </button>
                            </form>
                            <!-- Mostrar datos importados antes de confirmar -->
                            @if (isset($datos) && count($datos) > 0)
                                <hr>
                                <h4>Datos Importados</h4>
                                <table id="tablaDatos" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $index => $row)
                                            @if ($index == 0) {{-- Ignora la primera fila (encabezados) --}}
                                                $index++;
                                                @continue
                                            @endif
                                            @php
                                                $row = array_filter($row); // Elimina columnas vacías
                                            @endphp

                                            @if (!empty($row)) {{-- Solo imprime filas que tengan al menos una columna con contenido --}}
                                                <tr>
                                                    @foreach ($row as $cell)
                                                        <td>{{ $cell ?? '-' }}</td> {{-- Evita celdas vacías --}}
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if (isset($errores) && count($errores) > 0)
                                <h4>❌ Errores en la Importación</h4>
                                <table id="tablaErrores" class="table table-bordered table-danger">
                                    <thead>
                                        <tr>
                                            <th>Nro Serie</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Tipo de Activo</th>
                                            <th>Precio</th>
                                            <th>Ubicación</th>
                                            <th>Motivo del Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($errores as $error)
                                            <tr>
                                                @foreach ($error['fila'] as $cell)
                                                    <td>{{ $cell ?? '-' }}</td>
                                                @endforeach
                                                <td>{{ $error['motivo'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            <!-- Botón para descargar el archivo de muestra -->
                            <a href="{{ route('descargarActivos.excel')}}" class="btn btn-secondary mt-3">
                                <i class="fas fa-file-excel"></i> Descargar plantilla excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
    </section>
@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Inicializar DataTables -->
    <script>
        $(document).ready(function () {
            $("#tablaDatos").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                scrollX: true,
                buttons: ["copy", "csv", "excel", "print"]
            }).buttons().container().appendTo('#tablaDatos_wrapper .col-md-6:eq(0)');
        });
        
        // Mostrar pantalla de carga al enviar el formulario
        $('#importForm').submit(function() {
            $('#loadingOverlay').show();
        });
    </script>
@endsection
