@extends('layouts.app')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

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
  <style>
    .filter-container {
      display: none;
      position: absolute;
      background:white;
      padding: 10 px;
      border: 1px solid #d2d6de;
      z-index: 10;
    }
    .filter-btn {
      background: none;
      border: none;
      color: #007bff;
      cursor: pointer;
      font-size: 10px;
    }
  </style>
<script data-cfasync="false" nonce="084c37db-8d53-4826-86ca-d286c27f94af">try{(function(w,d){!function(a,b,c,d){if(a.zaraz)console.error("zaraz is loaded twice");else{a[c]=a[c]||{};a[c].executed=[];a.zaraz={deferred:[],listeners:[]};a.zaraz._v="5848";a.zaraz._n="084c37db-8d53-4826-86ca-d286c27f94af";a.zaraz.q=[];a.zaraz._f=function(e){return async function(){var f=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:f})}};for(const g of["track","set","debug"])a.zaraz[g]=a.zaraz._f(g);a.zaraz.init=()=>{var h=b.getElementsByTagName(d)[0],i=b.createElement(d),j=b.getElementsByTagName("title")[0];j&&(a[c].t=b.getElementsByTagName("title")[0].text);a[c].x=Math.random();a[c].w=a.screen.width;a[c].h=a.screen.height;a[c].j=a.innerHeight;a[c].e=a.innerWidth;a[c].l=a.location.href;a[c].r=b.referrer;a[c].k=a.screen.colorDepth;a[c].n=b.characterSet;a[c].o=(new Date).getTimezoneOffset();if(a.dataLayer)for(const k of Object.entries(Object.entries(dataLayer).reduce(((l,m)=>({...l[1],...m[1]})),{})))zaraz.set(k[0],k[1],{scope:"page"});a[c].q=[];for(;a.zaraz.q.length;){const n=a.zaraz.q.shift();a[c].q.push(n)}i.defer=!0;for(const o of[localStorage,sessionStorage])Object.keys(o||{}).filter((q=>q.startsWith("_zaraz_"))).forEach((p=>{try{a[c]["z_"+p.slice(7)]=JSON.parse(o.getItem(p))}catch{a[c]["z_"+p.slice(7)]=o.getItem(p)}}));i.referrerPolicy="origin";i.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a[c])));h.parentNode.insertBefore(i,h)};["complete","interactive"].includes(b.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async bs=>new Promise((bt=>{if(bs){bs.e&&bs.e.forEach((bu=>{try{const bv=d.querySelector("script[nonce]"),bw=bv?.nonce||bv?.getAttribute("nonce"),bx=d.createElement("script");bw&&(bx.nonce=bw);bx.innerHTML=bu;bx.onload=()=>{d.head.removeChild(bx)};d.head.appendChild(bx)}catch(by){console.error(`Error executing script: ${bu}\n`,by)}}));Promise.allSettled((bs.f||[]).map((bz=>fetch(bz[0],bz[1]))))}bt()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script></head>
    @section('content')
    <section class = "content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tabla de activos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div style = "overflow-x:auto;">
                  <table id="example1" class="table">
                    <thead>
                    <tr>
                      @foreach(["Número de serie", "Marca", "Modelo", "Precio", "Tipo", "Estado", "Usuario", "Responsable", "Sitio", "Soporte TI", "Justificación"] as $index => $columna)
                        <th>
                          {{ $columna }}
                          <!-- boton filtro -->
                          <button class="filter-btn" data-index="{{ $index }}">
                            <i class="fas fa-filter"></i>
                          </button>
                          <div class="filter-container" id="filter-{{ $index }}">
                            <input type="text" class="column-search" data-index="{{ $index }}" placeholder="Buscar...">
                            <div class="checkbox-filters" data-index="{{ $index }}"></div>
                          </div>
                        </th>
                      @endforeach
                    </tr>
                    </thead>
                    <tbody>
                      @foreach($datos as $dato)
                    <tr>
                          <td>{{ $dato->nroSerie }}</td>
                          <td>{{ $dato->marca }}</td>
                          <td>{{ $dato->modelo }}</td>
                          <td>{{ $dato->precio }}</td>
                          <td>{{ $dato->tipoDeActivo }}</td>
                          <td>{{ $dato->estado }}</td>
                          <td>{{ $dato->rutUsuario}}</td>
                          <td>{{ $dato->rutResponsable}}</td>
                          <td>{{ $dato->sitio }}</td>
                          <td>{{ $dato->soporteTI }}</td>
                          <td>{{ $dato->justificacionDobleActivo }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                      <th>Número de serie</th>
                      <th>Marca</th>
                      <th>Modelo</th>
                      <th>Precio</th>
                      <th>Tipo</th>
                      <th>Estado</th>
                      <th>Usuario</th>
                      <th>responsable</th>
                      <th>Sitio</th>
                      <th>Soporte TI</th>
                      <th>Justificación</th>
                    </tr>
                    </tfoot>
                  </table>
                </div>
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
  $(document).ready(function() {
    let table = $('#example1').DataTable();
    $('.filter-btn').click(function() {
      let index = $(this).data('index');
      $(`#filter-${index}`).toggle();
    });
    $('.column-search').on('keyup', function() {
      let index = $(this).data('index');
      table.column(index).search(this.value).draw();
    });
    table.columns().every(function(index) {
      let column = this;
      let uniqueValues = new Set();
      column.data().each(function(value) {
        uniqueValues.add(value);
      });
      let checkboxContainer = $(`.checkbox-filters[data-index="${index}"]`);
      uniqueValues.forEach(value => {
        checkboxContainer.append(
          `<label><input type="checkbox" class="column-checkbox" data-index="${index}" value="${value}"> ${value}</label><br>`
        );
      });
      checkboxContainer.on('change', 'input', function() {
        let checkedValues = checkboxContainer.find('input:checked').map(function() {
          return $.fn.dataTable.util.escapeRegex($(this).val());
        }).get().join('|');
        column.search(checkedValues.length ? `^(${checkedValues})$` : '', true, false).draw();
      });
    });
  });
</script>

</html>
