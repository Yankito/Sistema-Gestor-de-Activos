
                <div class="modal-body">
                    <h2>Editar activo</h2>
                    <form action="{{ route('activos.update', $activo->id) }}" method="POST">
                        @csrf

                        <div class = "row">

                            <div class = " col-md-6">
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="nro_serie">Nro. Serie</label>
                                    <input type="text" name="nro_serie" id="nro_serie" required class="form-control" value="{{ $activo->nro_serie }}"/>
                                </div>
                            </div>

                            <div class = " col-md-6">
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="marca">Marca</label>
                                    <input type="text" name="marca" id="marca" required class="form-control" value="{{ $activo->marca }}"/>
                                </div>
                            </div>

                            <div class = " col-md-6">
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="modelo">Modelo</label>
                                    <input type="text" name="modelo" id="modelo" required class="form-control" value="{{ $activo->modelo }}"/>
                                </div>
                            </div>


                            <div class = " col-md-6">
                                <!-- Tipo de Activo -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="tipo_de_activo">Tipo de Activo</label>
                                    <select name="tipo_de_activo" id="tipo_de_activo" class="form-control" required>
                                        <option value="LAPTOP">Laptop</option>
                                        <option value="DESKTOP">Desktop</option>
                                        <option value="MONITOR">Monitor</option>
                                        <option value="IMPRESORA">Impresora</option>
                                        <option value="CELULAR">Celular</option>
                                        <option value="OTRO">Otro</option>
                                    </select>
                                </div>
                            </div>

                            <div class = " col-md-6">
                                <!-- Precio -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="precio">Precio</label>
                                    <input type="number" name="precio" id="precio" required class="form-control" value="{{ $activo->precio }}"/>
                                </div>
                            </div>


                            <div class = " col-md-6">
                                <!-- Ubicación -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="ubicacion">Ubicación</label>
                                    <select name="ubicacion" id="ubicacion" class="form-control" required>
                                        @foreach($ubicaciones as $ubicacion)
                                            <option value="{{$ubicacion->id}}">{{$ubicacion->sitio}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class = " col-md-6">
                                <div class="form-outline mb-4" id="responsableSection">
                                    <div class="form-group">
                                        <label class="form-label" for="responsable_de_activo">Responsable</label>
                                        <select name="responsable_de_activo" id="responsable_de_activo" select class="form-control select2bs4">
                                            <option value="{{ $activo->responsable_de_activo }}" selected>
                                                {{ $activo->responsableDeActivo->rut ?? '' }}: {{ $activo->responsableDeActivo->getNombreCompletoAttribute() ?? '' }}
                                            </option>
                                            @foreach($personas as $persona)
                                                <option value="{{$persona->id}}">{{$persona->rut}}: {{$persona->getNombreCompletoAttribute()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>

                </div>


