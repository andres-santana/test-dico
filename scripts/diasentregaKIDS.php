<?php
ini_set('auto_detect_line_endings', true);
include "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/log.class.php";
//include("/home/cloudpanel/htdocs/dico.com.mx/pwd.php"); 

require_once '/home/cloudpanel/htdocs/casadelaslomas.com/app/Mage.php';
umask(0);
Mage::app();

$file = file('http://prontoform.dico.com.mx/ExistenciasWEB/LomasProd/ExistWeb.csv');
$data = [];
$n=0;
if ($file) {
  foreach ($file as $line) {
    echo "string2";
    $data[$n] = str_getcsv($line);
    $sku = trim($data[$n][0]);
    $tiempo_entrega = trim($data[$n][1]);
  
    $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);

    if ($n>0) {
    
    if($_product && $tiempo_entrega!=0){
      $attrData = array("tiempo_entrega" => $tiempo_entrega); 
      //Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData, 1);
      //Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData, 5);
      Mage::getResourceSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData, 22);
      echo "Producto actualizado [SKU][".$sku."] - [Tiempo de entrega][".$tiempo_entrega."]"."<br>";  
      $log = new Log("tiempoEntrega", "./logs/");
      echo $log->insert("[SKU][".$sku."] - [Tiempo de entrega][".$tiempo_entrega."] - [Portal][Lomas Baby]", false, true, false, "LomasKids");
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