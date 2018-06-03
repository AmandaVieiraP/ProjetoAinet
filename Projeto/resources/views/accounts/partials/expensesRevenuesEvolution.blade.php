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

@if (count($evolutionExpenseRevenue))

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-5">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Value (€)</th>
                <th>Category</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
        @foreach($evolutionExpenseRevenue as $expRev)
        <tr>
            <td>
                {{ date('M Y', strtotime($expRev->date)) }}
            </td>
            <td>
                {{ $expRev->total }}
            </td>
            <td>
                {{ $expRev->name }}
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
      google.charts.load('current', {'packages':['annotationchart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Total');
        data.addColumn('string', 'Category');
        data.addColumn('string', 'Type');
        data.addRows([
           @foreach($evolutionExpenseRevenue as $expRev)
              [new Date("{{$expRev->date}}"),{{$expRev->total}}, '{{ $expRev->name }} ','{{ $expRev->type }}'],
          @endforeach
        ]);

        var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));

        var options = {
          displayAnnotations: true
        };

        chart.draw(data, options);
      }
    </script>

    <div id="chart_div" style="width: 700px; height: 400px"></div>
  </div>
</div>
</div>

    

@else 
   <h5>There's no information to show</h5>

@endif
@endsection