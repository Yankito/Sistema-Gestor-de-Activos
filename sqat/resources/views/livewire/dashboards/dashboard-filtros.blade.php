<section class="content">

    <style>
        .dropdown-menu {
            max-height: 300px; /* Ajusta la altura según necesites */
            overflow-y: auto;
        }
    </style>
    @section('navbar-custom')
        <div id="navbar-custom" wire:ignore>
            <div class="col-sm-12">
                <div class="breadcrumb float-sm-right">
                    <a class="nav-link me-2" data-toggle="dropdown" href="#">
                        <i class="fas fa-th mr-1"></i>
                        <span id="navbar-text">
                            @if($vista === "UBICACION")
                                Cambiar Ubicación
                            @elseif($vista === "TIPO_DE_ACTIVO")
                                Cambiar Tipo de Activo
                            @endif
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="navbar-dropdown">
                        <a class="dropdown-item dropdown-footer" style="cursor: pointer;" onclick="updateGeneral()" >DASHBOARD GENERAL</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard {{ ucfirst(strtolower(str_replace('_', ' ', $nombreVista))) }}</h1>
            </div>
            <div class="col-sm-6">
                <div class="breadcrumb float-sm-right" wire:ignore>
                    <div class="nav-link me-2" data-toggle="dropdown" href="#">
                        <i class="fas fa-th mr-1"></i>
                        Aplicar filtro
                    </div>
                    <select wire:model="filtro" wire:change="actualizarAtributo($event.target.value)" id="filtro" class="form-control">
                        @foreach ($atributos as $atributo)
                            <option value="{{$atributo}}">
                                {{ ucfirst(str_replace('_', ' ', $atributo)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="row" wire:poll.5s>
        <div class="col-lg-5 connectedSortable ui-sortable">
            <div style="cursor: pointer;" onclick="window.location.href='/tablaActivos'" >
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner text-center">
                        <p>Activos En Servicio</p>
                        <h3>{{$activosEnServicio}}</h3>
                    </div>
                    <div class="icon" style="cursor: pointer;">
                        <i class="ion ion-laptop"></i>
                    </div>
                    <a href="/tablaActivos" class="small-box-footer">Ver activos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div style="cursor: pointer;" onclick="window.location.href='/tablaActivos'" >
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner text-center">
                        <p>Activos Fuera de Servicio</p>
                        <h3>{{$activosFueraDeServicio}}</h3>
                    </div>
                    <div class="icon" style="cursor: pointer;">
                        <i class="ion ion-alert-circleds"></i>
                    </div>
                    <a href="/tablaActivos" class="small-box-footer">Ver activos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-7 connectedSortable ui-sortable" >
            <div class="card bg-gradient-info" style="border: #00b5c4 !important;" >
                <div class="card-header border-0" style="background-color: #00b5c4 !important;">
                    <h3 class="card-title" style="color: #ffffff !important;">
                        <i class="fas fa-th mr-1"></i>
                        Cantidad de activos por estado
                    </h3>

                </div>

                <!-- /.card-body -->
                <div class="card-footer bg-transparent" style="background-color: #00b5c4 !important">
                    <div class="row">
                        @foreach($cantidadPorEstados as $nombre => $estado)
                            <div class="col-md-6">
                                <div class="progress-group">
                                    {{ $nombre }}
                                    <i class="fas fa-info-circle" style="color: rgba(255, 255, 255, 0.7);"
                                        data-toggle="tooltip" data-placement="top" title="{{ $estado['descripcion'] }}" wire:ignore>
                                    </i>
                                    <span class="float-right"><b>{{ $estado['cantidad'] }}</b>/{{ $cantidadActivos }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: {{ $cantidadActivos != 0 ? ($estado['cantidad'] / $cantidadActivos) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                <!-- /.row -->
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            @if($conteoValores !=null)
                @foreach($conteoValores as $id => $valores)

                    <div class="col-lg-3 col-6" style="cursor: pointer;"
                        onclick="{{ $filtro === 'tipo_de_activo' ? "updateTipoDeActivo('" .$id. "')" :
                                ($filtro === 'ubicacion' ? "updateUbicacion('" .$id. "')" : '') }}">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $valores['cantidad'] }}</h3>
                                <p>{{ $valores['nombre'] }}</p>
                            </div>
                            <div class="icon" style="cursor: pointer;">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>


</section>

<script>
    document.addEventListener('livewire:navigated', function() {
        $(function () {
            $('#filtro').on('change', function () {
                Livewire.dispatch('actualizarAtributo', [$(this).val() ]);
            });
        });

        Livewire.on('actualizarDashboard', (data) => {
            let vista = data[0]['vista'];
            let opcionesDashboard = data[0]['opcionesDashboard'];

            // Actualizar el texto del navbar
            let navbarText = document.getElementById('navbar-text');
            if (navbarText) {
                navbarText.textContent = vista === "UBICACION" ? "Cambiar Ubicación" : "Cambiar Tipo de Activo";
            }

            // Actualizar las opciones del dropdown
            let dropdownMenu = document.getElementById('navbar-dropdown');
            if (dropdownMenu) {
                dropdownMenu.innerHTML = ''; // Vaciar contenido
                dropdownMenu.className = "dropdown-menu dropdown-menu-lg dropdown-menu-right";

                // Iterar sobre las claves y valores del objeto opcionesDashboard
                Object.entries(opcionesDashboard).forEach(([opcion, nombre]) => {
                    let item = document.createElement('a');
                    item.className = "dropdown-item";
                    item.textContent = nombre.charAt(0).toUpperCase() + nombre.slice(1);
                    item.onclick = function() { vista === "UBICACION" ? updateUbicacion(opcion) : updateTipoDeActivo(opcion) };
                    item.style.cursor = "pointer";

                    // Marcar el seleccionado
                    if (opcion === data[0]['valor']){
                        item.classList.add('active');
                    }

                    dropdownMenu.appendChild(item);
                });

                // Agregar opción de dashboard general
                let generalItem = document.createElement('a');
                generalItem.className = "dropdown-item dropdown-footer";
                generalItem.textContent = "DASHBOARD GENERAL";
                generalItem.onclick = updateGeneral;
                generalItem.style.cursor = "pointer";
                dropdownMenu.appendChild(generalItem);
            }
        });
    });

</script>
