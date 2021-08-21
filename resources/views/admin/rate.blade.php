<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTBeca</title>
    @include('bookshop')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bookshop/datatables.min.css') }}"/>
    @include('admin.bookshop')
    <script type="text/javascript" src="{{ asset('js/bookshop/datatables.min.js') }}"></script>
</head>
<body class="body-admin">
@include('auth.menu')
    <div class="loader"></div>
    <div class="main-panel">
        @include('auth.navbar')
        <div class="justify-content-center has-success marginTop" id="row">
            <form id="formRate" action="{{route('admin.formRate')}}" method="post" autocomplete="off">
                @csrf    
                <h2 style="text-align: center; padding-bottom: 10px;">Modificar Tasa</h2>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">SLP</label>
                    <div class="col">
                        <img src="{{ asset('images/less_equal.png') }}" width="20px">
                    </div>
                    <div class="col">
                        <input type="num" class="form-control" id="lessSlp" name="lessSlp" value="{{$rate->lessSlp? $rate->lessSlp : ''}}" placeholder="75" autocomplete="off" required>
                    </div>
                    <div class="col">
                        <img src="{{ asset('images/right-arrow.png') }}" width="30px">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="lessPercentage" name="lessPercentage" value="{{$rate->lessPercentage? $rate->lessPercentage : ''}}" placeholder="porcentaje" autocomplete="off" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">SLP</label>
                    <div class="col">
                        <img src="{{ asset('images/greater.png') }}" width="20px">
                    </div>
                    <div class="col">
                        <input type="num" class="form-control" id="greaterSlp" name="greaterSlp" value="{{$rate->greaterSlp? $rate->greaterSlp : ''}}" placeholder="75" autocomplete="off" readonly required>
                    </div>
                    <div class="col">
                        <img src="{{ asset('images/right-arrow.png') }}" width="30px">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="greaterPercentage" name="greaterPercentage" value="{{$rate->greaterPercentage? $rate->greaterPercentage : ''}}" placeholder="porcentaje" autocomplete="off" required>
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <div class="row justify-content-center">
                    <div class="col-6">
                        <button type="submit" class="submit btn btn-bottom" id="btn-submit-rate">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";

        $(document).ready(function () {
            $("#btn-submit-rate").click(function (e) { 
                e.preventDefault();
                if( !($("#lessSlp").val() >0 ))
                    alertify.error('Debe ingresar un número mayor!');
                else if (! ($("#lessPercentage").val() > 0) && ($("#lessPercentage").val() < 100) )
                    alertify.error('Ingrese el porcentaje correctamente');
                else if (! ($("#greaterPercentage").val() > 0) && ($("#greaterPercentage").val() < 100) )
                    alertify.error('Ingrese el porcentaje correctamente');
                else{
                    alertify.success('Guardado correctamente');
                    $( "#formRate" ).submit();
                }
            });

            $("#lessSlp").on('input', function(e) {
                e.preventDefault();
                var value = $(this).val();
                $("#greaterSlp").val(value);
            });
        });
        
        $(".main-panel").perfectScrollbar('update');


    </script>
</body>
</html>