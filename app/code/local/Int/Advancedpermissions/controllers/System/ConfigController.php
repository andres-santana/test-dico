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
 * Configuration controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
require_once "Mage/Adminhtml/controllers/System/ConfigController.php"; 
class Int_Advancedpermissions_System_ConfigController extends Mage_Adminhtml_System_ConfigController
{
    /**
     * Index action
     *
     */
    public function indexAction()
	{
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '1')
		{	
			return parent::indexAction();
		} else {
		
			$userModel			= Mage::getSingleton('admin/session')->getUser();
			$userWebsiteTable 	= Mage::getConfig()->getTablePrefix()."user_website";
			$userId 			= $userModel->getUserId();
			$userType 			= $userModel->getUsertype();
			if($userType != NULL )
			{
				$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
				$select = $connection->select()->from($userWebsiteTable, array('*')) ->where('userid=?', $userId);
				$rowsArray = $connection->fetchAll($select); // return all rows
				
				for($i=0;$i<count($rowsArray);$i++)
				{
					for ($col = 0; $col < count($rowsArray[$i]); $col++)
					{
				
						if($col == 1)
						{
							$wid=$rowsArray[$i]['websiteid'];
							
							$select 	= $connection->select()->from($userWebsiteTable, array('*')) ->where('website_id=?', $wid);
							$rowsArray 	= $connection->fetchRow($select);
							$web_code	= $rowsArray['code'];
							$rowsArray[$i]['websiteid'];
						
						}
				
					}
				
				}
				
				$this->getRequest()->setParam('section','general');
				$this->getRequest()->setParam('website',$web_code);
			}
			$this->_forward('edit');
		}	
    }
}
