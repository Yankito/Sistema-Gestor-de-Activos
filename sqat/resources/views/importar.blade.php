@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Importar Archivo Excel</h3>
                        </div>
                        <div class="card-body">
                            <!-- Mensaje de éxito -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Formulario para cargar archivo Excel -->
                            <form action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="archivo_excel" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success">Importar Datos</button>
                            </form>

                            <!-- Mostrar datos importados antes de confirmar -->
                            @if (isset($data) && count($data) > 0)
                                <hr>
                                <h4>Datos Importados</h4>
                                <form action="{{ route('confirmar.importacion') }}" method="POST">
                                    @csrf
                                    <table id="tablaDatos" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>RUT</th>
                                                <th>Nombre Usuario</th>
                                                <th>Nombres</th>
                                                <th>Primer Apellido</th>
                                                <th>Segundo Apellido</th>
                                                <th>Supervisor</th>
                                                <th>Empresa</th>
                                                <th>Estado Empleado</th>
                                                <th>Centro Costo</th>
                                                <th>Denominación</th>
                                                <th>Título Puesto</th>
                                                <th>Fecha Inicio</th>
                                                <th>Usuario TI</th>
                                                <th>Nro Serie</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Estado</th>
                                                <th>Responsable Activo</th>
                                                <th>Precio</th>
                                                <th>Ubicación</th>
                                                <th>Justificación Doble Activo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    @foreach ($row as $cell)
                                                        <td>{{ $cell }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button id="importButton" class="btn btn-primary mt-3" type="submit">
                                        Confirmar Importación
                                    </button>
                                </form>
                            @endif

                            <!-- Botón para descargar el archivo de muestra -->
                            <a href="{{ route('descargar.excel') }}" class="btn btn-secondary mt-3">
                                Descargar Excel de Muestra
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
    </script>
@endsection
