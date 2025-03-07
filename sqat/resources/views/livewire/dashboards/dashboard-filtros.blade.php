<section class="content">

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard {{ ucfirst(strtolower(str_replace('_', ' ', $nombreVista))) }}</h1>
            </div>
            <div class="col-sm-6">
                <div class="breadcrumb float-sm-right">
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

    <div class="row">
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
                                        data-toggle="tooltip" data-placement="top" title="{{ $estado['descripcion'] }}">
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
                <form id="update-tipoDeActivo-form" action="{{ route('actualizar.dashboardTipo') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="tipoDeActivo_id" id="tipoDeActivo_id" value="">
                </form>
                <form id="update-ubicacion-form" action="{{ route('actualizar.dashboardUbicacion') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="ubicacion_id" id="ubicacion_id" value="">
                </form>
            @endif

        </div>
    </div>


</section>

<script>
    document.addEventListener('livewire:navigated', function() {
        $(function () {
            $('#filtro').on('change', function () {
                console.log('cambio: ' + $(this).val());
                Livewire.dispatch('actualizarAtributo', [$(this).val() ]);
            });
        });
    });
    function updateTipoDeActivo(id) {
        document.getElementById('tipoDeActivo_id').value = id;
        document.getElementById('update-tipoDeActivo-form').submit();
    }

    function updateUbicacion(id) {
        document.getElementById('ubicacion_id').value = id;
        document.getElementById('update-ubicacion-form').submit();
    }
</script>
