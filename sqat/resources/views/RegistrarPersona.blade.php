@extends('layouts.app')
<!doctype html>
<html lang="en">
    <head>
        <title>Registrar Persona</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

                <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />

    <script data-cfasync="false" nonce="2823c135-4b84-4a99-b748-a0ec6bd3fac9">try{(function(w,d){!function(a,b,c,d){if(a.zaraz)console.error("zaraz is loaded twice");else{a[c]=a[c]||{};a[c].executed=[];a.zaraz={deferred:[],listeners:[]};a.zaraz._v="5848";a.zaraz._n="2823c135-4b84-4a99-b748-a0ec6bd3fac9";a.zaraz.q=[];a.zaraz._f=function(e){return async function(){var f=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:f})}};for(const g of["track","set","debug"])a.zaraz[g]=a.zaraz._f(g);a.zaraz.init=()=>{var h=b.getElementsByTagName(d)[0],i=b.createElement(d),j=b.getElementsByTagName("title")[0];j&&(a[c].t=b.getElementsByTagName("title")[0].text);a[c].x=Math.random();a[c].w=a.screen.width;a[c].h=a.screen.height;a[c].j=a.innerHeight;a[c].e=a.innerWidth;a[c].l=a.location.href;a[c].r=b.referrer;a[c].k=a.screen.colorDepth;a[c].n=b.characterSet;a[c].o=(new Date).getTimezoneOffset();if(a.dataLayer)for(const k of Object.entries(Object.entries(dataLayer).reduce(((l,m)=>({...l[1],...m[1]})),{})))zaraz.set(k[0],k[1],{scope:"page"});a[c].q=[];for(;a.zaraz.q.length;){const n=a.zaraz.q.shift();a[c].q.push(n)}i.defer=!0;for(const o of[localStorage,sessionStorage])Object.keys(o||{}).filter((q=>q.startsWith("_zaraz_"))).forEach((p=>{try{a[c]["z_"+p.slice(7)]=JSON.parse(o.getItem(p))}catch{a[c]["z_"+p.slice(7)]=o.getItem(p)}}));i.referrerPolicy="origin";i.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a[c])));h.parentNode.insertBefore(i,h)};["complete","interactive"].includes(b.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async bs=>new Promise((bt=>{if(bs){bs.e&&bs.e.forEach((bu=>{try{const bv=d.querySelector("script[nonce]"),bw=bv?.nonce||bv?.getAttribute("nonce"),bx=d.createElement("script");bw&&(bx.nonce=bw);bx.innerHTML=bu;bx.onload=()=>{d.head.removeChild(bx)};d.head.appendChild(bx)}catch(by){console.error(`Error executing script: ${bu}\n`,by)}}));Promise.allSettled((bs.f||[]).map((bz=>fetch(bz[0],bz[1]))))}bt()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script></head>


    @section('content')
        <section class="h-100 gradient-form" style="background-color: #eee;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                        <!-- Aquí va el contenido de la página centrado-->
                            <div class = "card-body p-md-5 mx-md-4"> 
                                <div class="text-center mb-4">
                                    <img src="{{asset('pictures/Logo Empresas Iansa.png')}}" style="width: 300px;" alt="logo">
                                </div>        

                                <h2>Registrar nueva Persona</h2>
                                <form action="/personas" method="POST">
                                    @csrf
                                    <div class = "row">
                                        <div class="col-md-6">
                                            <!-- RUT -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="rut">RUT</label>
                                                <input type="text" name="rut" id="rut" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <!-- Nombre de Usuario -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="nombreUsuario">Nombre de Usuario</label>
                                                <input type="text" name="nombreUsuario" id="nombreUsuario" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class="col-md-6">
                                            <!-- Nombres -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="nombres">Nombres</label>
                                                <input type="text" name="nombres" id="nombres" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Primer Apellido -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="primerApellido">Primer Apellido</label>
                                                <input type="text" name="primerApellido" id="primerApellido" required class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Segundo Apellido -->
                                    <div class = "row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="segundoApellido">Segundo Apellido</label>
                                                <input type="text" name="segundoApellido" id="segundoApellido" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Supervisor -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="supervisor">Supervisor</label>
                                                <input type="text" name="supervisor" id="supervisor" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                            <!-- Empresa -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="empresa">Empresa</label>
                                                <input type="text" name="empresa" id="empresa" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Centro Costo -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="centroCosto">Centro Costo</label>
                                                <input type="text" name="centroCosto" id="centroCosto" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                            <!-- Denominación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="denominacion">Denominación</label>
                                                <input type="text" name="denominacion" id="denominacion" class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Título Puesto -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="tituloPuesto">Título Puesto</label>
                                                <input type="text" name="tituloPuesto" id="tituloPuesto" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                            <!-- Fecha Inicio -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="fechaInicio">Fecha Inicio</label>
                                                <input type="date" name="fechaInicio" id="fechaInicio" required class="form-control" />
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Usuario TI -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="usuarioTI">Usuario TI</label>
                                                <select name="usuarioTI" id="usuarioTI" class="form-control" required>
                                                    <option value="1">SI</option>
                                                    <option value="0">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-6">
                                             <!-- Ubicación -->
                                            <div class="form-outline mb-4">
                                                <label class="form-label" for="ubicacion">Ubicación</label>
                                                <select name="ubicacion" id="ubicacion" class="form-control" required>
                                                    <!-- Aquí debes cargar las ubicaciones disponibles -->
                                                    @foreach($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}} - {{$ubicacion->soporteTI}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class = "col-md-6">
                                            <!-- Activo -->
                                            <div class="form-outline mb-4">
                                                <div class="form-group">
                                                    <label class = "form-label">Activos</label>
                                                    <select class="form-control select2bs4" style="width: 100%;">
                                                        @foreach($activos as $activo)
                                                            @if ($activo->estado == 'DISPONIBLE')
                                                                <option value="{{$activo->nroSerie}}">{{$activo->nroSerie}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Botón de Enviar -->
                                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3 text-center" type="submit">Registrar Persona</button>
                                    <!-- Botón de Volver atrás -->
                                    <a href="/dashboard" type="button" class="btn btn-outline-danger text-center">Volver atrás</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Estilos -->
        <style>
            form .form-label {
                font-size: 15px;
                color: #4b4b4b;
            }
        </style>
    @endsection


</html>
