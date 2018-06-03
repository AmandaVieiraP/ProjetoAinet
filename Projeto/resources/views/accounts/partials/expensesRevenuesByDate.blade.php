@extends('master')
@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light
">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li>
        <a class="nav-link" href="{{ route('dashboard',['user'=>$id]) }}"><strong>Go Back</strong></a>
      </li>
    </ul>
  </div>
</nav>

<br>

@if (count($totalExpenseRevenue))

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-5">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Value (€)</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
        @foreach($totalExpenseRevenue as $expRev)
        <tr>
            <td>
                {{ $expRev->name }}
            </td>
            <td>
                {{ $expRev->total }}
            </td>
            <td>
                {{ $expRev->type }}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>

   <div class="col-sm-6">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
      ['Category','Total Value(€)'],

        @foreach($totalExpenseRevenue as $expRev)
            ['{{$expRev->name}} ({{$expRev->type}})',{{$expRev->total}}],
        @endforeach
      ]);

      var options = {
        chart: {
        },
        bars: 'horizontal' 
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_expenses'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
        }
        </script>

        <div id="barchart_expenses" style="width: 600px; height: 250px;"></div>
    </div>



}
  </div>
</div>
@else 
   <h5>There's no information to show</h5>

@endif
@endsection