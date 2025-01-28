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
                                                    <select name="activo" id="activo" class="form-control select2bs4" style="width: 100%;">
                                                        @foreach($activos as $activo)
                                                            @if ($activo->estado == 'DISPONIBLE')
                                                                <option value="{{$activo->nroSerie}}">{{$activo->nroSerie}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="asignarResponsable" name="asignarResponsable">
                                                <label class="form-check-label" for="asignarResponsable">Asignar Responsable</label>
                                            </div>
                                        </div>
                                        <div class="form-outline mb-4" id="responsableSection" style="display: none;">
                                            <div class="form-group">
                                                <label class="form-label" for="responsable">Responsable</label>
                                                <select name="responsable" id="responsable" select class="form-control select2bs4">
                                                    <option value="" disabled selected>Seleccione un responsable</option>
                                                    @foreach($personas as $persona)
                                                        <option value="{{$persona->rut}}">{{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="agregarActivo" name="agregarActivo">
                                                <label class="form-check-label" for="agregarActivo">Agregar Activo</label>
                                            </div>
                                        </div>
                                        <div class="form-outline mb-4" id="activoSection" style="display: none;">
                                            <div class="form-group">
                                                <label>Multiple</label>
                                                <select class="select2bs4" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" id="activoSelect">
                                                    @foreach($activos as $activo)
                                                        @if ($activo->estado == 'DISPONIBLE')
                                                            <option value="{{$activo->nroSerie}}">{{$activo->nroSerie}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div id="justificacionContainer">
                                                <!-- Aquí se generarán los campos de justificación para cada activo -->
                                            </div>
                                        </div>

                                        <script>
                                            // Cuando el select de activos cambie, actualizamos la justificación
                                            document.getElementById('activoSection').addEventListener('change', function() {
                                                var selectedActivos = Array.from(this.selectedOptions).map(option => option.value);
                                                var justificacionContainer = document.getElementById('justificacionContainer');

                                                // Limpiar cualquier justificación previa
                                                justificacionContainer.innerHTML = '';

                                                // Para cada activo seleccionado, generar un campo de justificación
                                                selectedActivos.forEach(function(activo) {
                                                    var div = document.createElement('div');
                                                    div.classList.add('form-group');

                                                    var label = document.createElement('label');
                                                    label.classList.add('form-label');
                                                    label.textContent = 'Justificación para ' + activo;

                                                    var input = document.createElement('input');
                                                    input.type = 'text';
                                                    input.name = 'justificacion_' + activo;
                                                    input.classList.add('form-control');

                                                    div.appendChild(label);
                                                    div.appendChild(input);
                                                    justificacionContainer.appendChild(div);
                                                });
                                            });
                                        </script>


                                        <script>
                                            document.getElementById('agregarActivo').addEventListener('change', function() {
                                                var activoSection = document.getElementById('activoSection');
                                                var activoJustificacion = document.getElementById('activoJustificacion');
                                                var activoSeleccion = document.getElementById('activoSeleccion');

                                                if (this.checked) {
                                                    activoSection.style.display = 'block';
                                                } else {
                                                    activoSection.style.display = 'none';
                                                    // Resetea los valores de los campos antes de enviar el formulario
                                                    activoJustificacion.value = '';
                                                    activoSeleccion.value = null;
                                                }
                                            });

                                            // Resetea los valores de los campos antes de enviar el formulario
                                            document.querySelector('form').addEventListener('submit', function() {
                                                var agregarActivo = document.getElementById('agregarActivo');
                                                var activoJustificacion = document.getElementById('activoJustificacion');
                                                var activoSeleccion = document.getElementById('activoSeleccion');

                                                if (!agregarActivo.checked) {
                                                    activoJustificacion.value = '';
                                                    activoSeleccion.value = null;  // No envía el valor cuando no está marcado
                                                }
                                            });
                                        </script>

                                        <script>
                                            document.getElementById('asignarResponsable').addEventListener('change', function() {
                                                var responsableSection = document.getElementById('responsableSection');
                                                var responsableSelect = document.getElementById('responsable');

                                                if (this.checked) {
                                                    responsableSection.style.display = 'block';
                                                } else {
                                                    responsableSection.style.display = 'none';
                                                    // Resetea el valor del select antes de enviar el formulario
                                                    responsableSelect.value = null;
                                                }
                                            });

                                            // Resetea el valor del campo 'responsable' antes de enviar el formulario
                                            document.querySelector('form').addEventListener('submit', function() {
                                                var asignarResponsable = document.getElementById('asignarResponsable');
                                                var responsableSelect = document.getElementById('responsable');

                                                if (!asignarResponsable.checked) {
                                                    responsableSelect.value = null;  // No envía el valor cuando no está marcado
                                                }
                                            });
                                        </script>

                                    </div>
                                    <!-- Botón de Enviar -->
                                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3 text-center" type="submit">Registrar Persona</button>
                                </form>
                                <!-- Botón de Volver atrás -->
                                <a href="/dashboard" type="button" class="btn btn-outline-danger text-center">Volver atrás</a>
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
