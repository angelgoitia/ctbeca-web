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

                                    $writeDate = $startDate;
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
                    @while($writeDate <= $endDate)
                    <tr>
                        <th>{{$writeDate}}</th>
                        @foreach($playersAll as $player)
                            @php
                                $status = false;
                                foreach($player->totalSlp as $item)
                                {
                                    if(Carbon::parse($item->created_at)->format('Y-m-d') == Carbon::parse($writeDate)->format('Y-m-d') )
                                    {
                                        echo "<td>".$item->total." LSP</td>";
                                        $status = true;
                                    }
                                }
                            @endphp

                            @if(!$status)
                                <td>0 LSP</td>
                            @endif

                        @endforeach
                        
                        @php
                            $writeDate = Carbon::parse($writeDate)->addDay()->format('Y-m-d');
                        @endphp
                    </tr>
                    @endWhile
                </tbody>
            </table>
        </div>
        <div id="showPlayer"></div>
    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        
        $(document).ready( function () {

            $('#table_id').DataTable({
                "scrollX": true,
                order: [[ 0, "asc" ]],
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

            $('#datepicker-admin').datepicker({
                orientation: "bottom auto",
                language: "es",
                autoclose: true,
                todayHighlight: true
            });

        });
        $(".main-panel").perfectScrollbar('update');
    </script>
</body>
</html>