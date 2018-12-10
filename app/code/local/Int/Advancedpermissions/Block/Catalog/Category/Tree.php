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
 * Categories tree block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Int_Advancedpermissions_Block_Catalog_Category_Tree extends Mage_Adminhtml_Block_Catalog_Category_Tree
{

    public function getCategoryCollection()
    {
		$original_results = parent::getCategoryCollection();
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return $original_results;
		}
		$userModel		= Mage::getSingleton('admin/session')->getUser();	
		$userId			= $userModel->getUserId();					
		$userType		= $userModel->getUsertype();					
		$storeId 		= $this->getRequest()->getParam('store', $this->_getDefaultStoreId());
        $collection 	= $this->getData('category_collection');
		$userObj		= new Int_Advancedpermissions_Block_Catalog_Product_Grid();
		$storeModel		= Mage::getModel('core/store');
		$arrUserCatIds 	= array();
		
        if (is_null($collection)) {
            $collection = Mage::getModel('catalog/category')->getCollection();

            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            if($userType != NULL){			
				if($storeId == 0){
					if(Mage::app()->getRequest()->getControllerName() == 'catalog_category'){
						$arrUserCatIds 	= $userObj->getCurrentUserCategory();
						$collection->addAttributeToFilter('entity_id', array('in' => $arrUserCatIds));
					} else {
						$arrCategoryIds = array();
						foreach($userObj->getCurrentUserStore() as $userStoreId){
							$rootCategoryId = Mage::getModel('core/store_group')->load($userStoreId)->getRootCategoryId();
							$allChildCategoryIds	= Mage::getModel('catalog/category')->load($rootCategoryId)->getAllChildren();
							$_allChildCategoryIds	= explode(',', $allChildCategoryIds);
							foreach($_allChildCategoryIds as $allChildCategoryId){
								$arrCategoryIds[] = $allChildCategoryId;
							}
						}
						$collection->addAttributeToFilter('entity_id', array('in' => $arrCategoryIds));
					}
				} else {
					if(Mage::app()->getRequest()->getControllerName() == 'catalog_category'){
						$groupId		= $storeModel->load($storeId)->getGroupId();
						$arrUserCatIds 	= $userObj->getUserCategory($userId, $groupId);
						$collection->addAttributeToFilter('entity_id', array('in' => $arrUserCatIds));
					}
				}  				
			}
			
			$collection->addAttributeToSelect('name')
                ->addAttributeToSelect('is_active')
                ->setProductStoreId($storeId)
                ->setLoadProductCount($this->_withProductCount)
                ->setStoreId($storeId);
				
            $this->setData('category_collection', $collection);
			
        } else {
		
			/* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            if($userType != NULL){			
				if($storeId == 0){
					if(Mage::app()->getRequest()->getControllerName() == 'catalog_category'){
						$arrUserCatIds 	= $userObj->getCurrentUserCategory();
						$collection->addAttributeToFilter('entity_id', array('in' => $arrUserCatIds));
					} else {
						$arrCategoryIds = array();
						foreach($userObj->getCurrentUserStore() as $userStoreId){
							$rootCategoryId = Mage::getModel('core/store_group')->load($userStoreId)->getRootCategoryId();
							$allChildCategoryIds	= Mage::getModel('catalog/category')->load($rootCategoryId)->getAllChildren();
							$_allChildCategoryIds	= explode(',', $allChildCategoryIds);
							foreach($_allChildCategoryIds as $allChildCategoryId){
								$arrCategoryIds[] = $allChildCategoryId;
							}
						}
						
						$collection->addAttributeToFilter('entity_id', array('in' => $arrCategoryIds));
					}
				} else {
					if(Mage::app()->getRequest()->getControllerName() == 'catalog_category'){
						$groupId		= $storeModel->load($storeId)->getGroupId();
						$arrUserCatIds 	= $userObj->getUserCategory($userId, $groupId);
						$collection->addAttributeToFilter('entity_id', array('in' => $arrUserCatIds));
					}
				}
			}
			
		}
		
        return $collection;
	
    }
	
	 public function getAddRootButtonHtml()
    {
        $original_results = parent::getAddRootButtonHtml();
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return $original_results;
		}
		
		$userModel 	= Mage::getSingleton('admin/session')->getUser();	
		$userId 	= $userModel->getUserId();	
		$userType 	= $userModel->getUsertype();	
		
		if($userType == NULL){
			return $original_results;
		} else {
			return false;
		}
    }
}
