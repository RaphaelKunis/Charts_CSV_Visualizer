<?php 
/** Create the select, canvas, data and script elements for displaying daily values of the selected month */

require_once("./core.php");
require_once("./config.php");
// get variable or set to standard value
$monthDays = isset($_POST['selMonthDays']) ? $_POST["selMonthDays"] : getNewestS0File();

print('<h2>Tageswerte des Monats - '.substr($monthDays, 16,2).'.'.substr($monthDays, 12,4).'</h2>
<h3>Summe: '.getCsvSum(readCSV($data_dir."/".$monthDays)).'</h3>
');

// now print the select list an let the select var be selMonthDays
print '<label>Monatsauswahl: <select id="monthDaySelectBox" onchange="setSelMonthDays();">';            // setSelMonthDays is defined in index.php
$files = scandir($data_dir);
foreach($files as $file) {
    if (strpos($file, 'zaehler_kwh_') !== false) {
        echo '<option value="'.$file.'"';
        if ($file == $monthDays) echo ' selected';
        echo '>'.substr($file, 16,2).'-'.substr($file, 12,4).'</option>
        ';
    }
}
print '</select></label>'; 

// Create the javascript data types for labels and data
print ('<canvas id="chartDayAjax" width="400" height="100"></canvas>
<script>');
echo processDataS0(readCSV($data_dir."/".$monthDays)); 
/* result is 
   var label, data and temp */
print ("
chart_s = document.getElementById('chartDayAjax');
drawChartS0(chart_s,labelS0,dataS0,tempS0);
</script>");
?>