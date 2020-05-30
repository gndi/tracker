<?php 
include('../config.php');
include('./session.php');
include ('./header.php');

$sql ="SELECT count(id) AS n_not, date(datetime) as date FROM notifications GROUP BY date(datetime);";
mysqli_query($db,"set names utf8");
$result =mysqli_query($db,$sql);
while($row = mysqli_fetch_assoc($result)) {
	$php_data_array[] = $row;

	};
	//echo json_encode($php_data_array); 
echo "<script>
        var my_2d = ".json_encode($php_data_array)."
</script>";

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div classs="container" >
	<div class="row" >
		<div class="col-md-4">
			<div width="100%" height="400px" id="canvas"></div>
			
		</div>
		<div class="col-md-4">
			
		</div>
		<div class="col-md-4">
			
		</div>


	</div>
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
		<div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
		<div class="btn-group mr-2" role="group" aria-label="First group">
    <a type="button" class="btn btn-secondary" href="../export.php?service=hc">Facilitis</a>
    <a type="button" class="btn btn-secondary" href="../export.php?service=notifications">Notifications</a>
    <a type="button" class="btn btn-secondary" href="../export.php?service=cases">Cases</a>
    <a type="button" class="btn btn-secondary">4</a>
  </div>
</div>
</div>

	</div>





</div>
















<script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {packages: ['corechart']});
      google.charts.setOnLoadCallback(drawChart);
	  
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Notifications');
        var sum;

        for(i = 0; i < my_2d.length; i++){

    		data.addRow([my_2d[i]['date'],parseInt(my_2d[i]['n_not'])]);
    		//sum+=parseInt(my_2d[i]['n_not']);
    	}
    		

var options = {
          title: 'Notifications per date',
        curveType: 'function',
        
        height: 400,
		
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('canvas'));
        chart.draw(data, options);

}

</script>






<?php
include('./footer.php');
?>