<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoct.svg') }}" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>CTBeca</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  @include('bookshop')
  @include('admin.bookshop')
</head>

<body class="">
  @include('auth.menu')
    <div class="main-panel">
      @include('admin.navbar')
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total</p>
                  <h3 class="card-title">{{$totalSlpToday}} SLP</h3>
                  <div class="row justify-content-between">
                    <div class="col" style="text-align: center;">
                      @if($priceSlp == 0)
                        <label>Inténtalo de nuevo más tarde</label>
                      @else
                        <label>1 SLP <img src="{{ asset('images/right-arrow.png') }}" width="20px"> $ {{$priceSlp}} </label>
                      @endif
                    </div>
                    <div class="col">
                      <label> $ {{$totalSlpToday * $priceSlp}} </label>
                    </div>
                  </div>

                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">date_range</i> Hoy
                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total</p>
                  <h3 class="card-title">{{$totalSlpYesterday}} SLP</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Ayer
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total</p>
                  <h3 class="card-title">{{$totalSlpUnclaimed}} SLP</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Sin Reclamar
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total Manager</p>
                  <h3 class="card-title">{{$totalSlpManager}} SLP</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Global
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total Becado</p>
                  <h3 class="card-title">{{$totalSlpPlayer}} SLP</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Global
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-secondary card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total Producidos</p>
                  <h3 class="card-title">{{$totalSlpAll}} SLP</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Global
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-chart">
                <div class="card-header card-header-success">
                  <div class="ct-chart" id="dailySlpChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">SLP Diarias</h4>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> Ultimos 15 Dias
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('admin.bookshopBottom')
  
  <script>
    var statusMenu = "{{$statusMenu}}";
    var listDay=[];
    
    $.ajax({
        url: "{{route('admin.dataGraphic')}}", 
        data: {"_token": "{{ csrf_token() }}", "player_id" : "{{$idPlayer}}"},
        type: "POST",
        dataType: 'json',
    }).done(function(data){
      listDay = data;
      updateData();
    }).fail(function(result){
      alertify.error('Sin Conexión, intentalo de nuevo mas tardes!');
    });
    
    md.initDashboardPageCharts();

    function updateData()
    {

        var date=[];
        var dayTotalSlp=[];

        $.each(listDay, function(i, item) {
            date.push(item.day);
            dayTotalSlp.push(item.totalSlp);
        });


        dataDailySlpChart = {
          labels: date,
          series: [
            dayTotalSlp
          ]
        }; 

        var dailySlpChart = new Chartist.Line('#dailySlpChart', dataDailySlpChart, optionsDailySlpChart);
        md.startAnimationForLineChart(dailySlpChart);

    }
  </script>
</body>

</html>