<?php
/**
     * Check to see if file has extension .csv
     * and check if mime type is in allowed list
     * Then read CSV file into array
     * @param $file string
     * @param $column_name bool
     * @return array
     */
function readCSV($file, $column_name=false){
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $file);
        finfo_close($finfo);
        $error_message = '';

        // checking few csv mime types that I found through my test and various forums and blogs.
        if(strtolower($ext) == 'csv' && in_array($mtype, array('text/csv','text/anytext','text/plain','text/comma-separated-values','application/csv','application/excel','application/vnd.ms-excel','application/vnd.msexcel'))){
            $csv = new Varien_File_Csv();

            try {
                $csvData = $csv->getData($file);
                // if you want to include column names as array keys
                if($column_name){
                    // get first array as column name
                    $columns =  array_shift($csvData);

                    // for remaining array items replace array keys with column names.
                    foreach($csvData as $k => $v){
                        $csvData[$k] = array_combine($columns, array_values($v));
                    }
                }
                return $csvData;
            } catch (Exception $e) {
                $error_message = "Unable to read csv file '{$file}'. Error: " . $e->getMessage() . '. See exception.log for full error log.';
                Mage::logException($e);
            }
        } else {
            $error_message = "Unable to read csv file '{$file}'. Either it's missing .csv extension or mime type is not in the allowed list.";
        }
        Mage::log($error_message, null, 'orderxml.log');
        return array();
    }