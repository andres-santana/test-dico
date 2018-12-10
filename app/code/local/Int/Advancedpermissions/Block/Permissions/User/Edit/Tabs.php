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
 * Admin page left menu
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
{	 
	class Int_Advancedpermissions_Block_Permissions_User_Edit_Tabs extends Mage_Adminhtml_Block_Permissions_User_Edit_Tabs
	{

	}

} else {
		 
	class Int_Advancedpermissions_Block_Permissions_User_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->setId('page_tabs');
			$this->setDestElementId('edit_form');
			$this->setTitle(Mage::helper('adminhtml')->__('User Information'));
		}
		
		protected function _beforeToHtml()
		{
			$userModel 	= Mage::registry('permissions_user');
			$userId 	= $userModel->getUserId();
			$userType 	= $userModel->getUsertype();
			
			$this->addTab('main_section', array(
				'label'     => Mage::helper('adminhtml')->__('User Info'),
				'title'     => Mage::helper('adminhtml')->__('User Info'),
				'content'   => $this->getLayout()->createBlock('adminhtml/permissions_user_edit_tab_main')->toHtml(),
				'active'    => true
			));
			
			if(! empty($userId)){
				$this->addTab('roles_section', array(
					'label'     => Mage::helper('adminhtml')->__('User Role'),
					'title'     => Mage::helper('adminhtml')->__('User Role'),
					'content'   => $this->getLayout()->createBlock('adminhtml/permissions_user_edit_tab_roles', 'user.roles.grid')->toHtml(),
				));
			}
			
			if(empty($userId) || $userType != NULL){
				$this->addTab('roles_section2', array(
						'label'     => Mage::helper('adminhtml')->__('Website'),
						'title'     => Mage::helper('adminhtml')->__('Website'),
						'content'   => $this->getLayout()->createBlock('adminhtml/permissions_user_edit_tab_websites', 'user.roles.grid')->toHtml(),
				));
			}
			
			return parent::_beforeToHtml();
		}

	}

}