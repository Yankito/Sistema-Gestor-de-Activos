@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iansa | Tabla Personas</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="vendor/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vendor/adminlte/dist/css/adminlte.min.css?v=3.2.0">
<script data-cfasync="false" nonce="084c37db-8d53-4826-86ca-d286c27f94af">try{(function(w,d){!function(a,b,c,d){if(a.zaraz)console.error("zaraz is loaded twice");else{a[c]=a[c]||{};a[c].executed=[];a.zaraz={deferred:[],listeners:[]};a.zaraz._v="5848";a.zaraz._n="084c37db-8d53-4826-86ca-d286c27f94af";a.zaraz.q=[];a.zaraz._f=function(e){return async function(){var f=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:f})}};for(const g of["track","set","debug"])a.zaraz[g]=a.zaraz._f(g);a.zaraz.init=()=>{var h=b.getElementsByTagName(d)[0],i=b.createElement(d),j=b.getElementsByTagName("title")[0];j&&(a[c].t=b.getElementsByTagName("title")[0].text);a[c].x=Math.random();a[c].w=a.screen.width;a[c].h=a.screen.height;a[c].j=a.innerHeight;a[c].e=a.innerWidth;a[c].l=a.location.href;a[c].r=b.referrer;a[c].k=a.screen.colorDepth;a[c].n=b.characterSet;a[c].o=(new Date).getTimezoneOffset();if(a.dataLayer)for(const k of Object.entries(Object.entries(dataLayer).reduce(((l,m)=>({...l[1],...m[1]})),{})))zaraz.set(k[0],k[1],{scope:"page"});a[c].q=[];for(;a.zaraz.q.length;){const n=a.zaraz.q.shift();a[c].q.push(n)}i.defer=!0;for(const o of[localStorage,sessionStorage])Object.keys(o||{}).filter((q=>q.startsWith("_zaraz_"))).forEach((p=>{try{a[c]["z_"+p.slice(7)]=JSON.parse(o.getItem(p))}catch{a[c]["z_"+p.slice(7)]=o.getItem(p)}}));i.referrerPolicy="origin";i.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a[c])));h.parentNode.insertBefore(i,h)};["complete","interactive"].includes(b.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async bs=>new Promise((bt=>{if(bs){bs.e&&bs.e.forEach((bu=>{try{const bv=d.querySelector("script[nonce]"),bw=bv?.nonce||bv?.getAttribute("nonce"),bx=d.createElement("script");bw&&(bx.nonce=bw);bx.innerHTML=bu;bx.onload=()=>{d.head.removeChild(bx)};d.head.appendChild(bx)}catch(by){console.error(`Error executing script: ${bu}\n`,by)}}));Promise.allSettled((bs.f||[]).map((bz=>fetch(bz[0],bz[1]))))}bt()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script></head>
    @section('content')
    <section class = "content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tabla de personas</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table">
                  <thead>
                  <tr>
                    <th>rut</th>
                    <th>nombreUsuario</th>
                    <th>nombres</th>
                    <th>primerApellido</th>
                    <th>segundoApellido</th>
                    <th>supervisor</th>
                    <th>empresa</th>
                    <th>estadoEmpleado</th>
                    <th>centroCosto</th>
                    <th>denominacion</th>
                    <th>tituloPuesto</th>
                    <th>fechaInicio</th>
                    <th>usuarioTI</th>
                    <th>ubicacion</tr>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($personas as $persona)
                        <tr>
                            <td>{{ $persona->rut }}</td>
                            <td>{{ $persona->nombreUsuario }}</td>
                            <td>{{ $persona->nombres }}</td>
                            <td>{{ $persona->primerApellido }}</td>
                            <td>{{ $persona->segundoApellido }}</td>
                            <td>{{ $persona->supervisor }}</td>
                            <td>{{ $persona->empresa }}</td>
                            <td>{{ $persona->estadoEmpleado }}</td>
                            <td>{{ $persona->centroCosto }}</td>
                            <td>{{ $persona->denominacion }}</td>
                            <td>{{ $persona->tituloPuesto }}</td>
                            <td>{{ $persona->fechaInicio }}</td>
                            <td>{{ $persona->usuarioTI }}</td>
                            <td>{{ $persona->ubicacion }}</td>
                        </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>rut</th>
                    <th>nombreUsuario</th>
                    <th>nombres</th>
                    <th>primerApellido</th>
                    <th>segundoApellido</th>
                    <th>supervisor</th>
                    <th>empresa</th>
                    <th>estadoEmpleado</th>
                    <th>centroCosto</th>
                    <th>denominacion</th>
                    <th>tituloPuesto</th>
                    <th>fechaInicio</th>
                    <th>usuarioTI</th>
                    <th>ubicacion</tr>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
        <!-- /.container-fluid -->
      </section>

    @endsection



<!-- jQuery -->
<script src="vendor/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="vendor/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="vendor/adminlte/plugins/jszip/jszip.min.js"></script>
<script src="vendor/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="vendor/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="vendor/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="vendor/adminlte/dist/js/adminlte.min.js?v=3.2.0"></script>
<!-- AdminLTE for demo purposes -->
<script src="vendor/adminlte/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "scrollX": true,
      "buttons": [
        { 
            extend:"copy",
            title: 'Iansa - Tabla de personas',
        },
        { 
            extend:"csv",
            title: 'Iansa - Tabla de personas',
        },
        { 
            extend:"excel",
            title: 'Iansa - Tabla de personas',
        },
        { 
            extend:"print",
            title: 'Iansa - Tabla de personas',
        }, 
        "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
</html>
