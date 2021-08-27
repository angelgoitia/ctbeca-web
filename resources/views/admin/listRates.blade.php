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
        <div class="justify-content-center has-success marginTopAdmin" id="row">
            @include('admin.widget.rate')
        </div>
        <div class="tableShow">
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Grupos</th>
                        <th scope="col">Signo</th>
                        <th scope="col">SLP</th>
                        <th scope="col"> </th>
                        <th scope="col">Porcentaje</th>
                        <th scope="col">Signo</th>
                        <th scope="col">SLP</th>
                        <th scope="col"> </th>
                        <th scope="col">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rates as $rate)
                    <tr>
                        <th scope="row">{{ $rate->id }}</th>
                        <td>{{ $rate->admin->nameGroup }}</td>
                        <td> <img src="{{ asset('images/less_equal.png') }}" width="20px"> </td>
                        <td>{{ $rate->lessSlp }} </td>
                        <td><img src="{{ asset('images/right-arrow.png') }}" width="30px"></td>
                        <td>{{$rate->lessPercentage}} %</td>
                        <td> <img src="{{ asset('images/greater.png') }}" width="20px"> </td>
                        <td>{{ $rate->greaterSlp }} </td>
                        <td><img src="{{ asset('images/right-arrow.png') }}" width="30px"></td>
                        <td>{{$rate->greaterPercentage}} %</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @include('admin.bookshopBottom')
    <script> 
        $( ".loader" ).fadeOut("slow"); 
        var statusMenu = "{{$statusMenu}}";
        
        $(document).ready( function () {

            $('#table_id').DataTable({
                "scrollX": true,
                order: [[ 1, "asc" ]],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Tasas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Tasas",
                    "infoFiltered": "(Filtrado de _MAX_ total Tasas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Tasas",
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