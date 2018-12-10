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


//class Mage_Adminhtml_Block_Report_Grid_Abstract extends Mage_Adminhtml_Block_Widget_Grid
class Int_Advancedpermissions_Block_Report_Grid_Abstract extends Mage_Adminhtml_Block_Report_Grid_Abstract
{
	
    /**
     * Get allowed store ids array intersected with selected scope in store switcher
     *
     * @return  array
     */
    protected function _getStoreIds()
    {
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::_getStoreIds();
		}
		
        $filterData = $this->getFilterData();
        if ($filterData) {
            $storeIds = explode(',', $filterData->getData('store_ids'));
        } else {
            $storeIds = array();
        }
        // By default storeIds array contains only allowed stores
        $allowedStoreIds = array_keys(Mage::app()->getStores());
        // And then array_intersect with post data for prevent unauthorized stores reports
        $storeIds = array_intersect($allowedStoreIds, $storeIds);
        // If selected all websites or unauthorized stores use only allowed
        
		$userModel	= Mage::getSingleton('admin/session')->getUser();
		$userType	= $userModel->getUsertype();
		if($userType != NULL){
			$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
			if (empty($storeIds)) {
				$storeIds = $userObj->getCurrentUserStoreView();
			} else {
				$storeIds = array_intersect($storeIds, $userObj->getCurrentUserStoreView());
			}
		} else {
			if (empty($storeIds)) {
				$storeIds = $allowedStoreIds;
			}
		}
		
		
        // reset array keys
        $storeIds = array_values($storeIds);

        return $storeIds;
    }
}
