<?php
set_time_limit(0);
session_start();
header("Content-type: text/xml");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
include 'config.php';
switch($_REQUEST['op']){
case "cp":
getZP();
break;
}

function getZP(){
$cp = mysql_real_escape_string($_REQUEST['cp']);
$sql = "SELECT * FROM CodigosPostales WHERE CodigoPostal = '$cp'";
$query = mysql_query($sql);
$num = mysql_num_rows($query);
if($num > 0){
$res = '<found>'.$num.'</found>';
}else{
$res = '<found>'.$num.'</found>';
}
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<codigo>
'.$res;

while($array = mysql_fetch_array($query)){
if(strlen($array["Municipio"]) < 2){
$ciudad = "————-";
}else{
$ciudad = $array["Municipio"];
}

$coloniasArray = $array['Colonia'];
$colonias = explode(";", $coloniasArray);
$coloniasTotal = count($colonias);

echo '
<idCodigo>'.$array['CodigoPostal'].'</idCodigo>
<colonia>';
if ($coloniasTotal  > 1) {
	for($x=0; $x<$coloniasTotal; $x++){
	echo '<subcolonia>'.utf8_decode($colonias[$x]).'</subcolonia>';
}
}
else{ 
echo'<subcolonia>'.utf8_decode($array['Colonia']).'</subcolonia>';
}
echo '</colonia>
<municipio>'.utf8_decode($array['Municipio']).'</municipio>
<estado>'.utf8_decode($array['Estado']).'</estado>
<ciudad>'.utf8_decode($ciudad).'</ciudad>';

}
echo '</codigo>';
}
mysql_close($connect)
?>