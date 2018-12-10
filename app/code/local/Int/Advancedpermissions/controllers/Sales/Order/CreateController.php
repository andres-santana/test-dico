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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales orders creation process controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
 
require_once('Mage/Adminhtml/controllers/Sales/Order/CreateController.php');
class Int_Advancedpermissions_Sales_Order_CreateController extends Mage_Adminhtml_Sales_Order_CreateController
{

    /**
     * Saving quote and create order
     */
    public function saveAction()
    {
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::saveAction();
		} else {
        
			try {
				$this->_processActionData('save');
				if ($paymentData = $this->getRequest()->getPost('payment')) {
					$this->_getOrderCreateModel()->setPaymentData($paymentData);
					$this->_getOrderCreateModel()->getQuote()->getPayment()->addData($paymentData);
				}

				$order = $this->_getOrderCreateModel()
					->setIsValidate(true)
					->importPostData($this->getRequest()->getPost('order'))
					->createOrder();

					
				/***** STORE VENDER PRODUCT DETAILS IN sales_flat_order TABLE *****/
				
				$dbConnection 		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$orderTable			= Mage::getConfig()->getTablePrefix()."sales_flat_order";
				$productTable		= Mage::getConfig()->getTablePrefix()."catalog_product_entity";
				$lastOrderId		= $order->getId();
				$adminOrder 		= Mage::getModel('sales/order')->load($lastOrderId)->getAllItems();
				$orderItemsCount	= count($adminOrder);
				foreach($adminOrder as $_order){
				$i++;
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
					
					
				$this->_getSession()->clear();
				Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The order has been created.'));
				$this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
			} catch (Mage_Payment_Model_Info_Exception $e) {
				$this->_getOrderCreateModel()->saveQuote();
				$message = $e->getMessage();
				if( !empty($message) ) {
					$this->_getSession()->addError($message);
				}
				$this->_redirect('*/*/');
			} catch (Mage_Core_Exception $e){
				$message = $e->getMessage();
				if( !empty($message) ) {
					$this->_getSession()->addError($message);
				}
				$this->_redirect('*/*/');
			}
			catch (Exception $e){
				$this->_getSession()->addException($e, $this->__('Order saving error: %s', $e->getMessage()));
				$this->_redirect('*/*/');
			}
		}
	}
	
}	