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

class Int_Advancedpermissions_Block_Permissions_User_Edit_Tab_Roles extends Mage_Adminhtml_Block_Permissions_User_Edit_Tab_Roles
{

    protected function _getSelectedRoles($json=false)
    {
        if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0'){
			return parent::_getSelectedRoles($json=false);
		}
		
		if($this->getRequest()->getParam('user_id') == NULL){
			$roleCollection = Mage::getModel('admin/role')->getCollection();
			$userRoleId = '';
			foreach($roleCollection as $roleValue){
				if($roleValue->getUserRoleType() == 'store_role'){
					$userRoleId	= $roleValue->getId();
				}
			}						
			return array($userRoleId);			
		}
		
		if ( $this->getRequest()->getParam('user_roles') != "" ) {
            return $this->getRequest()->getParam('user_roles');
        }
        /* @var $user Mage_Admin_Model_User */
        $user = Mage::registry('permissions_user');
        //checking if we have this data and we
        //don't need load it through resource model
        if ($user->hasData('roles')) {
            $uRoles = $user->getData('roles');
        } else {
            $uRoles = $user->getRoles();
        }

        if ($json) {
            $jsonRoles = Array();
            foreach($uRoles as $urid) $jsonRoles[$urid] = 0;
            return Mage::helper('core')->jsonEncode((object)$jsonRoles);
        } else {
            return $uRoles;
        }
    }

}
