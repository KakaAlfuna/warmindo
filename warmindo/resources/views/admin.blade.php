@extends('layouts.admin')

@section('header', 'Dashbord')

@section('css')
@endsection

@section('content')
<div class="container" id="controller">
    <div class="row">
        <div class="col-lg-12 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $banyakTransaksi }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
            <a href="{{ url('transaction') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $banyakUser }}</h3>
                    <p>Total User</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $banyakMenu }}</h3>
                    <p>Total Menu </p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
            <a href="{{ url('menus') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $banyakMinuman }}</h3>
                    <p>Total Minuman</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
            <a href="{{ url('drinks') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!-- BAR CHART -->
    <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Bar Chart</h3>  
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var data_bar = '{!! json_encode($data_bar) !!}';
 
    $(function () {
      var areaChartData = {
        labels: ['January', 'February','march','April','May','June','July','Agustus','September','October','November','Desember'],
        datasets: JSON.parse(data_bar),
      }
      var barChartCanvas = $('#barChart').get(0).getContext('2d')
      var barChartData = $.extend(true, {}, areaChartData)
      var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false
      }
  
      new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
      })
    })
</script>
@endsection
