<?php
include("/home/cloudpanel/htdocs/dico.com.mx/pwd.php"); 
/***********************
 * Import/Export Script to run Import/Export profile 
 * from command line or cron. Cleans entries from dataflow_batch_(import|export) table
 ***********************/

$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
require_once 'reader.php';
umask(0);
Mage::app();
error_reporting(E_ALL); 
ini_set("display_errors", 1); 
echo "Leyendo archivo...<br>";
$file = '/home/cloudpanel/htdocs/dico.com.mx/var/import/ActualizaTiempoEntregaweb.xls';
$data = new Spreadsheet_Excel_Reader();
$data->read($file);
$store_count = 4;
$m=0;
for($n=0; $n<$store_count; $n++){
	switch($n){
		case '0': $store_code = 1; break;
		case '1': $store_code = 20; break;
		case '2': $store_code = 5; break;
		case '3': $store_code = 14; break;
		/*case '4': $store_code = 11; break;
		case '5': $store_code = 7; break;
		case '6': $store_code = 8; break;
		case '7': $store_code = 12; break;
		case '8': $store_code = 13; break;
		case '9': $store_code = 15; break;*/	
	}
	for ($i = 1; $i <= $data->sheets[$m]['numRows']; $i++) {
		if($i>1){
			$sku[$i] = $data->sheets[$m]['cells'][$i][1];
			$tiempo[$i] = $data->sheets[$m]['cells'][$i][2];
			/*$precio_especial[$i] = $data->sheets[$n]['cells'][$i][5];
			$precio_agrupado[$i] = $data->sheets[$n]['cells'][$i][6];
			$precio_especial_agrupado[$i] = $data->sheets[$n]['cells'][$i][7];*/
			$store_code_id[$i] = $store_code;
			$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku[$i]);
			$attrData[$i] = array("tiempo_entrega" => $tiempo[$i]); 
			if($n==0){
				if($_product){
				Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], 0);
				Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], 1);
				echo $sku[$i]." - ".$store_code_id[$i];
				echo "<br>";
				}
				else{
					echo "No existe SKU: ".$sku[$i];
					echo "<br />";
				}
			}
			if($_product){
				Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_code_id[$i]);
			echo $sku[$i]." - ".$store_code_id[$i];
			echo "<br>";
			}
			else{
					echo "No existe SKU: ".$sku[$i];
					echo "<br />";
				}
		}
	}
}
echo "Productos guardados";
?> 