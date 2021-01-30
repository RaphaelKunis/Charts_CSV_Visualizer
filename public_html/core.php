<?php
require_once("config.php");

/**
 *  read the contents of a given csv file and store it into an array
 *  Without parameter $day writes the whole content otherwise only writes the day values replacing the first column (date) by hour form second column (time)
 *  @param string $fname the filename of the csv file
 *  @param string $day if given then only the chosen day is returned in the result array and date is replaced by hour
 */
function readCSV($fname,$day = null) {
    DEBUG ? print ('readCSV: $fname: ' & $fname) : NULL;
    // The nested array to hold all the arrays
    $csv_as_array = []; 
    
    // Open the file for reading
    ini_set('auto_detect_line_endings',TRUE);
    if (($h = fopen($fname, "r")) !== FALSE) {
   
        // Convert each line into the local $data variable
        while (($data = fgetcsv($h, 500, ";")) !== FALSE) {		
            // Each individual array is being pushed into the nested array
            // if $day is given the only that day is processed
            if ($day == null) { $csv_as_array[] = $data; }
            elseif ($data[0] == $day) { $csv_as_array[] = array( date("H",strtotime($data[1])), $data[1], $data[2], $data[3], $data[4]); }
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
        if ($value[0] != 'Datum' && trim($value[0]) != '') { 
            DEBUG ? print ('getCsvSum: $value[3]: ' & $value[3]) : NULL;
            $sumKwh += $value[3];
            $avgTemp += $value[4]; 
            $cnt +=1;
        }
    }
    return ''.number_format(round($sumKwh,2),2,",",".")." kWh - ".number_format(round($avgTemp/$cnt,2),2,",",".")." °C";
}

/**
 *  calculate the average per day of the kwH values given as array from readCSV
 *  @param array $csv_as_array the array created by readCSV
 *  @return String with values of avgerage(kwH per day) and average(temp)
 */
function getCsvAvg($csv_as_array) {
    $sumKwh = 0;
    $cnt_days = 0;
    $last_date = "";
    foreach ($csv_as_array as $value) {
        if ($value[0] != 'Datum' && trim($value[0]) != '') { 
            $sumKwh += $value[3];
            if ($last_date != $value[0]) {
                $last_date = $value[0];
                $cnt_days += 1;
            }
        }
    }
    return ''.number_format(round($sumKwh/$cnt_days,2),2,",",".")." kWh";
}


/**
 *
 * create the data arrays from csv for monthly values as javascript code, i.e. var label.read = [v1,v2,v3,..];
 * available for files 
 * - zaehler_abgelesen.csv 
 * - zaehler_berechnet.csv
 *
 * @param    array   $csv_as_array the csv values as array
 * @param    string  $art the type of csv: read = abgelesene Werte, measure= measured values
 * @return   string  the javascript type definitions
 */
function processData($csv_as_array, $art='read') {
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
    hint: the measures are taken every twenty minutes (temperature) or when 0.1 kWh are resumed
    
    @param    array   $csv_as_array the csv values as array in the form [[date|hour,kwH,temperature]]
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
    Can also be used for calculation of hourly values for on day 
        if $data contains only single day values and the first column (formerly day) is replaced by the hour of column 2
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

/* 
    process the data measured by S0Counter for boxplot display (only label and temp)
    Creates javascript array, i.e. var label.S0Bp = [v1,v2,v3,..];
    This means the values are put in separate arrays under temp for each day
    
    @param    array   $csv_as_array the csv values as array in the form [[date,kwH,temperature]]
    @param    string  $abrv sets the abbreviation of the var-names for javascript, i.e. S0Bp -> var labelS0Bp, var tempS0Bp, ...; DEFAULT: 'S0Bp'
*/
function processDataS0Boxplot($csv_as_array, $abrv='S0Bp') {
    $label = "var label".$abrv." = ["; 
    $temp  = "var temp".$abrv."  = [[";

    foreach ($csv_as_array as $value) {
        if ($value[0] != 'Datum') { 
            if (substr($label,-4) !=  substr($value[0]."',",-4)) {
                // add new label and new array when day in $csv_as_array changes
                $label .= "'".$value[0]."',"; 
                if (substr($temp,-2) == '[[') { // first element  
                    $temp .= $value[4].',';
                } else {
                    $temp =  rtrim($temp, ',')."],\r\n[".$value[4].',';
                }
            } else {// add temperature to latest array in $temp
                $temp .= $value[4].',';
            }
        }
    }

    $label = rtrim($label, ',')."];";
    $temp  = rtrim($temp, ',')."]];";
    
    return "\r\n".$label."\r\n".$temp;

}

function getNewestS0File() {
    $files = scandir('data', SCANDIR_SORT_DESCENDING);
    foreach($files as $file) {
        if (strpos($file, 'zaehler_kwh_') !== false) {
            $newest_file = $file;   // return first found file
            DEBUG ? print ("getNewestS0File" & $newest_file) : NULL; 
            break;
        }
    } 
    return $newest_file; 
}

/*
return the last line with values of a given csv file
*/
function getLastValuesInFile($fname) {
    $csv_as_array = readCSV($fname);
    $last[] = "";
    do {
        $last = array_pop($csv_as_array);
       
    } while ($last[0] == "");
    $retval = "[".$last[0].", ".$last[1].", ".$last[2].", ".$last[3].", ".$last[4]."]";
    return $retval;
}

/* Test section ****************************************************/
// print(getLastValuesInFile("./data/zaehler_kwh_202010.csv"));
?>