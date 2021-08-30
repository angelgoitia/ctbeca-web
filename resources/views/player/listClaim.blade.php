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
                        <form id="search-form" class="contact-form" method='POST' action="{{route('player.listClaim')}}">   
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
                        <th scope="col">Fecha</th>
                        <th scope="col">Acumulado</th>
                        <th scope="col">Total Manager</th>
                        <th scope="col">Total Becado</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($listDate as $date)
                    <tr>
                        <td>{{$date}}</td>
                        @foreach($player->claims as $item)
                            @if(Carbon::parse($item->date)->format('Y-m-d') == Carbon::parse($date)->format('Y-m-d') )
                                <td>{{$item->total}}</td>
                                <td>{{$item->totalManager}}</td>
                                <td>{{$item->totalPlayer}}</td>
                                @php
                                    $status = true;
                                @endphp
                            @endif
                        @endforeach

                        @if(!$status)
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
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

        });
        $(".main-panel").perfectScrollbar('update');


    </script>
</body>
</html>