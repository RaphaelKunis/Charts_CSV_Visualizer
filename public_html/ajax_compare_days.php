<?php 
/** Create the select, canvas, data and script elements for displaying comparison of daily values of the selected month */
require_once("./core.php");
require_once("./config.php");
$thispage = "";

// read post variables or take the current day for compare
$selDay1    = isset($_POST['selDay1']) ? $_POST["selDay1"] : date("Y-m-d");
$filename1  = "zaehler_kwh_".substr(str_replace("-","",$selDay1),0,-2).".csv";
$dayInFile1 = date("Y-n-j",strtotime($selDay1));
$selDay2    = isset($_POST['selDay2']) ? $_POST["selDay2"] : $selDay1;
$filename2  = "zaehler_kwh_".substr(str_replace("-","",$selDay2),0,-2).".csv";
$dayInFile2 = date("Y-n-j",strtotime($selDay2));

# Start creating page
$thispage = '<h2>Vergleich der Tageswerte '.date("d.m.Y",strtotime($selDay1)).' und '.date("d.m.Y",strtotime($selDay1)).'</h2>';
// now add the select lists an let the select var be selDay2
$thispage .= '<label>Tagesauswahl 1: 
    <input id="dayCompInputBox1" onchange="setSelDayComp();" type="date" value="'.$selDay1.'"          // setSelDayComp is defined in index.php
    min="2019-03-20" max='.date("Y-m-d").'/>
</label>
<label>Tagesauswahl 2: 
    <input id="dayCompInputBox2" onchange="setSelDayComp();" type="date" value="'.$selDay2.'"          // setSelDayComp is defined in index.php
    min="2019-03-20" max='.date("Y-m-d").'/>
</label>
';

if (empty(readCSV($data_dir."/".$filename1, $dayInFile1))) {
    $thispage .= '<p>Keine Werte für den Tag 1 ('.date("d.m.Y",strtotime($selDay1)).')  enthalten</p>';
} else if (empty(readCSV($data_dir."/".$filename2, $dayInFile2))) { 
    $thispage .= '<p>Keine Werte für den Tag 2 ('.date("d.m.Y",strtotime($selDay2)).')  enthalten</p>';
} else {
    $thispage .= '<h3>Summe '.date("d.m.Y",strtotime($selDay1)).': '.getCsvSum(readCSV($data_dir."/".$filename1, $dayInFile1)).'</h3>
                  <h3>Summe '.date("d.m.Y",strtotime($selDay2)).': '.getCsvSum(readCSV($data_dir."/".$filename2, $dayInFile2)).'</h3>
    ';

    // check whetere there are values for the chosen day 
    // create javascript
    $thispage .= '<canvas id="chartCompDays" width="400" height="100"></canvas>
    <script>
    chart_c = document.getElementById(\'chartCompDays\');
    ';
    $thispage .= processDataS0(readCSV($data_dir."/".$filename1, $dayInFile1),"D1");  // Day 1
    $thispage .= "var lblD1 = '".date("d.m.Y",strtotime($selDay1))."';";
    $thispage .= processDataS0(readCSV($data_dir."/".$filename2, $dayInFile2),"D2");  // Day 2    
    $thispage .= "var lblD2 = '".date("d.m.Y",strtotime($selDay2))."';";
    // result is var labelM1, dataM1,  tempM1, dataM2, tempM2, lblM1, lblM2 */
    $thispage .= "drawChartCompare(chart_c,labelD1,lblD1,dataD1,tempD1,lblD2,dataD2,tempD2);";     
    $thispage .= '  </script>';
} 

// Now print the page contents
echo $thispage;
?>