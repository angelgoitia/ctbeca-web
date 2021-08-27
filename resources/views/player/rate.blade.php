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
            <div id="formRate">
                @csrf    
                <h2 style="text-align: center; padding-bottom: 10px;">Tasa</h2>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">SLP</label>
                    <div class="col">
                        <img src="{{ asset('images/less_equal.png') }}" width="20px">
                    </div>
                    <div class="col">
                        <input type="num" class="form-control" id="lessSlp" name="lessSlp" value="{{$rate? $rate->lessSlp : ''}}" placeholder="75" autocomplete="off" readonly>
                    </div>
                    <div class="col">
                        <img src="{{ asset('images/right-arrow.png') }}" width="30px">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="lessPercentage" name="lessPercentage" value="{{$rate? $rate->lessPercentage : ''}}" placeholder="porcentaje" autocomplete="off" readonly>
                    </div>
                    <div class="col colPercentage">
                        <label> <strong>%</strong> </label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">SLP</label>
                    <div class="col">
                        <img src="{{ asset('images/greater.png') }}" width="20px">
                    </div>
                    <div class="col">
                        <input type="num" class="form-control" id="greaterSlp" name="greaterSlp" value="{{$rate? $rate->greaterSlp : ''}}" placeholder="75" autocomplete="off"  readonly>
                    </div>
                    <div class="col">
                        <img src="{{ asset('images/right-arrow.png') }}" width="30px">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="greaterPercentage" name="greaterPercentage" value="{{$rate? $rate->greaterPercentage : ''}}" placeholder="porcentaje" autocomplete="off" readonly>
                    </div>
                    <div class="col colPercentage">
                        <label> <strong>%</strong> </label>
                    </div>
                </div>
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