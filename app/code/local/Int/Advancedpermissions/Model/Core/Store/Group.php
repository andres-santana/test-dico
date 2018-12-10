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
 * @package     Mage_Core
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Store group model
 *
 * @method Mage_Core_Model_Resource_Store_Group _getResource()
 * @method Mage_Core_Model_Resource_Store_Group getResource()
 * @method Mage_Core_Model_Store_Group setWebsiteId(int $value)
 * @method string getName()
 * @method Mage_Core_Model_Store_Group setName(string $value)
 * @method Mage_Core_Model_Store_Group setRootCategoryId(int $value)
 * @method Mage_Core_Model_Store_Group setDefaultStoreId(int $value)
 *
 * @category    Mage
 * @package     Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Int_Advancedpermissions_Model_Core_Store_Group extends Mage_Core_Model_Store_Group
{
    /**
     * Retrieve new (not loaded) Store collection object with group filter
     *
     * @return Mage_Core_Model_Mysql4_Store_Collection
     */
    public function getStoreCollection()
    {
		$original_results = parent::getStoreCollection();
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return $original_results;
		}
		
		$userModel 	= Mage::getSingleton('admin/session')->getUser();
		$userId 	= $userModel->getUserId();
		$userType 	= $userModel->getUsertype();			
		$userWebsiteTable 	= Mage::getConfig()->getTablePrefix()."user_website";
		$dbConnection 		= Mage::getSingleton('core/resource')->getConnection('core_write');
		$storeGroupResult 	= $dbConnection->query("SELECT store_ids FROM ".$userWebsiteTable." WHERE userId='".$userId."'");
		$storeGroupRows		= $storeGroupResult->fetchAll(PDO::FETCH_ASSOC);
		foreach($storeGroupRows as $storeGroupRow){
			if(substr_count($storeGroupRow['store_ids'], ',') >0 ){
				$arrStoreGroupIds = explode(",", $storeGroupRow['store_ids']);
				foreach($arrStoreGroupIds as $arrStoreGroupId){
					$userStoreGroupIds[] = $arrStoreGroupId;
				}
			} else {
				$userStoreGroupIds[] = $storeGroupRow['store_ids'];
			}
		}
		
		if($userType == NULL){
			return Mage::getModel('core/store')
				->getCollection()
				->addGroupFilter($this->getId());
		} else {
			if(in_array($this->getId(), $userStoreGroupIds)){
				return Mage::getModel('core/store')
					->getCollection()
					->addGroupFilter($this->getId());
			}
		}	
    }

    /**
     * Retrieve wersite store objects
     *
     * @return array
     */
    public function getStores()
    {
		$original_results = parent::getStores();
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return $original_results;
		}
		
		if (is_null($this->_stores)) {
            $this->_loadStores();
        }
		
		$currentUrl 		= Mage::helper('core/url')->getCurrentUrl();
		$userModel 	= Mage::getSingleton('admin/session')->getUser();
		$userId 	= $userModel->getUserId();
		$userType 	= $userModel->getUsertype();
		$userWebsiteTable 	= Mage::getConfig()->getTablePrefix()."user_website";
		$store 				= $this->getData();
		$groupId			= $store['group_id'];
		$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
		$result				= $dbConnection->query("SELECT store_ids FROM ".$userWebsiteTable." WHERE userid='".$userId."'");		
		$rows				= $result->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row){
			$strCount = substr_count($row['store_ids'], ',');
			if($strCount>0){
				$expRow = explode(",", $row['store_ids']);
				foreach($expRow as $_expRow){
					$userGroupIds[] = $_expRow;
				}
			} else {
				$userGroupIds[] = $row['store_ids'];
			}		
		}
		
		if($userType == NULL){
			return $this->_stores;
		} else {
			if(substr_count($currentUrl, "/admin/sales_order/index/") > 0){
				return $this->_stores;
			} else {		
				if(in_array($groupId, $userGroupIds)){
					return $this->_stores;
				}	
			}	
		}
    }
}
