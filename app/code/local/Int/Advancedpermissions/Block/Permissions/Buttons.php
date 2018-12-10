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
if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
{
	class Int_Advancedpermissions_Block_Permissions_Buttons extends Mage_Adminhtml_Block_Permissions_Buttons
	{

	}
	
} else {

	class Int_Advancedpermissions_Block_Permissions_Buttons extends Mage_Adminhtml_Block_Template
	{

		public function __construct()
		{
			parent::__construct();
			$this->setTemplate('permissions/userinfo.phtml');
		}

		protected function _prepareLayout()
		{
			
			$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
			$adminRoleTable 	= Mage::getConfig()->getTablePrefix()."admin_role";
			$adminRoleResult	= $dbConnection->query("SELECT role_id FROM ".$adminRoleTable." WHERE `user_role_type`='store_role'");
			$adminRoleRow		= $adminRoleResult->fetch(PDO::FETCH_ASSOC);
			$adminRoleId		= $adminRoleRow['role_id'];
			
			$currentRoleId =Mage::app()->getRequest()->getParam('rid');
			
			$this->setChild('backButton',
				$this->getLayout()->createBlock('adminhtml/widget_button')
					->setData(array(
						'label'     => Mage::helper('adminhtml')->__('Back'),
						'onclick'   => 'window.location.href=\''.$this->getUrl('*/*/').'\'',
						'class' => 'back'
					))
			);

			$this->setChild('resetButton',
				$this->getLayout()->createBlock('adminhtml/widget_button')
					->setData(array(
						'label'     => Mage::helper('adminhtml')->__('Reset'),
						'onclick'   => 'window.location.reload()'
					))
			);

			
			
			 if($currentRoleId != $adminRoleId){
				$this->setChild('saveButton',
					$this->getLayout()->createBlock('adminhtml/widget_button')
						->setData(array(
							'label'     => Mage::helper('adminhtml')->__('Save Role'),
							'onclick'   => 'roleForm.submit();return false;',
							'class' => 'save'
						))
				);
			}
			
			
			if($currentRoleId != NULL){
				 if($currentRoleId != $adminRoleId){
					$this->setChild('deleteButton',
						$this->getLayout()->createBlock('adminhtml/widget_button')
							->setData(array(
								'label'     => Mage::helper('adminhtml')->__('Delete Role'),
								'onclick'   => 'deleteConfirm(\'' . Mage::helper('adminhtml')->__('Are you sure you want to do this?') . '\', \'' . $this->getUrl('*/*/delete', array('rid' => $this->getRequest()->getParam('rid'))) . '\')',
								'class' => 'delete'
							))
					);
				}
			}
			
			return parent::_prepareLayout();
		}

		public function getBackButtonHtml()
		{
			return $this->getChildHtml('backButton');
		}

		public function getResetButtonHtml()
		{
			return $this->getChildHtml('resetButton');
		}

		public function getSaveButtonHtml()
		{
			return $this->getChildHtml('saveButton');
		}

		public function getDeleteButtonHtml()
		{
			if( intval($this->getRequest()->getParam('rid')) == 0 ) {
				return;
			}
			return $this->getChildHtml('deleteButton');
		}

		public function getUser()
		{
			return Mage::registry('user_data');
		}
	}

}