<?php
ini_set('auto_detect_line_endings', true);  
include "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/log.class.php";
require_once '/home/cloudpanel/htdocs/dico.com.mx/app/Mage.php';
umask(0);
Mage::app();

$file = file('http://prontoform.dico.com.mx/ExistenciasWEB/Centro/ExistRemates.csv');
$data = [];
$n=0;
date_default_timezone_set('America/Mexico_City');
$fecha = date('ymd_His');

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
 
function _updateStocks($data,$log_dico,$log_oriente,$log_suroeste){
    $connection     = _getConnection('core_write');
    $sku            = trim($data[0]);
    $newQty         = trim($data[1]);
    $productId      = _getIdFromSku($sku);
    $attributeId    = _getAttributeId();
    //$store_id ='1';
    $store ='1';

    if ($newQty=="99999") {
        
            //foreach ($store_id as $key => $store) {
                //Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '282'), $store);
                $product = Mage::getModel('catalog/product')->load($productId);
                $resource = $product->getResource();
                
                $product->setStoreId(0)->setData('status_stock', '282');
                $resource->saveAttribute($product, 'status_stock');

                $product->setStoreId(1)->setData('status_stock', '282');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_dico->insert($sku.",".$newQty, false, true, false);
                endif;

                $product->setStoreId(5)->setData('status_stock', '282');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_oriente->insert($sku.",".$newQty, false, true, false);
                endif;

                $product->setStoreId(14)->setData('status_stock', '282');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_suroeste->insert($sku.",".$newQty, false, true, false);
                endif;

            //}
        
    }
    elseif ($newQty=="88888") {
        //foreach ($store_id as $key => $store) {
                //Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), array("status_stock" => '281'), $store);
                $product = Mage::getModel('catalog/product')->load($productId);
                $resource = $product->getResource();

                $product->setStoreId(0)->setData('status_stock', '281');
                $resource->saveAttribute($product, 'status_stock');

                $product->setStoreId(1)->setData('status_stock', '281');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_dico->insert($sku.",".$newQty, false, true, false);
                endif;

                $product->setStoreId(5)->setData('status_stock', '281');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_oriente->insert($sku.",".$newQty, false, true, false);
                endif;
                
                $product->setStoreId(14)->setData('status_stock', '281');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_suroeste->insert($sku.",".$newQty, false, true, false);
                endif;


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
                
                $product->setStoreId(0)->setData('status_stock', '283');
                $resource->saveAttribute($product, 'status_stock');

                $product->setStoreId(1)->setData('status_stock', '283');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_dico->insert($sku.",".$newQty, false, true, false);
                endif;

                $product->setStoreId(5)->setData('status_stock', '283');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_oriente->insert($sku.",".$newQty, false, true, false);
                endif;
                
                $product->setStoreId(14)->setData('status_stock', '283');
                if($resource->saveAttribute($product, 'status_stock')):
                    echo $log_suroeste->insert($sku.",".$newQty, false, true, false);
                endif;

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

/**
Ejecutamos el cambio
*/



if ($file) {
    $fecha = date('ymd_His');
    $log_dico = new Log("remates_".$fecha, "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/dico/");
    $log_oriente = new Log("remates_".$fecha, "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/oriente/");
    $log_suroeste = new Log("remates_".$fecha, "/home/cloudpanel/htdocs/dico.com.mx/scripts/logs/suroeste/");
  foreach ($file as $line) {
    $data[$n] = str_getcsv($line);
    
    $sku = $data[$n][0];
    $stock = $data[$n][1];
    $data2[0] = trim($sku);
    $data2[1] = trim($stock);

    $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$data2[0]);
    if ($n>0) {
    if($_product){
      if(_checkIfSkuExists($data2[0])){
        try{
            _updateStocks($data2,$log_dico,$log_oriente,$log_suroeste);
                $message .= $count . '> Hecho :: Cantidad (' . $data2[1] . ') del Sku (' . $data2[0] . ') ha sido actualizada. <br />';
 
        }catch(Exception $e){
            $message .=  $count .'> Error:: Actualizando el campo  Qty (' . $data2[1] . ') del SKU (' . $data2[0] . ') => '.$e->getMessage().'<br />';
        }
      }else{
        $message .=  $count .'> Error:: El producto con Sku (' . $data2[0] . ') no existe.<br />';
      } 
	 
    }
    else{
	    echo "sku no encontrado ".$sku."<br>";
    }
    }
  
  $n++;
       $count++;
  }
    echo $message;

}
else{
	echo "no se puede abrir el archivo";
}


 ?> 