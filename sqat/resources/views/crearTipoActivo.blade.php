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
                                <button class="btn btn-primary btn-block mb-3" type="submit">Registrar Tipo</button>
                            </form>
                            <a href="/dashboard" class="btn btn-outline-danger">Volver atrás</a>

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
                                                    </tr>
                                                </thead>
                                                <tbody id="tipoActivoTable">
                                                    @foreach($tiposActivo as $tipo)
                                                        <tr>
                                                            <td>{{ $tipo->nombre }}</td>
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
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
