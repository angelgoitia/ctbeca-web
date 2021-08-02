<!--- Modal Player-->
<div class="modal fade" id="playerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg has-success">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><Strong>Nuevo Becados</Strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body">
                <form id="formPlayer" action="{{route('admin.formPlayer')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Nombre Jugador</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" minlength="3" placeholder="Joe Doe" pattern="([a-zA-ZÁÉÍÓÚñáéíóú]{1,}[\s]*)+" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Telefóno</label>
                        <label class="content-select content-select">
                            <select class="addMargin" name="digPhone" id="digPhone" required>
                                <option value="" disabled selected>Seleccionar</option>    
                                <option value="0412">0412</option>
                                <option value="0414">0414</option>
                                <option value="0414">0424</option>
                                <option value="0416">0416</option>
                                <option value="0426">0426</option>
                            </select>
                        </label>
                        <div class="col-sm-4">
                            <input type="tel" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" id="phone" name="phone" minlength="6" maxlength="7" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Telegram</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="telegram" name="telegram" placeholder="@ctbeca" pattern="[@][A-Za-z0-9_]{5,20}" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Correo Electrónico</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="email" name="email" placeholder="joedoe@hotmail.com" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Referencia</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="reference" name="reference" minlength="3" pattern="([a-zA-ZÁÉÍÓÚñáéíóú]{1,}[\s]*)+">
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-center minh-10">
                        <h4><Strong>Acceso del Juego</Strong></h4>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Billetera:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="wallet" autocomplete="off" minlength="20" placeholder="Billetera" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Código QR</label>
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
                            <div>
                                <span class="btn btn-raised btn-round btn-default btn-file">
                                    <input type="file" name="codeQr" required/>
                                </span>
                                <a href="" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i>Eliminar</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Correo Electrónico </label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="emailGame" name="emailGame" placeholder="joedoe@hotmail.com" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Contraseña</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="marginAuto">
                    <button type="submit" class="submit btn btn-bottom" id="submit_player" form="formPlayer">Guardar Becados</button>
                </div>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>