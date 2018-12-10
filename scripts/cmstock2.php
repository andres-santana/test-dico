<?php
/**
 * @author      MagePsycho <info@magepsycho.com>
 * @website     http://www.magepsycho.com
 * @category    Export / Import
 */
echo "1";
$mageFilename = '../app/Mage.php';
require_once $mageFilename;
require_once 'getCsv.php';

Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app('admin');
Mage::register('isSecureArea', 1);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

 echo "3";
set_time_limit(0);
ini_set('memory_limit','1024M');
echo "4";

function _getConnection($type = 'core_read'){
    return Mage::getSingleton('core/resource')->getConnection($type);
}
 
function _getTableName($tableName){
    return Mage::getSingleton('core/resource')->getTableName($tableName);
}
 
function _getAttributeId($attribute_code = 'price'){
    $connection = _getConnection('core_read');
    $sql = "SELECT attribute_id
                FROM " . _getTableName('eav_attribute') . "
            WHERE
                entity_type_id = ?
                AND attribute_code = ?";
    $entity_type_id = _getEntityTypeId();
    return $connection->fetchOne($sql, array($entity_type_id, $attribute_code));
}
 
function _getEntityTypeId($entity_type_code = 'catalog_product'){
    $connection = _getConnection('core_read');
    $sql        = "SELECT entity_type_id FROM " . _getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
    return $connection->fetchOne($sql, array($entity_type_code));
}
 
function _checkIfSkuExists($sku){
    $connection = _getConnection('core_read');
    $sql        = "SELECT COUNT(*) AS count_no FROM " . _getTableName('catalog_product_entity') . " WHERE sku = ?";
    $count      = $connection->fetchOne($sql, array($sku));
    if($count > 0){
        return true;
    }else{
        return false;
    }
}
 
function _getIdFromSku($sku){
    $connection = _getConnection('core_read');
    $sql        = "SELECT entity_id FROM " . _getTableName('catalog_product_entity') . " WHERE sku = ?";
    return $connection->fetchOne($sql, array($sku));
}
 
function _updateStocks($data){
    $connection     = _getConnection('core_write');
    $sku            = $data[0];
    $newQty         = $data[1];
    $productId      = _getIdFromSku($sku);
    $attributeId    = _getAttributeId();
 	$store_id = array('1','18','4','12');

 	if ($newQty=="99999") {
 		
 			foreach ($store_id as $key => $store) {
 				//Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '282'), $store);
 				$product = Mage::getModel('catalog/product')->load($productId);
				$resource = $product->getResource();
				$product->setData('status_stock', '282');
				$resource->saveAttribute($product, 'status_stock');
 			}
 		
 	}
 	elseif ($newQty=="88888") {
 		foreach ($store_id as $key => $store) {
 				//Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '281'), $store);
 				$product = Mage::getModel('catalog/product')->load($productId);
				$resource = $product->getResource();
				$product->setData('status_stock', '281');
				$resource->saveAttribute($product, 'status_stock');
 			}
 	}
 	else{
 		foreach ($store_id as $key => $store) {
 				//Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '283'), $store);
 				$product = Mage::getModel('catalog/product')->load($productId);
				$resource = $product->getResource();
				$product->setData('status_stock', '283');
				$resource->saveAttribute($product, 'status_stock');
 			}
    	$sql            = "UPDATE " . _getTableName('cataloginventory_stock_item') . " csi,
    	                   " . _getTableName('cataloginventory_stock_status') . " css
    	                   SET
    	                   csi.qty = ?,
    	                   csi.is_in_stock = ?,
    	                   css.qty = ?,
    	                   css.stock_status = ?
    	                   WHERE
    	                   csi.product_id = ?
    	                   AND csi.product_id = css.product_id";
    	$isInStock      = $newQty > 0 ? 1 : 0;
    	$stockStatus    = $newQty > 0 ? 1 : 0;
    	$connection->query($sql, array($newQty, $isInStock, $newQty, $stockStatus, $productId));
	}
}

//$csv = new Varien_File_Csv();
$file = 'stocks.csv';

$data = readCSV($file, $column_name=true);
 echo "Data:".$data;
//$data = $csv->getData('stocks.csv'); //path to csv
//array_shift($data);

if($data){
    print_r($data);


$message = 'message: ';
$count   = 1;
foreach($data as $_data){
    if(_checkIfSkuExists($_data[0])){
        try{
            _updateStocks($_data);
            $message .= $count . '> Hecho :: Cantidad (' . $_data[1] . ') del Sku (' . $_data[0] . ') ha sido actualizada. <br />';
 
        }catch(Exception $e){
            $message .=  $count .'> Error:: Actualizando el campo  Qty (' . $_data[1] . ') del SKU (' . $_data[0] . ') => '.$e->getMessage().'<br />';
        }
    }else{
        $message .=  $count .'> Error:: El producto con Sku (' . $_data[0] . ') no existe.<br />';
    }
    $count++;
}
	echo $message;
}
else{
    echo "no se pudo leer el archivo";
}
*/