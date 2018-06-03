    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);


      function drawChart() { 

        var data = google.visualization.arrayToDataTable([
          ['Accounts', 'Percentagem'],

          @for($i = 0; $i < count($accounts); $i++)
            <?php $summary[$i]=abs($summary[$i]); ?>
            ['ID: {{$accounts[$i]->id}}',{{$summary[$i]}}],
          @endfor
        
        ]);

        var options = {
          title: 'Accounts Summary (%)',
          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>

    <div id="piechart" style="width: 600px; height: 250px;"></div>