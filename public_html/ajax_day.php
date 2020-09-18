<?php 
require_once("./core.php");
$data_dir = './data'; 
$monthDays = isset($_POST['selMonthDays']) ? $_POST["selMonthDays"] : getNewestS0File();

print('<h2>Tageswerte Ajax des Monats - '.substr($monthDays, 16,2).'.'.substr($monthDays, 12,4).'</h2>
<h3>Summe: '.getCsvSum(readCSV($data_dir."/".$monthDays)).'</h3>
');

// now print the select list an let the select var be selMonthDays
print '<label>Monatsauswahl: <select id="monthDaySelectBox" onchange="setSelMonthDays();">';            // setSelMonthDays is defined in index2.php
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