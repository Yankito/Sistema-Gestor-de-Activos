<section class="content">

<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard de {{ $filtro }}</h1>
        </div>
        <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
                <div class="nav-link me-2" data-toggle="dropdown" href="#">
                    <i class="fas fa-th mr-1"></i>
                    Cambiar Tipo de Activo
                </div>
                    <select wire:model="filtro" wire:change="actualizarAtributo($event.target.value)" id="filtro" class="form-control select2bs4">
                        @foreach ($atributos as $atributo)
                            <option value="{{$atributo}}">
                                {{ ucfirst($atributo)}}
                            </option>
                        @endforeach
                    </select>
            </div>

        </div>
    </div>
    <div class="col-sm-6 d-flex align-items-center">


    </div><!-- /.container-fluid -->
</div>

<!-- small box -->
<div class="small-box bg-info" style="background-color: #50ACB8 !important;">
    <div class="inner text-center">
        <p>Activos Totales</p>
        <h3>{{$cantidadActivos}}</h3>
    </div>
    <div class="icon" style="cursor: pointer;">
        <i class="ion ion-laptop"></i>
    </div>
    <a href="/tablaActivos" class="small-box-footer">Ver activos <i class="fas fa-arrow-circle-right"></i></a>
</div>

<div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @if($conteoValores !=null)
                        @foreach($conteoValores as $valor => $cantidad)
                            <div class="col-lg-3 col-6" style="cursor: pointer;">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $cantidad }}</h3>
                                        <p>{{ $valor }}</p>
                                    </div>
                                    <div class="icon" style="cursor: pointer;">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <form id="update-ubicacion-form" action="{{ route('actualizar.dashboardUbicacion') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="ubicacion_id" id="ubicacion_id" value="">
                    </form>
                </div>
            </div>



<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">

        <form id="update-ubicacion-form" action="{{ route('actualizar.dashboardUbicacion') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="ubicacion_id" id="ubicacion_id" value="">
        </form>
    </div>
</div>
</section>

<script>
    document.addEventListener('livewire:navigated', function() {
        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
        $(function () {
            $('#filtro').on('change', function () {
                console.log('cambio: ' + $(this).val());
                Livewire.dispatch('actualizarAtributo', [$(this).val() ]);
            });
        });
    });
</script>
