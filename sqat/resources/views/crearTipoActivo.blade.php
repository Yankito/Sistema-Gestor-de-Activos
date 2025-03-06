@extends('layouts.app')

@section('content')
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="card-body p-md-5 mx-md-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('pictures/Logo Empresas Iansa.png') }}" style="width: 300px;" alt="logo">
                            </div>
                            <h2>Registrar Tipo de Activo</h2>
                            @if(session('success'))
                                <div id="success-message" style="background: #00b000; color: white; padding: 10px; margin-bottom: 10px;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div id="error-message" style="background: #ffaa00; color: white; padding: 10px; margin-bottom: 10px;">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form action="/tipos-activo" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="nombre">Nombre del Tipo</label>
                                            <input type="text" name="nombre" id="nombre" required class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-block mb-3" type="submit" style="background: #005856;">Registrar Tipo</button>
                            </form>

                            <!-- Buscador -->
                            <div class="row d-flex justify-content-center mt-5">
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title text-center">Lista de Tipos de Activo</h4>
                                            
                                            <!-- Input de búsqueda -->
                                            <div class="mb-3">
                                                <input type="text" id="searchInput" class="form-control" placeholder="Buscar tipo de activo...">
                                            </div>

                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tipoActivoTable">
                                                    @foreach($tiposActivo as $tipo)
                                                        <tr>
                                                            <td>{{ $tipo->nombre }}</td>
                                                            <td>
                                                                <form action="{{ route('tipos-activo.destroy', $tipo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tipo de activo?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            @if($tiposActivo->isEmpty())
                                                <p class="text-center">No hay tipos de activos registrados.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Script de búsqueda -->
                            <script>
                                document.getElementById("searchInput").addEventListener("keyup", function() {
                                    let filter = this.value.toLowerCase();
                                    let rows = document.querySelectorAll("#tipoActivoTable tr");

                                    rows.forEach(row => {
                                        let text = row.textContent.toLowerCase();
                                        row.style.display = text.includes(filter) ? "" : "none";
                                    });
                                });

                                // Ocultar el mensaje de éxito después de 3 segundos
                                const successMessage = document.getElementById('success-message');
                                if (successMessage) {
                                    setTimeout(() => {
                                        successMessage.style.display = 'none';
                                    }, 3000); // 3000 milisegundos = 3 segundos
                                }

                                // Ocultar el mensaje de error después de 3 segundos
                                const errorMessage = document.getElementById('error-message');
                                if (errorMessage) {
                                    setTimeout(() => {
                                        errorMessage.style.display = 'none';
                                    }, 3000); // 3000 milisegundos = 3 segundos
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection