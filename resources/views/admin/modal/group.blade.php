<!--- Modal Player-->
<div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg has-success">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <Strong>
                        @if($groupSelect) 
                            Modificar Grupo 
                        @else 
                            Nuevo Grupo 
                        @endif
                    </Strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body">
                <form id="formGroup" action="{{route('admin.formGroup')}}" method="post" autocomplete="off">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Nombre</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" minlength="3" placeholder="Joe Doe" pattern="([a-zA-ZÁÉÍÓÚñáéíóú]{1,}[\s]*)+" value="{{$groupSelect? $groupSelect->name : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Nombre Grupo</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="nameGroup" name="nameGroup" minlength="3" placeholder="Joe Doe" pattern="([a-zA-ZÁÉÍÓÚñáéíóú]{1,}[\s]*)+" value="{{$groupSelect? $groupSelect->name : ''}}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Correo Electrónico</label>
                        <div class="col-sm-6">
                            @if($groupSelect)
                                <input type="email" class="form-control" id="email" name="email" placeholder="joedoe@hotmail.com" value="{{ $groupSelect->email}}" autocomplete="off" readonly>
                            @else
                            <input type="email" class="form-control" id="email" name="email" placeholder="joedoe@hotmail.com" autocomplete="off" required>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Contraseña</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="off" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-fab btn-round btnEye">
                                    <i class="material-icons">visibility</i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <label class="col-8 col-form-label">(La contraseña se enviará al admnistrador del grupo por medio de Correo Electrónico)</label>
                    </div>
                    @if($groupSelect)
                        <input type="hidden" name="groupSelect" value="{{$groupSelect}}">
                        <input type="hidden" id="groupId" name="groupId" value="{{$groupSelect->id}}">
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <div class="marginAuto">
                    <button type="submit" class="submit btn btn-bottom" id="submit_group" form="formGroup">Guardar Grupo</button>
                </div>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".btnEye").click(function () { 
            var x = document.getElementById("password");
            if (x.type === "password"){
                x.type = "text";
                $(this).find('i').text("visibility_off");
            } else {
                x.type = "password";
                $(this).find('i').text("visibility");
            }
        });

        $("#submit_group").click(function (e) { 
            e.preventDefault();
            if($("#name").val() <3 )
                alertify.error('Ingrese un nombre correctamente!');
            else if($("#nameGroup").val() <3 )
                alertify.error('Ingrese un nombre del grupo correctamente!');
            else if($("#email").val() <5 )
                alertify.error('Ingrese un correo electrónico correctamente!');
            else if($("#password").val() <5 )
                alertify.error('Ingrese una contraseña correctamente!');
            else
                $.ajax({
                    url: "{{route('admin.verifyGroup')}}", 
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: $("#groupId").val(),
                        email: $("#email").val(),
                        nameGroup: $("#nameGroup").val(),
                    },
                    type: "POST",
                }).done(function(data){
                    if(data.statusCode == 201 && data.listErrorLength == 0)
                        $( "#formGroup" ).submit();
                    else
                        data.listError.forEach(function(item, index) {
                            alertify.error(item);
                        });
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                });
        });

    });

</script>