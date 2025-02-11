<div style = "overflow-x:auto;">
                  <table id="tabla" class="table table-bordered table-hover table-striped dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación"] as $index => $columna)
                                <th>
                                {{ $columna }}
                                <!-- boton filtro -->
                                <button class="filter-btn" data-index="{{ $index + 1 }}">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <div class="filter-container" id="filter-{{ $index + 1}}">
                                    <input type="text" class="column-search" data-index="{{ $index + 1}}" placeholder="Buscar...">
                                    <div class="checkbox-filters" data-index="{{ $index + 1}}"></div>
                                </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activos as $dato)
                            <tr data-id="{{ $dato->id }}">
                                @include('activos.filasTabla', ['dato' => $dato])
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>