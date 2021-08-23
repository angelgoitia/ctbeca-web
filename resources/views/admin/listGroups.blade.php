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
                <button type='button' class="btn btn-bottom" onclick="editGroup(0)"><i class="material-icons">edit</i> Crear Grupo</button>
            </div>
        </div>
        <div class="tableShow">
            <table id="table_id" class="table table-bordered display" style="width:100%;">
                <thead>
                    <tr class="table-title">
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Nombre Grupo</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                    <tr>
                        <th scope="row">{{ $group->id }}</th>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->nameGroup }}</td>
                        <td>{{ $group->email }}</td>
                        <td>
                            <botton class="btn btn-bottom" onclick="editGroup({{$group->id}})" rel="tooltip" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="material-icons">edit</i></botton>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="showGroup"></div>
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Grupos",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Grupos",
                    "infoFiltered": "(Filtrado de _MAX_ total Grupos)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Grupos",
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

        function editGroup(id)
        {
            $.ajax({
                url: "{{route('admin.editGroup')}}", 
                data: {"_token": "{{ csrf_token() }}", "id" : id},
                type: "POST",
            }).done(function(data){
                $('#showGroup').html(data.html);
                $('#groupModal').modal('show'); 
            }).fail(function(result){
                alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
                $('#groupModal').modal('hide'); 
                $('#showGroup').html();
            });
        }

    </script>
</body>
</html>