<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTBeca</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}">
    @include('admin.bookshop')
</head>
<body class="body-admin">
@include('auth.menu')
    <div class="loader"></div>
    <div class="main-panel">
      @include('auth.navbar')

        <div class="row justify-content-center align-items-center minh-10 dataProfile">
            <div class="col-6" style="text-align: center;">
                <div class="title"><label><strong>Datos Personales</strong></label></div>
                    <div style="text-align: left; padding-left: 40px;">
                        <label><strong>Nombre : </strong>{{$player->name}}</label> <br> 
                        <label><strong>Correo Electrónico: </strong>{{$player->email}}</label> <br> 
                        <label><strong>Teléfono: </strong>{{$player->phone}}</label> <br> 
                        <label><strong>Telegram: </strong>{{$player->telegram}}</label> <br>
                        <label><strong>Referencia: </strong>{{$player->reference}}</label> <br> 
                    </div>

                    <div class="title"><label><strong>Acceso Axies Infinity</strong></label></div>
                    <img src="{{asset('storage/'.$player->urlCodeQr)}}" class="rounded float-start" width="150px" height="150px">

                    <div style="text-align: left; padding-left: 40px;">
                        <label><strong>Correo Electrónico: </strong>{{$player->emailGame}}</label> <br> 
                        <label><strong>Billetera: </strong>{{$player->wallet}}</label> <br> 
                    </div>

                    <div class="title"><label><strong>Axies</strong></label></div>
                    @foreach($player->animals as $axie)
                        <div class="card mb-3 mx-auto" style="max-width: 400px;">
                            <div class="row g-0">
                                <div class="col-md-5" style="background-color: white;">
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
            </div>
        </div>

    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        
        $(".main-panel").perfectScrollbar('update');

    </script>
</body>
</html>