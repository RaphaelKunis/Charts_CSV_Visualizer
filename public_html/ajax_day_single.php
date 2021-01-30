<?php 
/** Create the select, canvas, data and script elements for displaying hourly values of the selected day 
*   added $thispage for the possibility to display the generated code
*/
require_once("./core.php");
require_once("./config.php");
$thispage = "";

// get variable or set to standard value
$selDay    = isset($_POST['selDay']) ? $_POST["selDay"] : date("Y-m-d");
$filename  = "zaehler_kwh_".substr(str_replace("-","",$selDay),0,-2).".csv";
$dayInFile = date("Y-n-j",strtotime($selDay));

DEBUG ? print_r(readCSV($data_dir."/".$filename, $dayInFile)) : NULL;

$thispage = '<h2>Tageswerte vom '.date("d.m.Y",strtotime($selDay)).' aus Datei '.$filename.'</h2>';
// now add the select list an let the select var be selMonthDays
$thispage .= '<label>Tagesauswahl: 
    <input id="singleDayInputBox" onchange="setSelDaySingle();" type="date" value="'.$selDay.'"
    min="2019-03-20" max='.date("Y-m-d").'/>
</label>
';

// check whetere there are values for the chosen day 
if (empty(readCSV($data_dir."/".$filename, $dayInFile))) {
    $thispage .='<p>Keine Werte für den angegebenen Tag enthalten</p>';
} else {
    $thispage .= '<h3>Summe            : '.getCsvSum(readCSV($data_dir."/".$filename, $dayInFile)).'</h3>';
    // Create the javascript data types for labels and data
    $thispage .= '<canvas id="chartDaySingleAjax" width="400" height="100"></canvas>
    <script>
        chart_s = document.getElementById(\'chartDaySingleAjax\');';
    $thispage .= processDataS0(readCSV($data_dir."/".$filename, $dayInFile));  // result is var labelS0, dataS0 and tempS0 
    $thispage .= "drawChartS0(chart_s,labelS0,dataS0,tempS0);";
    $thispage .= "\r\n</script>";
}

// Now print the page contents
echo $thispage;
?>