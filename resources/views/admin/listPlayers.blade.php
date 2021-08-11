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
        <div class="row buttonCreatePlayers">
            <div class="col">
                <button type='button' class="btn btn-bottom" onclick="showPlayer(0)"><i class="material-icons">edit</i> Crear Becados</button>
            </div>
        </div>
        <div class="tableShow">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-danger">
                        <strong style="color:white !important;">Error: </strong> {{Session::get('message') }}
                    </div>
                @endif
            </div>
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Jugador</th>
                        <th scope="col">Telegram</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Referencia</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($playersAll as $player)
                    <tr>
                        <th scope="row">{{ $player->id }}</th>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->telegram }}</td>
                        <td>{{ $player->email }}</td>
                        <td>{{ $player->phone }}</td>
                        <td>{{ $player->reference }}</td>
                        <td>
                            <botton class="btn btn-bottom" onclick="showPlayer({{$player->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="material-icons">edit</i></botton>
                            <botton class="btn btn-bottom" onclick="showAnimals({{$player->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Ver Animales"><i class="material-icons">visibility</i></botton>
                        </td>
                    </tr>
                    @endforeach
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
                order: [[ 1, "asc" ]],
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

        function showPlayer(id)
        {
            $.ajax({
                url: "{{route('admin.showPlayer')}}", 
                data: {"_token": "{{ csrf_token() }}", "id" : id},
                type: "POST",
            }).done(function(data){
                $('#showPlayer').html(data.html);
                $('#playerModal').modal('show'); 
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#playerModal').modal('hide'); 
                $('#showPlayer').html();
            });
        }
    </script>
</body>
</html>