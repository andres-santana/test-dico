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
 * http://localhost/freshmagento/mage1702/index.php/admin/system_config/edit/section/general/key/6599a215d503cbe97a4593703c756c48/
 * http://localhost/freshmagento/mage1702/index.php/admin/system_config/edit/section/general/website/web2/key/6599a215d503cbe97a4593703c756c48/
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Store switcher block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Int_Advancedpermissions_Block_Store_Switcher extends Mage_Adminhtml_Block_Store_Switcher
{

    /**
     * Deprecated
     */
    public function getWebsiteCollection()
    {
        $original_results = parent::getWebsiteCollection();
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return $original_results;
		}
		
		$collection 		= Mage::getModel('core/website')->getResourceCollection();
        $userModel 			= Mage::getSingleton('admin/session')->getUser();
        $userId 			= $userModel->getUserId();
        $userType 			= $userModel->getUsertype();
       
        if($userType == NULL)
        {
	        /* Original Code */
	        $websiteIds = $this->getWebsiteIds();
	        if (!is_null($websiteIds)) {
	            $collection->addIdFilter($this->getWebsiteIds());
	        }
	        /**/
        } else {

			$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
			if(Mage::app()->getRequest()->getControllerName() != 'catalog_category'){
				$collection->addIdFilter($userObj->getCurrentUserWebsite());
			} else {
				$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
				$dbConnection 		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userResult			= $dbConnection->query("SELECT `website_id`,`category_id` FROM ".$userCategoryTable." WHERE user_id='".$userId."'");
				$userRows			= $userResult->fetchAll(PDO::FETCH_ASSOC);
				$arrUserWebIds		= array();	
				if(count($userRows) > 0){
					foreach($userRows as $userRow){
						if($userRow['category_id']){
							$arrUserWebIds[] = 	$userRow['website_id'];
						}
					}
				}
				$collection->addIdFilter(array_unique($arrUserWebIds));
			}
		}
		
        return $collection->load();
    }
	
	/**
     * Get store views for specified store group
     *
     * @param Mage_Core_Model_Store_Group $group
     * @return array
     */
    public function getStores($group)
    {
        
		if (!$group instanceof Mage_Core_Model_Store_Group) {
            $group = Mage::app()->getGroup($group);
        }
        $stores = $group->getStores();
        if ($storeIds = $this->getStoreIds()) {
            foreach ($stores as $storeId => $store) {
                if (!in_array($storeId, $storeIds)) {
                    unset($stores[$storeId]);
                } 
            }
        }
		
		$userModel 	= Mage::getSingleton('admin/session')->getUser();
		$userId 	= $userModel->getUserId();
		$userType 	= $userModel->getUsertype();
		if($userType != NULL){	
			if($group->getId()){
				$dbConnection 		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userCategoryTable	= Mage::getConfig()->getTablePrefix()."user_category";
				$userQuery			= $dbConnection->query("
											SELECT `storeview_id` FROM ".$userCategoryTable." WHERE `store_id`='".$group->getId()."' AND `user_id`='".$userId."' 
										");
				$userRow			= $userQuery->fetch(PDO::FETCH_ASSOC);
				if(count($userRow) > 0){
					$arrUserStoreIds = explode(",", $userRow['storeview_id']);
					$stores = Mage::getModel('core/store')->getCollection()
								->addFieldToFilter('store_id', $arrUserStoreIds);
				}
			}
		}	
		return $stores;
    }
	
    /**
     * Deprecated
     */
    public function getGroupCollection($website)
    {
		$userModel	= Mage::getSingleton('admin/session')->getUser();
		$userId		= $userModel->getUserId();
		$userType	= $userModel->getUsertype();
		if($userType == NULL){
			if (!$website instanceof Mage_Core_Model_Website) {
				$website = Mage::getModel('core/website')->load($website);
			}
			return $website->getGroupCollection();
		} else {
			
			if(Mage::app()->getRequest()->getControllerName() == 'catalog_category'){
				$websiteId			= $website->getId();
				$userCategoryTable	= Mage::getConfig()->getTablePrefix()."user_category";
				$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userQuery			= $dbConnection->query("
											SELECT `store_id`,`category_id` FROM $userCategoryTable WHERE `website_id`='$websiteId' AND `user_id`='$userId'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				$arrUserStoreIds	= array();
				
				if(count($userRows) > 0){
					foreach($userRows as $userRow){
						
						if($userRow['category_id']){
							$userCategoryIds = explode(',', $userRow['category_id']);
							
							foreach($userCategoryIds as $userCategoryId){
								if(Mage::getModel('catalog/category')->load($userCategoryId)->getLevel() == 1){
									$arrUserStoreIds[] = $userRow['store_id'];
								}
							}
						}
					}
					sort($arrUserStoreIds);
				}
				return Mage::getModel('core/store_group')
									->getCollection()
									->addFieldToFilter('main_table.group_id', array('in' => array_unique($arrUserStoreIds)));
									
			
			} else {
				$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
				return Mage::getModel('core/store_group')
									->getCollection()
									->addFieldToFilter('main_table.group_id', array('in' => $userObj->getUserStore($userId, $website->getId())));
			}
		}
    }

    /**
     * Deprecated
     */
    public function getStoreCollection($group)
    {
        $userModel	= Mage::getSingleton('admin/session')->getUser();
		$userId		= $userModel->getUserId();
		$userType	= $userModel->getUsertype();
		if($userType == NULL){
			if (!$group instanceof Mage_Core_Model_Store_Group) {
				$group = Mage::getModel('core/store_group')->load($group);
			}
			$stores = $group->getStoreCollection();
			$_storeIds = $this->getStoreIds();
			if (!empty($_storeIds)) {
				$stores->addIdFilter($_storeIds);
			}
			return $stores;
		} else {
			$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
			return Mage::getModel('core/store')->getcollection()
										->addFieldToFilter('main_table.store_id', array('in' => $userObj->getUserStoreView($userId, $group->getId())));
		}
    }

    /**
     * Set/Get whether the switcher should show default option
     *
     * @param bool $hasDefaultOption
     * @return bool
     */
    public function hasDefaultOption($hasDefaultOption = null)
    {
		$original_results = parent::hasDefaultOption($hasDefaultOption = null);
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return $original_results;
		}
			
        if (null !== $hasDefaultOption) {
            //$this->_hasDefaultOption = $hasDefaultOption;
        }
        //return $this->_hasDefaultOption;
    }

}