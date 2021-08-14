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
    <script src="{{ asset('js/locales/bootstrap-datepicker.es.min.js') }}"></script>
</head>
<body class="body-admin">
@include('auth.menu')
    <div class="loader"></div>
    <div class="main-panel">
      @include('auth.navbar')
        <div class="justify-content-center" id="row">
            <div class="col-10">
                <div class="card card-filter">
                    <div class="card-header">
                        Filtro:
                    </div>
                    <div class="card-body has-success" style="margin:15px;">
                        <form id="search-form" class="contact-form" method='POST' action="{{route('admin.listDaily')}}">   
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Rango de Fecha</label>
                                @php
                                    use Carbon\Carbon;
                                    if($orderBy == "ASC")
                                        $writeDate = $startDate;
                                    else
                                        $writeDate = $endDate;
                                    $status = false;
                                @endphp
                                <div class="col">
                                    <div class="input-daterange input-group" id="datepicker-admin">
                                    <input type="text" class="form-control" name="startDate" placeholder="Fecha Inicial" value="{{Carbon::parse(str_replace('/','-',$startDate))->format('d/m/Y')}}" autocomplete="off"/>
                                        <span class="input-group-addon"> Hasta </span>
                                        <input type="text" class="form-control" name="endDate" placeholder="Fecha Final" value="{{Carbon::parse(str_replace('/','-',$endDate))->format('d/m/Y')}}" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Orden Fecha</label>
                                <label class="content-select">
                                    <select class="addMargin" name="orderBy" id="orderBy">
                                        @if($orderBy == "ASC")
                                            <option value="ASC" >Ascendiente</option>
                                            <option value="DESC">Descendiente</option>
                                        @else
                                            <option value="ASC" >Ascendiente</option>
                                            <option value="DESC" selected>Descendiente</option>
                                        @endif
                                    </select>
                                </label>
                            </div>

                            <div class="row">&nbsp;</div>

                            <div class="row justify-content-center">
                                <div class="col-6">
                                    <button type="submit" class="submit btn btn-bottom">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row btn-newSLP">
            <div class="col">
                <button type='button' class="btn btn-bottom" onclick="newSlp()"><i class="material-icons">edit</i> Crear Nueva control</button>
            </div>
        </div>
        <div class="tableShow">
            <table class="table table-rotate">
                <thead>
                    <tr>
                        <th scope="col" class="table-title addPadding">Fecha</th>
                        @foreach($playersAll as $player)
                            <th scope="col" class="notBorder"><div class="outerDiv" ><div class="innerDiv">{{$player->name}}</div></div></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if($orderBy == "ASC")
                        @while($writeDate <= $endDate)
                        <tr>
                            <th>{{$writeDate}}</th>
                            @foreach($playersAll as $player)
                                @php 
                                    $status = false; 
                                @endphp
                                
                                @foreach($player->totalSlp as $item)
                                    @if(Carbon::parse($item->date)->format('Y-m-d') == Carbon::parse($writeDate)->format('Y-m-d') )
                                        <td>{{$item->daily}} SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                        @php 
                                            $status = true; 
                                        @endphp
                                    @endif
                                @endforeach

                                @if(!$status)
                                    <td>0 SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                @endif

                            @endforeach
                            
                            @php
                                $writeDate = Carbon::parse($writeDate)->addDay()->format('Y-m-d');
                            @endphp
                        </tr>
                        @endWhile
                    @else
                        @while($writeDate >= $startDate)
                        <tr>
                            <th>{{$writeDate}}</th>
                            @foreach($playersAll as $player)
                                @php
                                    $status = false;
                                @endphp
                                
                                @foreach($player->totalSlp as $item)
                                    @if(Carbon::parse($item->date)->format('Y-m-d') == Carbon::parse($writeDate)->format('Y-m-d') )
                                        <td>{{$item->daily}} SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                        @php
                                            $status = true;
                                        @endphp
                                    @endif
                                @endforeach

                                @if(!$status)
                                    <td>0 SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                @endif

                            @endforeach
                            
                            @php
                                $writeDate = Carbon::parse($writeDate)->subDays()->format('Y-m-d');
                            @endphp
                        </tr>
                        @endWhile
                    @endif
                </tbody>
            </table>
        </div>
        <div id="newFormSLP"></div>
    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        
        $(document).ready( function () {

            $('#datepicker-admin').datepicker({
                orientation: "bottom auto",
                language: "es",
                endDate: new Date(),
                language: "es",
                autoclose: true,
                todayHighlight: true
            });

        });
        $(".main-panel").perfectScrollbar('update');

        function newSlp()
        {
            $.ajax({
                url: "{{route('admin.newSLP')}}", 
                data: {"_token": "{{ csrf_token() }}",},
                type: "GET",
            }).done(function(data){
                $('#newFormSLP').html(data.html);
                $('#slpModal').modal('show'); 
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#slpModal').modal('hide'); 
                $('#newFormSLP').html();
            });
        }
    </script>
</body>
</html>