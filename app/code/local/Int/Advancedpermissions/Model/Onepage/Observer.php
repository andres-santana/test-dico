<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Int_Advancedpermissions_Model_Onepage_Observer
{
	public function success($observer)
	{
		$event 			= $observer->getEvent();
		$eventId 		= $event->getData('order_ids');
		$lastOrderId	= $eventId[0];
		
		
			/***** STORE VENDER PRODUCT DETAILS IN sales_flat_order TABLE *****/
			
			$dbConnection 	= Mage::getSingleton('core/resource')->getConnection('core_write');
			$orderTable		= Mage::getConfig()->getTablePrefix()."sales_flat_order";
			$productTable	= Mage::getConfig()->getTablePrefix()."catalog_product_entity";
			$order = Mage::getModel('sales/order')->load($lastOrderId)->getAllItems();
			$orderItemsCount	=	count($order);
			foreach($order as $_order){
			$i++;
				$_order->getProductId();	
				$productResult 	= $dbConnection->query("SELECT vender_id FROM ".$productTable." WHERE entity_id='".$_order->getProductId()."'");	
				$productRow		= $productResult->fetch(PDO::FETCH_ASSOC);
				if($venderId = $productRow['vender_id']){
					$venderOrderDetails .= "pId-".$_order->getProductId().":vId-".$venderId;
					if($orderItemsCount != $i){
						$venderOrderDetails .= ";";
					}
				} else {
					$venderOrderDetails .= "pId-".$_order->getProductId();
					if($orderItemsCount != $i){
						$venderOrderDetails .= ";";
					}
				}
			}
			$dbConnection->query("UPDATE ".$orderTable." SET vender_ids='".$venderOrderDetails."' WHERE entity_id='".$lastOrderId."'");
		
		return $this;
	}
	
}