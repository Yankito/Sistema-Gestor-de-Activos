@extends('layouts.app')
<!doctype html>
<html lang="es">
    <head>
        <title>Subdashboard</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link href="{{asset('assets/estiloLogin.css')}}" rel="stylesheet">


    </head>

    @section('content')
    <section class="content">

        <div class="content-header">
            <div class="container-fluid">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard de {{$ubicacion->sitio}}</h1>
                </div><!-- /.col -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="card bg-gradient-info">
            <div class="card-header border-0">
            <h3 class="card-title">
                <i class="fas fa-th mr-1"></i>
                Cantidad de activos por estado
            </h3>

            <div class="card-tools">
                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                <i class="fas fa-times"></i>
                </button>
            </div>
            </div>

            <!-- /.card-body -->
            <div class="card-footer bg-transparent">
            <div class="row">
                @foreach($cantidadPorEstados as $estado => $cantidad)
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{ ($cantidad/$cantidadActivos)*100 }}" data-width="60" data-height="60"
                            data-fgColor="#39CCCC" data-displayInput="true">
                        <div class="text-white">{{ ucfirst(strtolower($estado)) }}</div>
                    </div>
                @endforeach

                <div class="col-md-4">
                    <p class="text-center">
                      <strong>Goal Completion</strong>
                    </p>

                    @foreach($cantidadPorEstados as $estado => $cantidad)
                        <div class="progress-group">
                            {{ ucfirst(strtolower($estado)) }}
                            <span class="float-right"><b>{{ $cantidad }}</b>/{{ $cantidadActivos }}</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: {{ ($cantidad/$cantidadActivos)*100 }}%"></div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
            <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>

        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                @foreach($tiposDeActivo as $tipoDeActivo => $cantidad)
                    <div class="col-lg-3 col-6" style="cursor: pointer;">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $cantidad }}</h3>
                                <p>{{ ucfirst(strtolower($tipoDeActivo))}}</p>
                            </div>
                            <div class="icon" style="cursor: pointer;">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endsection
</html>
