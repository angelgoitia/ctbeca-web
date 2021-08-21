<!--- Modal Player-->
<div class="modal fade" id="slpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg has-success">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Crear nuevo control de SLP
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body">
                <form id="formSLP" action="{{route('admin.formSLP')}}" method="post" autocomplete="off">
                    @csrf

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Becado</label>
                        <label class="content-select content-select">
                            <select class="addMargin" name="playerId" id="playerId" required>
                                <option value="0" disabled selected>Seleccionar</option>
                                @foreach($players as $player)
                                    <option value="{{$player->id}}">{{$player->name}}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Fecha</label>
                        <div class="col-sm-6">
                            @php use Carbon\Carbon; @endphp
                            <input type="text" class="form-control" id="datepicker-SLP" name="date" value="{{Carbon::now()->format('Y-m-d')}}" autocomplete="off" readonly required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Total Diaria</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="total" name="total" min="0" pattern="([0-9])+" autocomplete="off" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="marginAuto">
                    <button id="btn-submit-slp" type="submit" class="submit btn btn-bottom">Guardar SLP</button>
                </div>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script> 
     
    $(document).ready( function () {

        $("#btn-submit-slp").click(function (e) { 
            e.preventDefault();
            if( !($("#playerId").val() >0 ))
                alertify.error('Debe seleccionar un Becado!');
            else if (! ($("#total").val() > 0) )
                alertify.error('Ingrese el total correctamente');
            else
                $.ajax({
                    url: "{{route('admin.verifySLP')}}", 
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: $("#playerId").val(),
                        date: $("#datepicker-SLP").val(),
                        total: $("#total").val(),
                    },
                    type: "POST",
                }).done(function(data){
                    if(data.statusCode == 201)
                        $( "#formSLP" ).submit();
                    else
                        alertify.error('El becado seleccionado ya tiene registrado el total diaria en la fecha ingresado');
                }).fail(function(result){
                    alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                });
        });

    });
    $(".main-panel").perfectScrollbar('update');
</script>