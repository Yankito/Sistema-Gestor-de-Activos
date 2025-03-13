@extends('layouts.app')
<!doctype html>
<html lang="es">
<head>
    <title>Importar Personas</title>
</head>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header" style="background-color: #005856; color: white;">
                            <h3 class="card-title mb-0">Importar Datos de Personas</h3>
                        </div>
                        <div class="card-body">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
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
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <!-- Excel Import Form -->
                            <form id="importForm" action="{{ route('importar.excel.personas') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="archivo_excel" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-block" style="background-color: #00C01E;">
                                    <i class="fas fa-file-import"></i> Importar Datos
                                </button>
                            </form>

                            <!-- Tabs for Imported Data and Errors -->
                            @if ((isset($personas) && count($personas) > 0) || (isset($errores) && count($errores) > 0))
                                <hr class="my-4">
                                <ul class="nav nav-tabs" id="importTabs">
                                    @if (isset($personas) && count($personas) > 0)
                                        <li class="nav-item">
                                            <a class="nav-link active" id="success-tab" data-toggle="tab" href="#success" role="tab" aria-controls="success" aria-selected="true">
                                                <i class="fas fa-check-circle text-success"></i> Datos Importados ({{ count($personas) }})
                                            </a>
                                        </li>
                                    @endif
                                    @if (isset($errores) && count($errores) > 0)
                                        <li class="nav-item">
                                            <a class="nav-link" id="errors-tab" data-toggle="tab" href="#errors" role="tab" aria-controls="errors" aria-selected="false">
                                                <i class="fas fa-exclamation-circle text-danger"></i> Errores ({{ count($errores) }})
                                            </a>
                                        </li>
                                    @endif
                                </ul>

                                <div class="tab-content" id="importTabsContent">
                                    <!-- Success Tab -->
                                    @if (isset($personas) && count($personas) > 0)
                                        <div class="tab-pane fade show active" id="success" role="tabpanel" aria-labelledby="success-tab">
                                            <div class="table-responsive mt-3">
                                                <table id="tablaDatos" class="table table-bordered table-striped table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Rut</th>
                                                            <th>Nombre Completo</th>
                                                            <th>Nombre Empresa</th>
                                                            <th>Estado</th>
                                                            <th>Fecha Ingreso</th>
                                                            <th>Fecha Término</th>
                                                            <th>Cargo</th>
                                                            <th>Ubicación</th>
                                                            <th>Correo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($personas as $persona)
                                                            @if (!empty(array_filter($persona)))
                                                                <tr>
                                                                    <td>{{ $persona['user'] }}</td>
                                                                    <td>{{ $persona['rut'] }}</td>
                                                                    <td>{{ $persona['nombre_completo'] }}</td>
                                                                    <td>{{ $persona['nombre_empresa'] }}</td>
                                                                    <td>{{ $persona['estado_empleado'] }}</td>
                                                                    <td>{{ $persona['fecha_ing'] }}</td>
                                                                    <td>{{ $persona['fecha_ter'] }}</td>
                                                                    <td>{{ $persona['cargo'] }}</td>
                                                                    <td>{{ $persona['ubicacion'] }}</td>
                                                                    <td>{{ $persona['correo'] }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Errors Tab -->
                                    @if (isset($errores) && count($errores) > 0)
                                        <div class="tab-pane fade" id="errors" role="tabpanel" aria-labelledby="errors-tab">
                                            <div class="table-responsive mt-3">
                                                <table id="tablaErrores" class="table table-bordered table-danger table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Rut</th>
                                                            <th>Nombre Completo</th>
                                                            <th>Nombre Empresa</th>
                                                            <th>Estado</th>
                                                            <th>Fecha Ingreso</th>
                                                            <th>Fecha Término</th>
                                                            <th>Cargo</th>
                                                            <th>Ubicación</th>
                                                            <th>Correo</th>
                                                            <th>Motivo del Error</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($errores as $error)
                                                            <tr>
                                                                <td>{{ $error['fila']['A'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['B'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['C'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['D'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['E'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['F'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['G'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['H'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['I'] ?? '-' }}</td>
                                                                <td>{{ $error['fila']['J'] ?? '-' }}</td>
                                                                <td>{{ $error['motivo'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if (isset($errores) && count($errores) > 0)
                                <div class="text-center mt-4">
                                    <a href="{{ route('descargar.erroresPersonas.excel') }}" class="btn btn-danger">
                                        <i class="fas fa-file-excel mr-2"></i> Descargar Errores en Excel
                                    </a>
                                </div>
                            @endif

                            <!-- Download Template Button -->
                            <div class="text-center mt-4">
                                <a href="{{ route('descargarPersonas.excel') }}" class="btn btn-secondary">
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
    <!-- DataTables -->
    <script src="vendor/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="vendor/adminlte/plugins/jszip/jszip.min.js"></script>
    <script src="vendor/adminlte/plugins/pdfmake/pdfmake.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#tablaDatos, #tablaErrores').DataTable({
                responsive: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        });

        $(document).ready(function() {
            $('#importForm').submit(function() {
                // Deshabilita el botón de importar y cambia el texto
                let button = $(this).find('button[type="submit"]');
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importando...');

                // Permite que el formulario continúe con el envío
                return true;
            });
        });
    </script>
@endsection
</html>
