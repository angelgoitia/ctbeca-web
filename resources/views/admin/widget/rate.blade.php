<form id="formRate" action="{{route('admin.formRate')}}" method="post" autocomplete="off">
    @csrf    
    <h2 style="text-align: center; padding-bottom: 10px;">Tasa</h2>
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">SLP</label>
        <div class="col">
            <img src="{{ asset('images/less_equal.png') }}" width="20px">
        </div>
        <div class="col">
            <input type="num" class="form-control" id="lessSlp" name="lessSlp" value="{{$rate? $rate->lessSlp : ''}}" placeholder="75" autocomplete="off" required>
        </div>
        <div class="col">
            <img src="{{ asset('images/right-arrow.png') }}" width="30px">
        </div>
        <div class="col">
            <input type="text" class="form-control" id="lessPercentage" name="lessPercentage" value="{{$rate? $rate->lessPercentage : ''}}" placeholder="porcentaje" autocomplete="off" required>
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
            <input type="num" class="form-control" id="greaterSlp" name="greaterSlp" value="{{$rate? $rate->greaterSlp : ''}}" placeholder="75" autocomplete="off" readonly required>
        </div>
        <div class="col">
            <img src="{{ asset('images/right-arrow.png') }}" width="30px">
        </div>
        <div class="col">
            <input type="text" class="form-control" id="greaterPercentage" name="greaterPercentage" value="{{$rate? $rate->greaterPercentage : ''}}" placeholder="porcentaje" autocomplete="off" required>
        </div>
        <div class="col colPercentage">
            <label> <strong>%</strong> </label>
        </div>
    </div>

    <div class="row">&nbsp;</div>

    <div class="row justify-content-center">
        <div class="col-6">
            <button type="submit" class="submit btn btn-bottom" id="btn-submit-rate">Guardar</button>
        </div>
    </div>
</form>

<script>
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
</script>