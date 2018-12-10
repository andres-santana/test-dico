<?php
/**
 * @author      MagePsycho <info@magepsycho.com>
 * @website     http://www.magepsycho.com
 * @category    Export / Import
 */

$mageFilename = '../app/Mage.php';
require_once $mageFilename;
require_once 'reader.php';
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app('admin');
Mage::register('isSecureArea', 1);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 
set_time_limit(0);
ini_set('memory_limit','1024M');
 
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
    //$store_id ='1';
    $store ='1';

    if ($newQty=="99999") {
        
            //foreach ($store_id as $key => $store) {
                //Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '282'), $store);
                $product = Mage::getModel('catalog/product')->load($productId);
                $resource = $product->getResource();
                /*$product->setData('status_stock', '202');*/
                $product->setData('status_stock', '282');
                $resource->saveAttribute($product, 'status_stock');
            //}
        
    }
    elseif ($newQty=="88888") {
        //foreach ($store_id as $key => $store) {
                //Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '281'), $store);
                $product = Mage::getModel('catalog/product')->load($productId);
                $resource = $product->getResource();
                /*$product->setData('status_stock', '200');*/
                $product->setData('status_stock', '281');
                $resource->saveAttribute($product, 'status_stock');
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

        		$newQty2 = 0;
        		$isInStock      = $newQty2 > 0 ? 1 : 0;
        		$stockStatus    = $newQty > 0 ? 1 : 0;
        		$connection->query($sql, array($newQty2, $isInStock, $newQty2, $stockStatus, $productId));

        //  }
    }
    else{
        //foreach ($store_id as $key => $store) {
                //Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '283'), $store);
                $product = Mage::getModel('catalog/product')->load($productId);
                $resource = $product->getResource();
                /*$product->setData('status_stock', '201');*/
                $product->setData('status_stock', '283');
                $resource->saveAttribute($product, 'status_stock');
        //  }
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

/*$csv                = new Varien_File_Csv();
$data               = $csv->getData('stocks.csv'); //path to csv*/

//date_default_timezone_set('UTC-5');
$date = date('YmdHis');
$target_dir = "./uploads/";
$target_file = $target_dir . $date."_". basename($_FILES["xlsfile"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$message = '';
$count   = 1;
if(isset($_POST["submit"])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $check = finfo_file($finfo, $_FILES["xlsfile"]["tmp_name"]);
    if($check == "application/vnd.ms-excel") {
        $uploadOk = 1;
        if (move_uploaded_file($_FILES["xlsfile"]["tmp_name"], $target_file)) {
            // Una vez guardado, podemos trabajar con el
            $data = new Spreadsheet_Excel_Reader();
            $data->read($target_file);
            // 
            $data2 = array();
            //echo ":3:";
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                
                    $sku[$i] = $data->sheets[0]['cells'][$i][1];
                    $stock[$i] = $data->sheets[0]['cells'][$i][2];
                    $data2[0] = $sku[$i];
                    $data2[1] = $stock[$i];
                    //echo "Sku:".$sku[$i];
                    //echo " --> ".$stock[$i];
                    //echo "<br>";
                    if(_checkIfSkuExists($sku[$i])){
        try{
            _updateStocks($data2);
            $message .= $count . '> Hecho :: Cantidad (' . $data2[1] . ') del Sku (' . $data2[0] . ') ha sido actualizada. <br />';
 
        }catch(Exception $e){
            $message .=  $count .'> Error:: Actualizando el campo  Qty (' . $data2[1] . ') del SKU (' . $data2[0] . ') => '.$e->getMessage().'<br />';
        }
    }else{
        $message .=  $count .'> Error:: El producto con Sku (' . $data2[0] . ') no existe.<br />';
    } 

    echo $message;
     $count++;
                    }
                }
            }
        }

//array_shift($data);
 /*
$message = '';
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
}*/

