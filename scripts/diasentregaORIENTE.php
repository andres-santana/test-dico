<?php
ini_set('auto_detect_line_endings', true);
include "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/log.class.php";
require_once '/home/cloudpanel/htdocs/dico.com.mx/app/Mage.php';
umask(0);
Mage::app();

$file = file('http://prontoform.dico.com.mx/ExistenciasWEB/Centro/ExistWeb.csv');
$data = [];
$n=0;
date_default_timezone_set('America/Mexico_City');
$fecha = date('ymd_His');

if ($file) {
  foreach ($file as $line) {
    $data[$n] = str_getcsv($line);
    $sku = trim($data[$n][0]);
    $tiempo_entrega = trim($data[$n][1]);
  
    $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
    if ($n>0) {
    
    if($_product && $tiempo_entrega!=0){
      $attrData = array("tiempo_entrega" => $tiempo_entrega); 
	    Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData, 5);
	    echo "Producto actualizado [SKU][".$sku."] - [Tiempo de entrega][".$tiempo_entrega."]"."<br>";	
	    $log = new Log("tiempoEntrega_".$fecha, "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/oriente/");
      echo $log->insert($sku.",".$tiempo_entrega, false, true, false);
    }
    else{
	    echo "sku no encontrado ".$sku."<br>";
    }
    }
  
  $n++;
  }
}
else{
	echo "no se puede abrir el archivo";
}

 
?> 
