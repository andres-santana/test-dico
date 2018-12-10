<?php
include("/home/cloudpanel/htdocs/dico.com.mx/pwd.php"); 
/***********************
 * Import/Export Script to run Import/Export profile 
 * from command line or cron. Cleans entries from dataflow_batch_(import|export) table
 ***********************/

$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
umask(0);
Mage::app();

$file = '/var/www/html/dico.com.mx/var/import/import_existencias.csv';
$csv = new Varien_File_Csv();
$data = $csv->getData($file);
 
for($i=1; $i<count($data); $i++)
{
  $sku[$i] = $data[$i][0];
  $_status[$i] = $data[$i][1];
  if($_status[$i]=="Activado"):
  $status[$i] = 1;
  elseif($_status[$i]=="Inactivo"):
  $status[$i] = 2;
  endif;
  $website_array[$i] = $data[$i][2];
  $store_code_id[$i] = explode(",", $website_array[$i]);
  $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku[$i]);
  if($_product){
  $productIds[$i] = $_product->getId();
  $attrData[$i] = array("status" => $status[$i]); 

  for($k=0; $k<count($store_code_id[$i]); $k++)
  {
	if($store_code_id[$i][$k]=="dico_df")
	{

		//Mage::getModel('catalog/product_status')->updateProductStatus($_product->getId(), 1, Mage_Catalog_Model_Product_Status::STATUS_ENABLED); 
		Mage::getModel('catalog/product_status')->updateProductStatus($productIds[$i], 1,$status[$i]);
		
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
  }
  else{
	echo "sku no encontrado &quote;".$sku[$i]."<br>";
}
}
 
echo "Productos guardados";
?> 