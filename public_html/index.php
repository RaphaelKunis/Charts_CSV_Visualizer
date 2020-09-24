<?php 
// php standard actions to init 
require_once("./core.php");
require_once("./config.php");   // i.e. $data_dir
?>
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
    <script>
        // Ajax
        var x = 5;  // just test and to show click function
        
        // function for Day Chart Reload based on selected month
        function setSelMonthDays() { // set the selected day by function
            var monthDaySelectBox = document.getElementById("monthDaySelectBox");
            var selMonthDays = monthDaySelectBox.options[monthDaySelectBox.selectedIndex].value;
            $('#dispDay_ajax').load('./ajax_day.php', {selMonthDays:selMonthDays});
        }
        // function for Comparison Chart Reload based on selected month1 ands month2
        function setSelMonthComp() { // set the selected months by function and reload chart
            var compMon1SelectBox = document.getElementById("compMonth1SelectBox");
            var compMon2SelectBox = document.getElementById("compMonth2SelectBox");
            var selcompMon1 = compMon1SelectBox.options[compMon1SelectBox.selectedIndex].value;
            var selcompMon2 = compMon1SelectBox.options[compMon2SelectBox.selectedIndex].value;
            $('#dispComp_ajax').load('./ajax_compare.php', {selMonth1:selcompMon1,selMonth2:selcompMon2});
        }    

        $(document).ready(function() {
            // init charts
            $('#dispMonth_ajax').load('./ajax_month.php', {var:x});
            $('#dispDay_ajax').load('./ajax_day.php');           // initial call without post of selMonthDays
            $('#dispComp_ajax').load('./ajax_compare.php');      // initial call without post of selMonth1 and selMonth2
            
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
    <h1>CSV Visualisierung mit Chart.js</h1>

    <button onclick="toogleVisibility(document.getElementById('dispDay_ajax'))">Toggle Tageswerte</button>
    <div id="dispDay_ajax">
    <!-- nur Platzhalter, wird durch ajax_day.php befüllt -->
    </div>

    <button onclick="toogleVisibility(document.getElementById('dispMonth_ajax'))">Toggle Monatswerte</button>
    <a id='ajax_link_month' href='#'>Ajax update month</a><br/>
    <div id="dispMonth_ajax">
        <!-- nur Platzhalter, wird durch ajax_month.php befüllt -->
    </div>

    <button onclick="toogleVisibility(document.getElementById('dispComp_ajax'))">Toggle Monatsvergleich</button>
    <div id="dispComp_ajax">
    <!-- nur Platzhalter, wird durch ajax_compare.php befüllt -->
    </div>    

</body>
</html>