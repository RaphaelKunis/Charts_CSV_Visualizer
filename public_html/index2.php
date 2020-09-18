<?php require_once("./core.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>      <!-- needed for charts -->
    <script src="https://unpkg.com/@sgratzl/chartjs-chart-boxplot"></script>                        <!-- needed for boxplot charts - copy in folder javascripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>        <!-- needed for ajax -->
    <script src="./javascripts/core.js"></script>                                                   <!-- my own js library -->
    <script> // Ajax
        var x = 'Teststring';
        
        function setX(choice){  // set the variable x by function from ajax_mybtn
           x = choice;
           $('#dispMonth_ajax').load('./ajax_month.php', {var:x});  // test if this is also possible
        }
        
        // function for Day Chart Reload based on selected month
        function setSelMonthDays() { // set the variable by function
            var monthDaySelectBox = document.getElementById("monthDaySelectBox");
            var selMonthDays = monthDaySelectBox.options[monthDaySelectBox.selectedIndex].value;
            $('#dispDay_ajax').load('./ajax_day.php', {selMonthDays:selMonthDays});
        }

        $(document).ready(function() {
            // init charts
            $('#dispMonth_ajax').load('./ajax_month.php', {var:x});
            $('#dispDay_ajax').load('./ajax_day.php');      // initial call without post of selMonthDays
            
            // functions for chart reload
            $('#ajax_link_month').click(function(e){
                e.preventDefault();
                $('#dispMonth_ajax').load('./ajax_month.php', {var:x});
            }); //  $('#ajax_link_month').click
        }); // $(document...
    </script>
    <title>CSV Visualisierung mit Chart.js</title>
</head>
<body>
    <?php  // php standard actions to init 
        $data_dir = './data'; 
        // try to get the two month via POST if already set or use standard values
        $monthDays = isset($_POST['selMonthDays']) ? $_POST["selMonthDays"] : getNewestS0File();
        $month1    = isset($_POST['selMonth1']) ? $_POST["selMonth1"] : str_replace(strval(date("Y")),strval(date("Y")-1),getNewestS0File()); // ab jetzt Monat getNewestS0Filevom Vorjahr  "zaehler_kwh_202001.csv"; 
        $month2    = isset($_POST['selMonth2']) ? $_POST["selMonth2"] : getNewestS0File();
    ?>

    <?php // -- now start with html displaying ?> 
    <h1>CSV Visualisierung mit Chart.js</h1>

    <!-- jetzt als Ajax umgesetzt, siehe unten
    <div id="dispDay">
        <h2>Tageswerte des Monats - <?php echo substr($monthDays, 16,2).'.'.substr($monthDays, 12,4); ?></h2>
        <h3>Summe: <?php echo getCsvSum(readCSV($data_dir."/".$monthDays))?></h3>

        <form id="formDay" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <select id="formDaySel" name="selMonthDays">
                <?php 
                $files = scandir($data_dir);
                foreach($files as $file) {
                    if (strpos($file, 'zaehler_kwh_') !== false) {
                        echo '<option value="'.$file.'" ';
                        if ($file == $monthDays) echo 'selected';
                        echo '>'.substr($file, 16,2).'-'.substr($file, 12,4).'</option>';
                    }
                } 
                ?>
            </select>
            <input type="submit" value="Neu laden">
        </form>            
        <canvas id="chartS0" width="400" height="100"></canvas>
    </div>
    -->

    <div id="dispDay_ajax">
    <!-- nur Platzhalter, wird durch ajax_day.php befüllt -->
    </div>

    <a id='ajax_link_month' href='#'>Ajax update month</a><br/>
<!--     <button class="ajax_mybtn" onClick="setX('Test 111')">Choice Test1</button>
    <button class="ajax_mybtn" onClick="setX('Test 222222')">Choice Test2</button>  -->
    <div id="dispMonth_ajax">
        <h2>Monatswerte Ajax</h2>
        <canvas id="chartMonthAjax" width="400" height="100"></canvas>
    </div>

    <div id="dispCompare">
        <h2>Vergleich der Monate <?php echo substr($month1, 16,2).'.'.substr($month1, 12,4); ?> und <?php echo substr($month2, 16,2).'.'.substr($month2, 12,4); ?></h2>
        <form id="formCompare" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <select id="formCompareSelM1" name="selMonth1">
                <?php 
                $files = scandir('data/');
                foreach($files as $file) {
                    if (strpos($file, 'zaehler_kwh_') !== false) {
                        echo '<option value="'.$file.'" ';
                        if ($file == $month1) echo 'selected';
                        echo '>'.substr($file, 16,2).'.'.substr($file, 12,4).'</option>';
                    }
                } 
                ?>
            </select>
            <select id="formCompareSelM2" name="selMonth2">
                <?php 
                $files = scandir('data/');
                foreach($files as $file) {
                    if (strpos($file, 'zaehler_kwh_') !== false) {
                        echo '<option value="'.$file.'" ';
                        if ($file == $month2) echo 'selected';
                        echo '>'.substr($file, 16,2).'.'.substr($file, 12,4).'</option>';
                    }
                } 
                ?>
            </select>        
            <input type="submit" value="Neu laden">
        </form>
        <canvas id="chartComp" width="400" height="100"></canvas>
    </div>

    <script>
        // values measured by S0
        <?php 
        // Create the javascript data types for labels and data
        //echo processDataS0(readCSV($data_dir."/".$monthDays)); 
        // echo processDataS0(readCSV($data_dir."zaehler_kwh_202003.csv")); 
        /* result is 
           var labelS0, dataS0 and tempS0 */
        ?>
        //chart_s = document.getElementById('chartS0');
        //drawChartS0(chart_s,labelS0,dataS0,tempS0);

        // monthly values - now ajax
        <?php 
        // Create the javascript data types for labels and data
        //echo processData('read',readCSV( $data_dir."/zaehler_abgelesen.csv")); 
        //echo processData('measure',readCSV($data_dir."/zaehler_berechnet.csv")); 
        /* result is 
           var label, data and temp */
        ?>
        // chart_m = document.getElementById('chartMonth');
        // drawChartMonthly(chart_m,labelMonRead,dataMonRead,dataMonMeas,tempMonMeas);

        // values compare two month
        <?php 
        // Create the javascript data types for labels and data
        echo processDataS0(readCSV($data_dir."/".$month1),"M1");  // Month1
        echo processDataS0(readCSV($data_dir."/".$month2),"M2");  // Month2        
        echo "var lblM1 = '".substr($month1, 16,2).'.'.substr($month1, 12,4)."';";
        echo "var lblM2 = '".substr($month2, 16,2).'.'.substr($month2, 12,4)."';";
        /* result is 
           var labelM1, dataM1,  tempM1, dataM2, tempM2, lblM1, lblM2 */
        ?>
        chart_c = document.getElementById('chartComp');
        drawChartCompare(chart_c,labelM1,lblM1,dataM1,tempM1,lblM2,dataM2,tempM2);        
    </script>

</body>
</html>