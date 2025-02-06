@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
    <!-- CSS de AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <!-- JS de AdminLTE -->
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.7.2/proj4.js"></script> <!-- Incluir la librería proj4js -->

<script data-cfasync="false" nonce="0dee53e3-556b-42f5-b409-099c0e3badca">try{(function(w,d){!function(a,b,c,d){if(a.zaraz)console.error("zaraz is loaded twice");else{a[c]=a[c]||{};a[c].executed=[];a.zaraz={deferred:[],listeners:[]};a.zaraz._v="5848";a.zaraz._n="0dee53e3-556b-42f5-b409-099c0e3badca";a.zaraz.q=[];a.zaraz._f=function(e){return async function(){var f=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:f})}};for(const g of["track","set","debug"])a.zaraz[g]=a.zaraz._f(g);a.zaraz.init=()=>{var h=b.getElementsByTagName(d)[0],i=b.createElement(d),j=b.getElementsByTagName("title")[0];j&&(a[c].t=b.getElementsByTagName("title")[0].text);a[c].x=Math.random();a[c].w=a.screen.width;a[c].h=a.screen.height;a[c].j=a.innerHeight;a[c].e=a.innerWidth;a[c].l=a.location.href;a[c].r=b.referrer;a[c].k=a.screen.colorDepth;a[c].n=b.characterSet;a[c].o=(new Date).getTimezoneOffset();if(a.dataLayer)for(const k of Object.entries(Object.entries(dataLayer).reduce(((l,m)=>({...l[1],...m[1]})),{})))zaraz.set(k[0],k[1],{scope:"page"});a[c].q=[];for(;a.zaraz.q.length;){const n=a.zaraz.q.shift();a[c].q.push(n)}i.defer=!0;for(const o of[localStorage,sessionStorage])Object.keys(o||{}).filter((q=>q.startsWith("_zaraz_"))).forEach((p=>{try{a[c]["z_"+p.slice(7)]=JSON.parse(o.getItem(p))}catch{a[c]["z_"+p.slice(7)]=o.getItem(p)}}));i.referrerPolicy="origin";i.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a[c])));h.parentNode.insertBefore(i,h)};["complete","interactive"].includes(b.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async bs=>new Promise((bt=>{if(bs){bs.e&&bs.e.forEach((bu=>{try{const bv=d.querySelector("script[nonce]"),bw=bv?.nonce||bv?.getAttribute("nonce"),bx=d.createElement("script");bw&&(bx.nonce=bw);bx.innerHTML=bu;bx.onload=()=>{d.head.removeChild(bx)};d.head.appendChild(bx)}catch(by){console.error(`Error executing script: ${bu}\n`,by)}}));Promise.allSettled((bs.f||[]).map((bz=>fetch(bz[0],bz[1]))))}bt()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script></head>

@section('content')

  <!-- Content Wrapper. Contains page content -->

    <!-- /.content-header -->
    <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="pictures/Logo Empresas Iansa.png" alt="AdminLTELogo" height="100" width="200">
        </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6" style="cursor: pointer;" onclick="window.location.href='/subdashboard';">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$cantidadActivos}}</h3>
                        <p>Activos</p>
                    </div>
                    <div class="icon" style="cursor: pointer;">
                        <i class="ion ion-laptop"></i>
                    </div>
                    @if($user->es_administrador)
                        <a href="/registrarActivo" class="small-box-footer">Dar activo de alta <i class="fas fa-arrow-circle-right"></i></a>
                    @else
                        <a href="#" class="small-box-footer">       <i class="fas fa-arrow-circle-right"></i></a>
                    @endif
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-3 col-6" style = "cursor: pointer;">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $cantidadPersonas }}</h3>
                        <p>Personas</p>
                    </div>
                    <div class="icon" style="cursor: pointer;">
                    <i class="ion ion-person-add"></i>
                    </div>
                        <!-- si usuario es administrador puede acceder a resigter-->
                        @if($user->es_administrador)
                        <a href="/registrarPersona" class="small-box-footer">Asignar activo a persona nueva <i class="fas fa-arrow-circle-right"></i></a>
                        @else
                        <a href="#" class="small-box-footer">       <i class="fas fa-arrow-circle-right"></i></a>
                        @endif
                    </div>
                </div>
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
            </div>
            </div>

            <!-- /.card-body -->
            <div class="card-footer bg-transparent">
            <div class="row">
                @foreach($cantidadPorEstados as $estado => $cantidad)
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{  round(($cantidad/$cantidadActivos)*100) }}" data-width="60" data-height="60"
                            data-fgColor="#39CCCC">
                        <div class="text-white">{{ ucfirst(strtolower($estado)) }}</div>
                    </div>
                @endforeach

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

            <div class="container-fluid">
                <!-- Aquí se incluye el mapa -->
                @include('mapa')
            </div>

    </section>
    <!-- /.content -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: "{{session('title')}}",
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection




</html>
