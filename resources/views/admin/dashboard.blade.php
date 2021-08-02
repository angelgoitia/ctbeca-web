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
      @include('auth.navbar')
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-7 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <img src="{{ asset('images/SLP.png') }}" width="50px">
                  </div>
                  <p class="card-category">Total de SLP</p>
                  <h3 class="card-title">{{$totalSlpToday}}</h3>
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
                  <p class="card-category">Total de SLP</p>
                  <h3 class="card-title">{{$totalSlpYesterday}}</h3>
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
                  <p class="card-category">Total de SLP</p>
                  <h3 class="card-title">{{$totalSlpWeek}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Ultimos 6 Dias
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
                  <div class="ct-chart" id="dailySalesChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">LSP Diarias</h4>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> Ultimos 6 Dias
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
            date.push(item.dia);
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