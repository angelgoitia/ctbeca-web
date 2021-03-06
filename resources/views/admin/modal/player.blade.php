<!--- Modal Player-->
<div class="modal fade" id="playerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg has-success">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <Strong>
                        @if($playerSelect) 
                            Modificar Becados 
                        @else 
                            Nuevo Becados 
                        @endif
                    </Strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body">
                <form id="formPlayer" action="{{route('admin.formPlayer')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Nombre Jugador</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" minlength="3" placeholder="Joe Doe" pattern="([a-zA-ZÁÉÍÓÚñáéíóú]{1,}[\s]*)+" value="{{$playerSelect? $playerSelect->name : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        @php
                            $listDig = array('0412', '0414', '0424', '0416', '0426');
                        @endphp

                        <label class="col-sm-4 col-form-label">Telefóno</label>
                        <label class="content-select content-select">
                            <select class="addMargin" name="digPhone" id="digPhone" required>
                                @if($playerSelect)
                                    <option value="" disabled>Seleccionar</option>
                                @else
                                    <option value="" disabled selected>Seleccionar</option>
                                @endif

                                @foreach($listDig as $dig)
                                    @if($playerSelect && substr($playerSelect->phone, 0, 4))
                                        <option value="{{$dig}}" selected>{{$dig}}</option> 
                                    @else
                                        <option value="{{$dig}}">{{$dig}}</option> 
                                    @endif
                                @endforeach
                            </select>
                        </label> 
                        <div class="col-sm-4">
                            <input type="tel" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" id="phone" name="phone" minlength="6" maxlength="7" value="{{$playerSelect? substr($playerSelect->phone,5) : ''}}" autocomplete="off" required>
                        </div> 
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Telegram</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="telegram" name="telegram" placeholder="@ctbeca ó 04125555555" pattern="(([@][A-Za-z0-9_]{5,50})|((0414|0424|0412|0416|0426)[0-9]{7}))" value="{{$playerSelect? $playerSelect->telegram : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Correo Electrónico</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="email" name="email" placeholder="joedoe@hotmail.com" value="{{$playerSelect? $playerSelect->email : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Referencia</label>
                        <div class="col-sm-3 .referenceSelect">
                            <label class="content-select content-select">
                                <select class="addMargin" name="reference" id="reference" required>
                                    @if($playerSelect)
                                        <option value="" disabled>Seleccionar</option>
                                    @else
                                        <option value="" disabled selected>Seleccionar</option>
                                    @endif
                                    @php $statusReference = true; @endphp
                                    @foreach($players as $player)
                                        @if(!empty($playerSelect) && $playerSelect->reference == $player->name)
                                            @php $statusReference = false; @endphp
                                            <option value="{{$player->name}}" selected>{{$player->name}}</option> 
                                        @else
                                            <option value="{{$player->name}}">{{$player->name}}</option> 
                                        @endif
                                    @endforeach
                                    @if($playerSelect && $statusReference)
                                        <option value="Otro" selected> Otro</option> 
                                    @else
                                        <option value="Otro"> Otro</option>
                                    @endif
                                </select>
                            </label> 
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label"></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control"  id="referenceOther" name="referenceOther" minlength="3" pattern="([a-zA-ZÁÉÍÓÚñáéíóú]{1,}[\s]*)+" value="{{$playerSelect && $statusReference? $playerSelect->reference : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Grupo</label>
                        <label class="content-select content-select">
                            @if(Auth::guard('admin')->id() == 1)
                                <select class="addMargin" name="group" id="group" required>
                            @else
                                <select class="addMargin" name="group" id="group" disabled>
                            @endif
                                @if($playerSelect || Auth::guard('admin')->id() != 1)
                                    <option value="" disabled>Seleccionar</option>
                                @else
                                    <option value="" disabled selected>Seleccionar</option>
                                @endif

                                @foreach($groups as $group)

                                    @if($playerSelect && $playerSelect->group && $playerSelect->group->id == $group->id || (Auth::guard('admin')->id() != 1 && Auth::guard('admin')->id() == $group->id))
                                        <option value="{{$group->id}}" selected>{{$group->nameGroup}}</option> 
                                    @else
                                        <option value="{{$group->id}}">{{$group->nameGroup}}</option> 
                                    @endif 
                                @endforeach
                            </select>
                        </label> 
                    </div>
                    <div class="row justify-content-center align-items-center minh-10">
                        <h4><Strong>Acceso del Juego</Strong></h4>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Billetera:</label>
                        <div class="col-sm-6">
                            @if($playerSelect)
                                <input class="form-control" type="text" name="wallet" autocomplete="off" minlength="20" placeholder="Billetera" value="ronin:{{$playerSelect->wallet}}" autocomplete="off" readonly required>
                            @else
                                <input class="form-control" type="text" name="wallet" autocomplete="off" minlength="20" placeholder="Billetera" autocomplete="off" required>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Código QR</label>
                        <div class="fileinput fileinput-new text-center" style="padding-left:10px" data-provides="fileinput">
                            <div class="fileinput-preview fileinput-exists thumbnail img-raised">
                                @if($playerSelect)
                                    <img src="{{asset('storage/'.$playerSelect->urlCodeQr)}}">
                                @endif
                            </div>
                            @if($playerSelect)
                                <input type="hidden" name="urlPrevius" value="{{$playerSelect->urlCodeQr}}">
                                <input type="hidden" name="playerSelect" value="{{$playerSelect}}">
                            @endif
                            <div>
                                <span class="btn btn-raised btn-round btn-default btn-file">
                                    @if($playerSelect)
                                        <input type="file" name="codeQr" id="codeQr"/>
                                    @else
                                        <input type="file" name="codeQr" id="codeQr" required/>
                                    @endif
                                </span>
                                <a href="" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i>Eliminar</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Correo Electrónico </label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="emailGame" name="emailGame" placeholder="joedoe@hotmail.com" value="{{$playerSelect? $playerSelect->emailGame : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="passwordGame" id="passwordGame" placeholder="Contraseña" autocomplete="off" required aria-label="Password" aria-describedby="basic-addon2">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-fab btn-round btnEye">
                                    <i class="material-icons">visibility</i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <label class="col-8 col-form-label">(La contraseña se enviará al jugador por medio de Correo Electrónico)</label>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Fecha de reclamo </label>
                        <div class="col-sm-4">
                            @php
                                use Carbon\Carbon;
                            @endphp
                            <div class="input-daterange input-group" id="datepicker-admin">
                                <input type="text" class="form-control" name="dateClaim" placeholder="Fecha de Reclamo" value="{{$playerSelect? Carbon::parse(str_replace('/','-',$playerSelect->dateClaim))->format('d/m/Y') : Carbon::now()->format('d/m/Y')}}" autocomplete="off" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <label class="col-8 col-form-label">Si el becado es nuevo selecciona la fecha de hoy, sino debe seleccionar el dia 15 o ultimo.  </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="marginAuto">
                    <button type="submit" class="submit btn btn-bottom" id="submit_player" form="formPlayer">Guardar Becado</button>
                </div>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var statusReference = $('#reference').val();

        if(statusReference != "Otro"){
            $("#referenceOther").prop('required',false);
            $('#referenceOther').addClass('referenceHide');
        }else{
            $("#referenceOther").prop('required',true);
            $('#referenceOther').removeClass('referenceHide');
        }

        $(".btnEye").click(function () { 
            var x = document.getElementById("passwordGame");
            if (x.type === "password"){
                x.type = "text";
                $(this).find('i').text("visibility_off");
            } else {
                x.type = "password";
                $(this).find('i').text("visibility");
            }
        });

        $(".fileinput-exists").click(function (e) { 
            e.preventDefault();
            $("#codeQr").prop('required',true);
        });

        $(".btn-file").click(function (e) { 
            $("#codeQr").prop('required',true);
        });

        $("#reference").on('change', function() {
            selectReference = $(this).val();
            if(selectReference == "Otro"){
                $("#referenceOther").prop('required',true);
                $('#referenceOther').removeClass('referenceHide');
            }
            else{
                $("#referenceOther").prop('required',false);
                $('#referenceOther').addClass('referenceHide');
            }
        });

        $('#datepicker-admin').datepicker({
            orientation: "top auto",
            language: "es",
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });

    });
</script>