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
 * Catalog product controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
include_once("Mage/Adminhtml/controllers/Catalog/ProductController.php"); 
class Int_Advancedpermissions_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * Save product action
     */
    public function saveAction()
    {
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::saveAction();
		} else {	
			$storeId        = $this->getRequest()->getParam('store');
			$redirectBack   = $this->getRequest()->getParam('back', false);
			$productId      = $this->getRequest()->getParam('id');
			$isEdit         = (int)($this->getRequest()->getParam('id') != null);

			$data = $this->getRequest()->getPost();
			
			if ($data) {
				$version = Mage::getVersion();
				
				if(substr_count($version, '1.7') > 0){
					$this->_filterStockData($data['product']['stock_data']);
				} else {	
					if (!isset($data['product']['stock_data']['use_config_manage_stock'])) {
					$data['product']['stock_data']['use_config_manage_stock'] = 0;
					}
				}
				
				$product = $this->_initProductSave();

				try {
					$product->save();
					$productId = $product->getId();

					
					/**
					 * Do copying data to stores
					 */
					if (isset($data['copy_to_stores'])) {
						foreach ($data['copy_to_stores'] as $storeTo=>$storeFrom) {
							$newProduct = Mage::getModel('catalog/product')
								->setStoreId($storeFrom)
								->load($productId)
								->setStoreId($storeTo)
								->save();
						}
					}

					Mage::getModel('catalogrule/rule')->applyAllRulesToProduct($productId);

					$this->_getSession()->addSuccess($this->__('The product has been saved.'));
				} catch (Mage_Core_Exception $e) {
					$this->_getSession()->addError($e->getMessage())
						->setProductData($data);
					$redirectBack = true;
				} catch (Exception $e) {
					Mage::logException($e);
					$this->_getSession()->addError($e->getMessage());
					$redirectBack = true;
				}
			
				/******  ADD COLUMN verder_id IN catalog_product_entity AND SAVED VALUE *****/
				$dbConnection 	= Mage::getSingleton('core/resource')->getConnection('core_write');		
				$tableName 		= Mage::getConfig()->getTablePrefix()."catalog_product_entity";
				
				$venderId 		= $data['product']['vender_id'];			
				
				$userType = Mage::getModel('admin/user')->load($venderId)->getUsertype();				
				
				$dbConnection->query("UPDATE ".$tableName." SET vender_id='".$venderId."' WHERE entity_id='".$productId."'");
				
				$productObj		= Mage::getModel('catalog/product');
				$_product		= $productObj->load($productId)->getTypeInstance(true)->getChildrenIds($productObj->getId(), false);
				if(!empty($_product)){
					unset($assocProId);
					foreach($_product as $arrPro){
						if(is_array($arrPro) == true){
							foreach($arrPro as $proId){
								if($proId){
									$dbConnection->query("UPDATE ".$tableName." SET vender_id='".$venderId."' WHERE entity_id='".$proId."'");
								}
							}
						}	
					}	
				}
			}

			if ($redirectBack) {
				$this->_redirect('*/*/edit', array(
					'id'    => $productId,
					'_current'=>true
				));
			} elseif($this->getRequest()->getParam('popup')) {
				$this->_redirect('*/*/created', array(
					'_current'   => true,
					'id'         => $productId,
					'edit'       => $isEdit
				));
			} else {
				$this->_redirect('*/*/', array('store'=>$storeId));
			}
		}
    }
}
