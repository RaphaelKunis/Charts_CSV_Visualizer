<?php 
/** Create the canvas, data and script elements for displaying monthly values */
require_once("./core.php");
require_once("./config.php");

// Paramtest
//foreach ($_POST as $param_name => $param_val) {
//    echo "Param: $param_name; Value: $param_val<br />\n }";

print('<h2>Monatswerte</h2>
<canvas id="chartMonthAjax" width="400" height="100"></canvas>
<script>');
// monthly values
// Create the javascript data types for labels and data
echo processData('read',readCSV( $data_dir."/zaehler_abgelesen.csv")); 
echo processData('measure',readCSV($data_dir."/zaehler_berechnet.csv")); 
/* result is 
   var label, data and temp */
print ("
chart_m = document.getElementById('chartMonthAjax');
drawChartMonthly(chart_m,labelMonRead,dataMonRead,dataMonMeas,tempMonMeas);
</script>");
?>