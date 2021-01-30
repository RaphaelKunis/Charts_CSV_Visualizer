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
        // use localstorage to control what is displayed an generated
        var viewDay;         // controls dispDay_ajax
        var viewDaySingle;   // controls dispDaySingle_ajax
        var viewCompDay;     // controls dispCompDay_ajax
        var viewComp;        // controls dispComp_ajax

        // toggle visibility of given div id
        function myToggleVisibility(id) {
            switch (id) {
                case "dispDay_ajax":
                    viewDay = !viewDay;
                    localStorage.setItem('viewDay',viewDay);
                    setSelMonthDays();
                    break;
                case "dispDaySingle_ajax":
                    viewDaySingle = !viewDaySingle;
                    localStorage.setItem('viewDaySingle',viewDaySingle);
                    setSelDaySingle();
                    break;
                case "dispCompDay_ajax":
                    viewCompDay = !viewCompDay;
                    localStorage.setItem('viewCompDay',viewCompDay);
                    setSelDayComp();
                    break;
                case "dispComp_ajax":
                    viewComp = !viewComp;
                    localStorage.setItem('viewComp',viewComp);
                    setSelMonthComp();
                    break;
                default:
                    break;
            }    
        }

        // function for Day Chart Reload based on selected month and boxplot checkbox, depends on viewDay from localstorage
        function setSelMonthDays() { // set the selected day by function
            if (viewDay) {                      // display
                var monthDaySelectBox = document.getElementById("monthDaySelectBox");
                if (monthDaySelectBox) {
                    var selMonthDays = monthDaySelectBox.options[monthDaySelectBox.selectedIndex].value;
                    $('#dispDay_ajax').load('./ajax_day.php', {selMonthDays:selMonthDays});
                } else {
                    $('#dispDay_ajax').load('./ajax_day.php');
                }
            } else {
                $('#dispDay_ajax').html("");    // clear
            }
        }
        // function for Single Day Chart Reload based on selected month and day, depends on viewDaySingle from localstorage
        function setSelDaySingle() { // set the selected day by function
            if (viewDaySingle) {                      // display            
                var singleDayInputBox = document.getElementById("singleDayInputBox");            
                if (singleDayInputBox) {
                    var selDay = singleDayInputBox.value;                                      
                    $('#dispDaySingle_ajax').load('./ajax_day_single.php', {selDay:selDay});
                } else {
                    $('#dispDaySingle_ajax').load('./ajax_day_single.php');     //  call without post of selDay    
                }
            } else {
                $('#dispDaySingle_ajax').html("");    // clear
            }
        }
        // function for Comparison Chart Reload based on selected month1 ands month2, depends on viewCompDay from localstorage
        function setSelMonthComp() { // set the selected months by function and reload chart
            if (viewComp) {
                var compMon1SelectBox = document.getElementById("compMonth1SelectBox");
                var compMon2SelectBox = document.getElementById("compMonth2SelectBox");
                if (compMon1SelectBox) {
                    var selcompMon1 = compMon1SelectBox.options[compMon1SelectBox.selectedIndex].value;
                    var selcompMon2 = compMon1SelectBox.options[compMon2SelectBox.selectedIndex].value;
                    $('#dispComp_ajax').load('./ajax_compare.php', {selMonth1:selcompMon1,selMonth2:selcompMon2});
                } else {
                    $('#dispComp_ajax').load('./ajax_compare.php');             // initial call without post of selMonth1 and selMonth2
                }
            } else {
                $('#dispComp_ajax').html("");    // clear
            }
        }    
        // function for Comparison Chart Reload based on selected day1 ands day2
        function setSelDayComp() { // set the selected days by function and reload chart
            if (viewCompDay) {
                var dayCompInputBox1 = document.getElementById("dayCompInputBox1");
                var dayCompInputBox2 = document.getElementById("dayCompInputBox2");
                if (dayCompInputBox1) {
                    var selDay1 = dayCompInputBox1.value;
                    var selDay2 = dayCompInputBox2.value;
                    $('#dispCompDay_ajax').load('./ajax_compare_days.php', {selDay1:selDay1,selDay2:selDay2});
                } else {
                    $('#dispCompDay_ajax').load('./ajax_compare_days.php');     // initial call without post of selDay1 and selDay2
                }
            } else {
                $('#dispCompDay_ajax').html("");    // clear
            }   
        }

        $(document).ready(function() {
            // init loaclstorage
            viewDay = localStorage.getItem('viewDay') ? localStorage.getItem('viewDay') === "true" ? true : false : false;
            viewDaySingle = localStorage.getItem('viewDaySingle') ? localStorage.getItem('viewDaySingle') === "true" ? true : false : false;
            viewCompDay = localStorage.getItem('viewCompDay') ? localStorage.getItem('viewCompDay') === "true" ? true : false : false;
            viewComp = localStorage.getItem('viewComp') ? localStorage.getItem('viewComp') === "true" ? true : false : false;

            // init charts
            setSelMonthDays();
            setSelDaySingle();
            setSelDayComp();                   
            setSelMonthComp();
        }); // $(document...
    </script>
    <title>CSV Visualisierung mit Chart.js</title>
</head>
<body>
    <h1>CSV Visualisierung mit Chart.js</h1>

    <button onclick="myToggleVisibility('dispDay_ajax')">Toggle Tageswerte</button>
    <button onclick="myToggleVisibility('dispDaySingle_ajax')">Toggle Einzelwerte Tag</button>
    <button onclick="myToggleVisibility('dispCompDay_ajax')">Toggle Tagesvergleich</button>
    <button onclick="myToggleVisibility('dispComp_ajax')">Toggle Monatsvergleich</button>


    <div id="dispDay_ajax"></div>        <!-- nur Platzhalter, wird durch ajax_day.php befüllt -->
    <div id="dispDaySingle_ajax"></div>  <!-- nur Platzhalter, wird durch ajax_day_single.php befüllt -->
    <div id="dispCompDay_ajax"></div>    <!-- nur Platzhalter, wird durch ajax_compare_days.php befüllt -->
    <div id="dispComp_ajax"></div>       <!-- nur Platzhalter, wird durch ajax_compare.php befüllt -->
    
    <!-- TODO -->
    <button onclick="toogleVisibility(document.getElementById('dispMonthBox_ajax'))">Toggle Monatsvergleich - Boxplot</button>
    <div id="dispMonthBox_ajax">
        <!-- nur Platzhalter, wird durch ajax_month_boxplot.php befüllt -->
    </div>

</body>
</html>