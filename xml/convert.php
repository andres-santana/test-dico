<?php
$archivo_csv = fopen('dico.csv', 'w');

if($archivo_csv)
{
    fputs($archivo_csv, xml2csv('./dico.xml', '/rss/channel/product').PHP_EOL);

    fclose($archivo_csv);
}else{
    echo "El archivo no existe o no se pudo crear";
}

//echo xml2csv('./dico.xml', '/rss/channel/product');
// ----------
// xml2csv
// -----
function xml2csv($xmlFile, $xPath) {

    // Load the XML file
    $xml = simplexml_load_file($xmlFile);

    // Jump to the specified xpath
    $path = $xml->xpath($xPath);

    $csvData .= '"linea","id","title","description","price","link",';
    $csvData .= "\n";
    // Loop through the specified xpath
    $n = 2;
    foreach($path as $item) {
        // Loop through the elements in this xpath
        $csvData .= $n .',';
        foreach($item as $key => $value) {
            if($key=="description"){
                $csvData .= '"'.htmlentities(trim($value)).'",';
            }
            else{
                $csvData .= trim($value) . ',';    
            }
            
        }
        
        // Trim off the extra comma
        $csvData = trim($csvData, ',');
        
        // Add an LF
        $csvData .= "\n";
        $n++;
    
    }
    
    // Return the CSV data
    return utf8_decode($csvData);
    
}