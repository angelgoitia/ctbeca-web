<!--- Modal Player-->
<div class="modal fade" id="slpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg has-success">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($selectPlayer)
                        Modificar control
                    @else
                        Crear nuevo control
                    @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body">
                <form id="formSLP" action="{{route('admin.formSLP')}}" method="post" autocomplete="off">
                    @if($selectPlayer)
                        <input type="hidden" name="selectPlayer" value="{{$selectPlayer}}">
                    @endif

                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Becado</label>
                        <label class="content-select content-select">
                            @if($selectPlayer)
                                <select class="addMargin" name="playerId" id="playerId" disabled required>
                                    <option value="0" disabled>Seleccionar</option>
                            @else
                                <select class="addMargin" name="playerId" id="playerId" required>
                                    <option value="0" disabled selected>Seleccionar</option>
                            @endif
                            
                                @foreach($players as $player)
                                    @if($selectPlayer && $selectPlayer->id == $player->id)
                                        <option value="{{$player->id}}" selected>{{$player->name}}</option>
                                    @else
                                        <option value="{{$player->id}}">{{$player->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Fecha</label>
                        <div class="col-sm-6">
                            @php use Carbon\Carbon; @endphp
                            @if($selectPlayer)
                                <input type="text" class="form-control" id="datepicker-SLP" name="date" value="{{$selectPlayer? Carbon::parse(str_replace('/','-',$selectPlayer->totalSLP[0]->date))->format('d/m/Y') : Carbon::now()->format('d/m/Y')}}" autocomplete="off" readonly required>
                            @else
                                <input type="text" class="form-control" id="datepicker-SLP" name="date" value="{{$selectPlayer? Carbon::parse(str_replace('/','-',$selectPlayer->totalSLP[0]->date))->format('d/m/Y') : Carbon::now()->format('d/m/Y')}}" autocomplete="off" required>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Total Diaria</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="totalDaily" name="totalDaily" min="0" pattern="([0-9])+" autocomplete="off" value="{{$selectPlayer? $selectPlayer->totalSLP[0]->daily : 0}}" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="marginAuto">
                    <button id="btn-submit-slp" type="submit" class="submit btn btn-bottom">Guardar</button>
                </div>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script> 
     
    $(document).ready( function () {
        
        if(statusDate){
            var date = moment(startDate, "YYYY-MM-DD");
            $('#datepicker-SLP').datepicker({
                orientation: "bottom auto",
                startDate: date.toDate(),
                endDate: new Date(),
                language: "es",
                autoclose: true,
                todayHighlight: true
            });
        }

        $('#playerId').on('change', function() {
            var selectplayerId = $('#playerId').val();
            $.each(players, function( index, value ) {
                if(value.id == selectplayerId){
                    $('#datepicker-SLP').datepicker("destroy");
                    var date = moment(value.dateClaim, "YYYY-MM-DD").add(1, 'days');
                    console.log(date.toDate());
                    $('#datepicker-SLP').datepicker({
                        orientation: "bottom auto",
                        startDate: date.toDate(),
                        endDate: new Date(),
                        language: "es",
                        autoclose: true,
                        todayHighlight: true
                    });
                    return false;
                }    
            });
        });

        $("#btn-submit-slp").click(function (e) { 
            e.preventDefault();
            if( !($("#playerId").val() >0 ))
                alertify.error('Debe seleccionar un Becado!');
            else if (! ($("#totalDaily").val() >= 0) )
                alertify.error('Ingrese el total correctamente');
            else{
                $( ".loader" ).fadeIn("slow"); 
                if(statusDate)
                    $.ajax({
                        url: "{{route('admin.verifySLP')}}", 
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: $("#playerId").val(),
                            date: $("#datepicker-SLP").val(),
                            totalDaily: $("#totalDaily").val(),
                        },
                        type: "POST",
                    }).done(function(data){
                        if(data.statusCode == 201)
                            $( "#formSLP" ).submit();
                        else{
                            $( ".loader" ).fadeOut("slow");
                            alertify.error('El becado seleccionado ya tiene registrado el total diaria en la fecha ingresado'); 
                        }
                    }).fail(function(result){
                        $( ".loader" ).fadeOut("slow");
                        alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                    });
                else
                    $( "#formSLP" ).submit();
            }
        });

    });
    $(".main-panel").perfectScrollbar('update');
</script>