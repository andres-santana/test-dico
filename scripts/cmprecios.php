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

$file = '/home/cloudpanel/htdocs/dico.com.mx/var/import/cambio_masivo_precios.xls';
$data = new Spreadsheet_Excel_Reader();
$data->read($file);
$store_count = 10;
for($n=0; $n<$store_count; $n++){
	switch($n){
		case '0': $store_code = 1; break;
		case '1': $store_code = 20; break;
		case '2': $store_code = 5; break;
		case '3': $store_code = 14; break;
		case '4': $store_code = 11; break;
		case '5': $store_code = 7; break;
		case '6': $store_code = 8; break;
		case '7': $store_code = 12; break;
		case '8': $store_code = 13; break;
		case '9': $store_code = 15; break;
	}
	for ($i = 1; $i <= $data->sheets[$n]['numRows']; $i++) {
		if($i>1){
			$sku[$i] = $data->sheets[$n]['cells'][$i][1];
			$precio[$i] = $data->sheets[$n]['cells'][$i][4];
			$precio_especial[$i] = $data->sheets[$n]['cells'][$i][5];
			$precio_agrupado[$i] = $data->sheets[$n]['cells'][$i][6];
			$precio_especial_agrupado[$i] = $data->sheets[$n]['cells'][$i][7];
			$store_code_id[$i] = $store_code;
			$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku[$i]);
			$attrData[$i] = array("price" => $precio[$i], "precio_agrupado" => $precio_agrupado[$i], "precio_especial_agrupado" => $precio_especial_agrupado[$i], "precio_especial_agrupado" => $precio_especial_agrupado[$i], "special_price" => $precio_especial[$i]); 
			if($n==0){
				Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], 0);
			}
			Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_code_id[$i]);
			echo $sku[$i]." - ".$store_code_id[$i];
			echo "<br>";
		}
	}
}
/*for($i=1; $i<count($data); $i++)
{
  $sku[$i] = $data[$i][0];
  $sku2[$i] = $data[0][$i][0];
  $website_array[$i] = $data[$i][1];
  $precio[$i] = $data[$i][2];
  $precio_agrupado[$i] = $data[$i][3];
  $precio_especial_agrupado[$i] = $data[$i][4];
  $type[$i] = $data[$i][5];
  $name[$i] = $data[$i][6];
  $precio_especial[$i] = $data[$i][7];
  $store_code_id[$i] = explode(",", $website_array[$i]);
  $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku[$i]);
  $productIds[$i] = $_product->getId();
  $attrData[$i] = array("price" => $precio[$i], "precio_agrupado" => $precio_agrupado[$i], "precio_especial_agrupado" => $precio_especial_agrupado[$i], "precio_especial_agrupado" => $precio_especial_agrupado[$i], "special_price" => $precio_especial[$i]); 
  
  /*echo $sku[$i];
  echo "<br>";
  echo $sku2[$i];
  echo "<br>";
  /*for($k=0; $k<count($store_code_id[$i]); $k++)
  {
	if($store_code_id[$i][$k]=="dico_df")
	{
	  $store_id[$i][0] = 1;
      Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][0]);
	}
	elseif($store_code_id[$i][$k]=="dico_centro")
	{
	  $store_id[$i][1] = 10;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][1]);
	}
	elseif($store_code_id[$i][$k]=="dico_oriente")
	{
	  $store_id[$i][2] = 5;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][2]);
	}
	elseif($store_code_id[$i][$k]=="dico_suroeste")
	{
	  $store_id[$i][3] = 14;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][3]);
	}
	elseif($store_code_id[$i][$k]=="dico_bajio")
	{
	  $store_id[$i][4] = 11;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][4]);
	}
	elseif($store_code_id[$i][$k]=="dico_occidente")
	{
	  $store_id[$i][5] = 7;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][5]);
	}
	elseif($store_code_id[$i][$k]=="dico_pacifico")
	{
	  $store_id[$i][6] = 8;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][6]);
	}
	elseif($store_code_id[$i][$k]=="dico_noroeste")
	{
	  $store_id[$i][7] = 12;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][7]);
	}
	elseif($store_code_id[$i][$k]=="dico_sureste")
	{
	  $store_id[$i][8] = 13;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][8]);
	}
	elseif($store_code_id[$i][$k]=="dico_norte")
	{
	  $store_id[$i][9] = 15;
	  Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData[$i], $store_id[$i][9]);
	}	
  }
}*/
echo "Productos guardados";
?> 