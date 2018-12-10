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
 * Tax Rate Titles Renderer
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */

class Int_Advancedpermissions_Block_Tax_Rate_Title extends Mage_Adminhtml_Block_Tax_Rate_Title
{

	public function getStores()
	{
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::getStores();
		}
		
		$stores = $this->getData('stores');
		
		$userModel	= Mage::getSingleton('admin/session')->getUser();
		$userType	= $userModel->getUsertype();
		
		if (is_null($stores)) {
			$stores = Mage::getModel('core/store')->getResourceCollection();
			if($userType != NULL){
				$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
				$stores->addFieldToFilter('store_id', $userObj->getCurrentUserStoreView());
			}
			$stores->setLoadDefault(false)->load();
			$this->setData('stores', $stores);
		}
		return $stores;
	}
}

