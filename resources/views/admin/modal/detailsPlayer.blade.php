<!--- Modal Player-->
<div class="modal fade" id="showplayerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog has-success">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <Strong>
                        Becado
                    </Strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>              
            <div class="modal-body" style="text-align: center;">
                <div class="title"><label><strong>Datos Personales</strong></label></div>
                <div style="text-align: left;">
                    <label><strong>Nombre : </strong>{{$player->name}}</label> <br> 
                    <label><strong>Correo Electrónico: </strong>{{$player->email}}</label> <br> 
                    <label><strong>Teléfono: </strong>{{$player->phone}}</label> <br> 
                    <label><strong>Telegram: </strong>{{$player->telegram}}</label> <br> 
                    <label><strong>Referencia: </strong>{{$player->reference}}</label> <br> 
                </div>


                <div class="title"><label><strong>Acceso Axies Infinity</strong></label></div>
                <img src="{{asset('storage/'.$player->urlCodeQr)}}" class="rounded float-start" width="150px" height="150px">

                <div style="text-align: left;">
                    <label><strong>Usuario : </strong>{{$player->user}}</label> <br> 
                    <label><strong>Correo Electrónico: </strong>{{$player->emailGame}}</label> <br> 
                    <label><strong>Billetera: </strong>{{$player->wallet}}</label> <br> 
                </div>

                <div class="title"><label><strong>Axies</strong></label></div>
                @foreach($player->animals as $axie)
                    <div class="card mb-3 mx-auto" style="max-width: 400px;">
                        <div class="row g-0">
                            <div class="col-md-5" style="background-color: #242734;">
                                <img src="{{$axie->image}}" class="img-fluid rounded-start">
                            </div>
                            <div class="col">
                            <div class="card-body">
                                <h5 class="card-title" style="font-weight: bold;"><strong>{{$axie->name}}</strong></h5>
                                <p class="card-text" style="text-align: left;"><label>
                                    <label><strong>Código: </strong>{{$axie->code}}</label><br>
                                    <label><strong>Nomenclatura: </strong>{{$axie->nomenclature}}</label><br>
                                </p>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
