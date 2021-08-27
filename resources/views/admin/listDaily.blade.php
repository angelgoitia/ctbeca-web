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
                            @php
                                use Carbon\Carbon;
                                $writeDate = $startDate;
                                $status = false;
                                $totalUnclaim = 0;
                                $totalPlayer = 0;
                                $yearInitial = Carbon::now()->setYear(2021)->format('Y');
                                $yearFinal = Carbon::now()->setYear(Carbon::now()->format('Y'))->format('Y');
                            @endphp

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Fecha</label>
                                <label class="content-select">
                                    <select class="addMargin" name="monthDate" id="monthDate">
                                        @foreach($months as $key => $month)
                                            @if($key == $monthDate-1)
                                                <option value="{{$key}}" selected>{{$month}}</option>
                                            @else
                                                <option value="{{$key}}">{{$month}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </label>
                                <label class="content-select">
                                    <select class="addMargin" name="yearDate" id="yearDate">
                                        @while($yearInitial <= $yearFinal)
                                            @if($yearInitial == $yearDate)
                                                <option value="{{$yearInitial}}" selected>{{$yearInitial}}</option>
                                            @else
                                                <option value="{{$yearInitial}}">{{$yearInitial}}</option>
                                            @endif
                                            @php $yearInitial += 1; @endphp
                                        @endwhile
                                    </select>
                                </label>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Mostrar</label>
                                <label class="content-select">
                                    <select class="addMargin" name="statusBiweekly" id="statusBiweekly">
                                        @if($statusBiweekly)
                                            <option value="true" selected>Quincenal</option>
                                            <option value="false">Último</option>
                                        @else
                                            <option value="true" >Quincenal</option>
                                            <option value="false" selected>Último</option>
                                        @endif
                                    </select>
                                </label>
                                @if(Auth::guard('admin')->id() == 1)
                                <label class="col-2"></label>
                                <label class="col-sm-2 col-form-label">Grupo</label>
                                <label class="content-select">
                                    <select class="addMargin" name="groupId" id="groupId">

                                        @if($groupId == 0)
                                            <option value="0" selected>Todos</option>
                                        @else
                                            <option value="0" >Todos</option>
                                        @endif

                                        @foreach($groups as $group)
                                            @if($groupId == $group->id)
                                                <option value="{{$group->id}}" selected>{{$group->nameGroup}}</option>
                                            @else
                                                <option value="{{$group->id}}" >{{$group->nameGroup}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </label>
                                @endif
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
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">Becados</th>
                        @while($writeDate <= $endDate)
                            <th scope="col">{{Carbon::parse($writeDate)->format('d')}}</th>
                            @php
                                $writeDate = Carbon::parse($writeDate)->addDay()->format('Y-m-d');
                            @endphp
                        @endWhile
                        <th scope="col">Acumulado</th>
                        <th scope="col">Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $writeDate = $startDate;
                    @endphp
                    @foreach($playersAll as $player)
                        <tr>
                            <td>{{$player->name}}</td>
                            @while($writeDate <= $endDate)

                                @foreach($player->totalSlp as $item)
                                    @if(Carbon::parse($item->date)->format('Y-m-d') == Carbon::parse($writeDate)->format('Y-m-d') )
                                        <td>{{$item->daily}} SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                        @php
                                            $status = true;
                                            $totalUnclaim += $item->daily;
                                            $totalPlayer += $item->totalPlayer;
                                        @endphp
                                    @endif
                                @endforeach

                                @if(!$status)
                                    <td>0 SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                @endif
                                @php
                                    $writeDate = Carbon::parse($writeDate)->addDay()->format('Y-m-d');
                                    $status = false;
                                @endphp
                            @endwhile
                            <td>{{$totalUnclaim}}</td>
                            <td>{{$totalPlayer}}</td>
                            @php
                                $writeDate = $startDate;
                                $status = false;
                                $totalUnclaim = 0;
                                $totalPlayer = 0;
                            @endphp
                        </tr>
                    @endforeach
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

            $('#table_id').DataTable({
                "scrollX": true,
                "ordering": false,
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Becados",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Becados",
                    "infoFiltered": "(Filtrado de _MAX_ total Becados)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Becados",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
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