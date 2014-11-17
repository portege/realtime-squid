<?php
include_once 'config.php';
?>
<!DOCTYPE HTML>
<html>

<head>
	<script src="inc/jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
//	setInterval('window.location.reload()', 60000);
	window.onload = function () {

		// dataPoints
<?php
$c=0;
	foreach ($block_ip as $ips){
		$c++;
		echo 'var dataPoints'.$c.' = [];'."\n";

	}
?>

		var chart = new CanvasJS.Chart("chartContainer",{
			zoomEnabled: true,
			title: {
				text: "RealTime Bandwidth Monitoring"		
			},
			toolTip: {
				shared: true
				
			},
			legend: {
				verticalAlign: "top",
				horizontalAlign: "center",
                                fontSize: 24,
				fontWeight: "bold",
				fontFamily: "calibri",
				fontColor: "dimGrey"
			},
			axisX: {
				title: "chart updates every 3 secs"
			},
			axisY:{
				prefix: '',
				includeZero: false
			}, 
			data: [
<?php
$c=0;
$js_data_string = array();
foreach ($block_ip as $ips){
	$c++;
	$js_data_string[] = "				
				{ 
				// dataSeries$c
				type: \"line\",
				xValueType: \"dateTime\",
				showInLegend: true,
				name: \"$ips\",
				dataPoints: dataPoints$c
			}";

}
echo implode(',',$js_data_string);
?>
			],
          legend:{
            cursor:"pointer",
            itemclick : function(e) {
              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
              }
              else {
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
		});



		var updateInterval = 3000;
		// initial value
<?php
$c=0;
foreach ($block_ip as $ips){
	$c++;
	echo 'var yValue'.$c.' = 0;'."\n";
}
?>

		var time = new Date;
//		time.setHours(20);
//		time.setMinutes(00);
//		time.setSeconds(00);
//		time.setMilliseconds(00);
		// starting at 9.30 am

		var updateChart = function (count) {
			count = count || 1;
//			count = 1;

			// count is number of times loop runs to generate random dataPoints. 

			for (var i = 0; i < count; i++) {

				time.setTime(time.getTime()+ updateInterval);
<?php
$c=0;
$js_define_var_string = array();
$js_push_string = array();
foreach ($block_ip as $ips){
	$c++;
	$js_define_var_string[] = '$.get( "sqstat.api.php?i='.$ips.'&r='.rand(1,1000000).'", function( data ) {
					  yValue'.$c.' = parseInt(data);
					});
				';


	$js_push_string[] = "dataPoints$c.push({
                                        x: time.getTime(),
                                        y: yValue$c
                                });\n
			";
}
	echo implode("\n",$js_define_var_string);
	echo "\n";
	echo implode("\n",$js_push_string);
?>
/*				
				// add interval duration to time				
				time.setTime(time.getTime()+ updateInterval);


				// generating random values
				var deltaY1 = .5 + Math.random() *(-.5-.5);
				var deltaY2 = .5 + Math.random() *(-.5-.5);

				// adding random value and rounding it to two digits. 
				yValue1 = Math.round((yValue1 + deltaY1)*100)/100;
				yValue2 = Math.round((yValue2 + deltaY2)*100)/100;
				
				// pushing the new values
				dataPoints1.push({
					x: time.getTime(),
					y: yValue1
				});
				dataPoints2.push({
					x: time.getTime(),
					y: yValue2
				});

*/
			};

			// updating legend text with  updated with y Value 
<?php
$c=0;
foreach ($block_ip as $ips){
	$c++;
//	echo 'chart.options.data['.$c.'].legendText = " '.$ips.'  $" + yValue'.$c.';';
}
?>
			chart.render();

		};

		// generates first set of dataPoints 
		updateChart(3000);	
		 
		// update chart after specified interval 
		setInterval(function(){updateChart()}, updateInterval);
	}
	</script>
	<script type="text/javascript" src="inc/canvasjs.min.js"></script>
</head>
<body>
	<div id="chartContainer" style="height: 300px; width: 100%;">
	</div>
</body>


</html>

