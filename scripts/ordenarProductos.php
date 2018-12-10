<?php
ini_set('auto_detect_line_endings', true);
//include("/home/cloudpanel/htdocs/dico.com.mx/pwd.php"); 
/***********************
 * Import/Export Script to run Import/Export profile 
 * from command line or cron. Cleans entries from dataflow_batch_(import|export) table
 ***********************/

$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
umask(0);
Mage::app();

//$file = file('http://prontoform.dico.com.mx/ExistenciasWEB/Centro/ExistWeb.csv');
//$data = [];
//$n=0;

/*if ($file) {
  foreach ($file as $line) {
    $data[$n] = str_getcsv($line);
    $sku = $data[$n][0];
    $tiempo_entrega = $data[$n][1];
  
    $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$data[$n][0]);
    if ($n>0) {
    
    if($_product){
      $attrData = array("tiempo_entrega" => $tiempo_entrega); 
	    Mage::getSingleton('catalog/product_action')->updateAttributes(array($_product->getId()), $attrData, 1);
	    echo "Producto actualizado [SKU][".$sku."] - [Tiempo de entrega][".$tiempo_entrega."]"."<br>";	
	 
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
}*/

$categoryId = 63; 
$defaultPosition = 0; 
$newPosition = 2; 
$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$data[$n][0]);
$category = Mage::getModel('catalog/category')->setStoreId(1)->load($categoryId);
$productPossitions = $category->getProductsPosition();
foreach ($productPossitions as $id => $value){
    if($id == '10101'){
        $productPossitions[$id] = $newPosition;
    }
}

$category->setPostedProducts($productPossitions);
$category->save();

$category2 = Mage::getModel('catalog/category')->setStoreId(1)->load($categoryId);
$productPossitions2 = $category2->getProductsPosition();
foreach ($productPossitions2 as $id => $value){
  echo "id: ".$id;
  echo "--> ".$value;
  echo "<br>";
}
?> 