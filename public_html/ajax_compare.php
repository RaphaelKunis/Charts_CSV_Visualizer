<?php 
/** Create the select, canvas, data and script elements for displaying comparison of daily values of the selected month */
require_once("./core.php");
require_once("./config.php");

// read post variable or take the current month for compare
$month1    = isset($_POST['selMonth1']) ? $_POST["selMonth1"] : str_replace(strval(date("Y")),strval(date("Y")-1),getNewestS0File()); // ab jetzt Monat getNewestS0Filevom Vorjahr  "zaehler_kwh_202001.csv"; 
$month2    = isset($_POST['selMonth2']) ? $_POST["selMonth2"] : getNewestS0File();

print(' <h2>Vergleich der Monate '.substr($month1, 16,2).'.'.substr($month1, 12,4).' und '.substr($month2, 16,2).'.'.substr($month2, 12,4).'</h2>
');

// now print the select list an let the select var be selMonthDays
print ('<label>Monatsauswahl: 
<select id="compMonth1SelectBox" onchange="setSelMonthComp();">');            // setSelMonthComp is defined in index.php
$files = scandir($data_dir);
foreach($files as $file) {
    if (strpos($file, 'zaehler_kwh_') !== false) {
        echo '<option value="'.$file.'"';
        if ($file == $month1) echo ' selected';
        echo '>'.substr($file, 16,2).'-'.substr($file, 12,4).'</option>
        ';
    }
}
print ('</select>
<select id="compMonth2SelectBox" onchange="setSelMonthComp();">');            // setSelMonthComp is defined in index.php
$files = scandir($data_dir);
foreach($files as $file) {
    if (strpos($file, 'zaehler_kwh_') !== false) {
        echo '<option value="'.$file.'"';
        if ($file == $month2) echo ' selected';
        echo '>'.substr($file, 16,2).'-'.substr($file, 12,4).'</option>
        ';
    }
}
print '</select>
</label>'; 

print '
<h3>Summe '.substr($month1, 16,2).'/'.substr($month1, 12,4).': '.getCsvSum(readCSV($data_dir."/".$month1)).' - Tagesdurchschnitt '.getCsvAvg(readCSV($data_dir."/".$month1)).' </h3>
<h3>Summe '.substr($month2, 16,2).'/'.substr($month2, 12,4).': '.getCsvSum(readCSV($data_dir."/".$month2)).' - Tagesdurchschnitt '.getCsvAvg(readCSV($data_dir."/".$month2)).' </h3>
';

// Create the javascript data types for labels and data
print ('<canvas id="chartComp" width="400" height="100"></canvas>
<script>');
// Create the javascript data types for labels and data
echo processDataS0(readCSV($data_dir."/".$month1),"M1");  // Month1
echo processDataS0(readCSV($data_dir."/".$month2),"M2");  // Month2        
echo "var lblM1 = '".substr($month1, 16,2).'.'.substr($month1, 12,4)."';";
echo "var lblM2 = '".substr($month2, 16,2).'.'.substr($month2, 12,4)."';";
/* result is 
   var labelM1, dataM1,  tempM1, dataM2, tempM2, lblM1, lblM2 */
print ("chart_c = document.getElementById('chartComp');
drawChartCompare(chart_c,labelM1,lblM1,dataM1,tempM1,lblM2,dataM2,tempM2);        
</script>");
?>