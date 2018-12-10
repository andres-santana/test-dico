<?php

$directorio = './cp/';
$ficheros  = glob($directorio.'*.csv');

/* Por cada archivo que exista en la carpeta realizar el despacho correspondiente */
foreach ($ficheros as $key => $nombre_archivo) {


	$archivo = fopen($nombre_archivo, "r") or die('No se puede abrir el archivo');
	$content = fread($archivo, filesize($nombre_archivo));
	$nom_archivo = split("/", $nombre_archivo);
  	$archivo_xml = split(".csv", $nom_archivo[2]);

  	$tsvFile = new SplFileObject($nombre_archivo);
  	$tsvFile->setFlags(SplFileObject::READ_CSV);

  	$n = 0;
  	$m = 0;
    $cp_temp = "";
    $array = array();
    $row_cp = "";
  	foreach ($tsvFile as $line => $row) {
    	if($line >= 0) {
        $cp = strlen($row[2]);

        if ($cp==4) {
          $row_cp = "0".$row[2];
          $array[$n] = array($row[0], $row[1], $row_cp, $row[3], $row[4]);
        }
       // if ($line==0) {
        else{
          $array[$n] = array($row[0], $row[1], $row[2], $row[3], $row[4]);
        }
          
        
        }
        
//        if ($row[3]=="0.0000" && $line > 0 && $row[4]=="299.0000") {
          //$array[$n] = array($row[0], $row[1], $row_cp, $row[3], $row[4]);
  //      }
        
        $n++;
        echo $cp;		
        echo "<br/>";
    	}
  	
	fclose($archivo);



  //
// save an array as tab seperated text file
//
 
function write_tabbed_file($filepath, $array, $save_keys=false){
    $content = '';
 
    reset($array);
    while(list($key, $val) = each($array)){
 
        // replace tabs in keys and values to [space]
        $key = str_replace("\t", " ", $key);
        $val = str_replace("\t", " ", $val);
 
        if ($save_keys){ $content .=  $key."\t"; }
 
        // create line:
        $content .= (is_array($val)) ? implode(",", $val) : $val;
        $content .= "\n";
    }
 
    if (file_exists($filepath) && !is_writeable($filepath)){ 
        return false;
    }
    if ($fp = fopen($filepath, 'w+')){
        fwrite($fp, $content);
        fclose($fp);
    }
    else { return false; }
    return true;
}
 
//
// load a tab seperated text file as array
//
function load_tabbed_file($filepath, $load_keys=false){
    $array = array();
 
    if (!file_exists($filepath)){ return $array; }
    $content = file($filepath);
 
    for ($x=0; $x < count($content); $x++){
        if (trim($content[$x]) != ''){
            $line = explode("\t", trim($content[$x]));
            if ($load_keys){
                $key = array_shift($line);
                $array[$key] = $line;
            }
            else { $array[] = $line; }
        }
    }
    return $array;
}
 
/*
** Example usage:
*/
 

 
// save the array to the data.txt file:
write_tabbed_file("./cp/update/".$nom_archivo[2], $array, false);
 
/* the data.txt content looks like this:
line1 data-1-1  data-1-2  data-1-3
line2 data-2-1  data-2-2  data-2-3
line3 data-3-1  data-3-2  data-3-3
line4 foobar
line5 hello world
*/
 
// load the saved array:
$reloaded_array = load_tabbed_file("./cp/update/".$nom_archivo[2],false);
 
print_r($reloaded_array);
  
// returns the array from above
}
?>