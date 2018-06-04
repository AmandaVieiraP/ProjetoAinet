  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
  <script type="text/javascript">
      google.charts.load('current', {packages: ['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawBasic);

      function drawBasic() {

            var data = google.visualization.arrayToDataTable([
              ['Total', 'Total Value',],
              ['Users', {{ $users }}],
              ['Accounts', {{ $accounts }}],
              ['Movements', {{ $movements }}]
            ]);

            var options = {
              title: 'Statistical Information',
              chartArea: {width: '50%'},
              hAxis: {
                title: 'Total',
                minValue: 0
              }
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

            chart.draw(data, options);
          }
    </script>

    <div id="chart_div">
</div>