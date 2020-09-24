<?php

/**
 *  read the contents of a given csv file and store it into an array
 *  @param string $fname the filename of the csv file
 */
function readCSV($fname) {
    // The nested array to hold all the arrays
    $csv_as_array = []; 
    
    // Open the file for reading
    ini_set('auto_detect_line_endings',TRUE);
    if (($h = fopen($fname, "r")) !== FALSE) {
   
        // Convert each line into the local $data variable
        while (($data = fgetcsv($h, 500, ";")) !== FALSE) {		
            // Each individual array is being pushed into the nested array
            $csv_as_array[] = $data;	
        }

        // Close the file
        fclose($h);    
    } else {
        $csv_as_array[] = "Could not open file ".$fname;
    }
    ini_set('auto_detect_line_endings',FALSE);
    
    return $csv_as_array;
}

/**
 *  calculate the sum of the kwH values and temp average given as array from readCSV
 *  @param array $csv_as_array the array created by readCSV
 *  @return String with values of sum(kwH) and average(temp)
 */
function getCsvSum($csv_as_array) {
    $sumKwh = 0;
    $avgTemp = 0;
    $cnt = 0;
    foreach ($csv_as_array as $value) {
        if ($value[0] != 'Datum') { 
            $sumKwh += $value[3];
            $avgTemp += $value[4]; 
            $cnt +=1;
        }
    }
    return number_format(round($sumKwh,2),2,",",".")."kWh - ".number_format(round($avgTemp/$cnt,2),2,",",".")." °C";
}

/**
 *
 * create the data arrays from csv for monthly values as javascript code, i.e. var label.read = [v1,v2,v3,..];
 * available for files 
 * - zaehler_abgelesen.csv 
 * - zaehler_berechnet.csv
 *
 * @param    string  $art the type of csv: read = abgelesene Werte, measure= measured values
 * @param    array   $csv_as_array the csv values as array
 * @return   string  the javascript type definitions
 */
function processData($art='read',$csv_as_array) {
    $abrv = "";
    $abrv = ($art == 'read') ? "MonRead" : "MonMeas";   // decide whether read or measured values are processed
    $label = "var label".$abrv." = ["; 
    $data  = "var data".$abrv." = [";
    $temp  = "var temp".$abrv."= [";

    foreach ($csv_as_array as $value) {
        if ($value[0] != 'Datum') { 
            $label .= "'".substr($value[0],3)."',"; 
            $data  .= $value[1].','; 
            $temp .= ($art == 'measure') ? $value[2].',' : '';
        }
    }

    $label = rtrim($label, ',')."];";
    $data  = rtrim($data, ',')."];";
    $temp  = rtrim($temp, ',')."];";
    
    return $label."\r\n".$data."\r\n".$temp;
}

/* 
    process the data measured by S0Counter
    Creates javascript array, i.e. var label.S0 = [v1,v2,v3,..];
    hint: the measures are taken every twenty minutes (temperature) or when 0.1 kWh are used
    
    @param    array   $csv_as_array the csv values as array in the form [[date,kwH,temperature]]
    @param    string  $abrv sets the abbreviation of the var-names for javascript, i.e. So -> var labelS0, var dataS0, ...; DEFAULT: 'S0'
*/
function processDataS0($csv_as_array, $abrv='S0') {
    $label = "var label".$abrv." = ["; 
    $data  = "var data".$abrv."  = [";
    $temp  = "var temp".$abrv."  = [";

    $arr_Day = calculateDayS0($csv_as_array);

    foreach ($arr_Day as $value) {
        if ($value[0] != 'Datum') { 
            $label .= "'".$value[0]."',"; 
            $data  .= $value[1].','; 
            $temp .=  $value[2].',';
        }
    }

    $label = rtrim($label, ',')."];";
    $data  = rtrim($data, ',')."];";
    $temp  = rtrim($temp, ',')."];";
    
    return $label."\r\n".$data."\r\n".$temp;
}

/*
    Calculate Day values from S0 csv array
*/
function calculateDayS0($data) {

    $res = [];
    $lastTick = 0;       // zuletzt geschriebener Tick-Index fuer Pruefung auf letzten wert nach Schleife

    $i = 0;
    if (!is_numeric($data[$i][0])) {
        $i += 1;  // if header, then start at line two
    }
 
    $energy      = 0.0;
    $temperature = 0.0;
    $cnt_temp  = 0;                 // needed for average
    $lastValue = $data[$i][0];      // init
    $newValue  = 0; 
    while ($i < sizeof($data)) {
        $newValue = $data[$i][0];    // just the day

        if ($lastValue == $newValue) {
            $energy      += floatval ($data[$i][3]); 
            $temperature += floatval ($data[$i][4]); 
            $cnt_temp++;
        } else {
            array_push($res, [$lastValue, number_format($energy, 2), number_format(($temperature / $cnt_temp),2)]);
            $energy      = floatval($data[$i][3]);
            $temperature = floatval($data[$i][4]);
            $cnt_temp = 1;
        }
     
        $lastValue = $newValue;
        $i++;        // next elem;
    }

    // write last value if not done yet
    if ($res[sizeof($res)-1][0] != $lastValue) {
        array_push($res, [$newValue, number_format($energy, 2), number_format(($temperature / $cnt_temp),2)]);
    }
    
    //enegery_data = res;
    return $res;
}

function getNewestS0File() {
    $files = scandir('data', SCANDIR_SORT_DESCENDING);
    foreach($files as $file) {
        if (strpos($file, 'zaehler_kwh_') !== false) {
            $newest_file = $file;   // return first found file
            break;
        }
    } 
    return $newest_file; 
}
?>