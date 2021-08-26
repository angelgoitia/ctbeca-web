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
                        <form id="search-form" class="contact-form" method='POST' action="{{route('admin.listClaim')}}">   
                            @csrf
                            @php
                                use Carbon\Carbon;
                                $status = false;
                                $yearInitial = Carbon::now()->setYear(2021)->format('Y');
                                $yearFinal = Carbon::now()->setYear(Carbon::now()->format('Y'))->format('Y');
                            @endphp

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Fecha</label>
                                <label class="content-select">
                                    <select class="addMargin" name="monthDate" id="monthDate">
                                        @foreach($months as $key => $month)
                                            @php $index = $key +1; @endphp
                                            @if($index == $monthDate)
                                                <option value="{{$index}}" selected>{{$month}}</option>
                                            @else
                                                <option value="{{$index}}">{{$month}}</option>
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

        <div class="tableShow">
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">Becados</th>
                        <th scope="col">Acumulado</th>
                        <th scope="col">Total Manager</th>
                        <th scope="col">Total Becados</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($playersAll as $player)
                    <tr>
                        <td>{{$player->name}}</td>
                        @foreach($player->claims as $item)
                            @if(Carbon::parse($item->date)->format('Y-m-d') == Carbon::parse($selectDate)->format('Y-m-d') )
                                <td>{{$item->total}} SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                <td>{{$item->totalManager}} SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                <td>{{$item->totalPlayer}} SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                                @php
                                    $status = true;
                                @endphp
                            @endif
                        @endforeach

                        @if(!$status)
                            <td>0 SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                            <td>0 SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                            <td>0 SLP <img src="{{ asset('images/SLP.png') }}" width="20px"></td>
                        @endif
                        @php
                            $status = false;
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Reclamos",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Reclamos",
                    "infoFiltered": "(Filtrado de _MAX_ total Reclamos)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Reclamos",
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


    </script>
</body>
</html>