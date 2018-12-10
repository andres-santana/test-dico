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
 * Tax rule controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Int_Advancedpermissions_VendorController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
      
		$this->loadLayout();
		$this->renderLayout();
		
    }
	
	public function formAction()
    {
      
	   //Get current layout state
        
		$this->loadLayout();
		
		$root = $this->getLayout()->getBlock('root');
		$settemplate = "page/1column.phtml";
		$root->setTemplate($settemplate);
		
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template',
            'advancedpermissions.vendor_form',
            array(
                'template' => 'advancedpermissions/vendor_form.phtml'
            )
        );

        $this->getLayout()->getBlock('content')->append($block);
        $this->_initLayoutMessages('core/session');
		
        $this->renderLayout();
		
    }
	
	public function validateAction()
    {
      	$params 		= $this->getRequest()->getParams(); 		
		$adminUserTable = Mage::getConfig()->getTablePrefix()."admin_user";
		$dbConnection	= Mage::getSingleton('core/resource')->getConnection('core_write');
		
		/*
		** Email Validation from 'admin_user' table
		*/
		if($params['email']){
			$emailResult	= $dbConnection->query("SELECT `email` FROM ".$adminUserTable." WHERE `email`='".$params['email']."'");
			$emailRow		= $emailResult->fetch(PDO::FETCH_ASSOC);	
			if($emailRow['email']){
				$emailstatus = "emailmatch";
			} else {
				$emailstatus = "emailnotmatch";
			}
				echo $emailstatus;	
				die();
		}
		
		/*
		** Username Validation from 'admin_user' table
		*/	
		if($params['username']){	
			$usernameResult	= $dbConnection->query("SELECT `username` FROM ".$adminUserTable." WHERE `username`='".$params['username']."'");
			$usernameRow	= $usernameResult->fetch(PDO::FETCH_ASSOC);	
			if($usernameRow['username']){
				$usernamestatus = "usernamematch";
			} else {
				$usernamestatus = "usernamenotmatch";
			}
				echo $usernamestatus;	
				die();
		}		
    }
	
	 public function saveAction()
    {
		$params = $this->getRequest()->getParams();  
		$adminUserTable = Mage::getConfig()->getTablePrefix()."admin_user";
		$dbConnection	= Mage::getSingleton('core/resource')->getConnection('core_write');
		
		/*
		** Fields Validation
		*/
		if(
			empty($params['firstname']) || empty($params['lastname']) || empty($params['email']) 
		|| empty($params['username'])	|| empty($params['password']) || empty($params['confirmation'])		
		){
			
			$errorMsg = '';
			if(empty($params['firstname'])){
				$errorMsg .= "Firstname, ";
			}
			if(empty($params['lastname'])){
				$errorMsg .= "Lastname, ";
			}
			if(empty($params['email'])){
				$errorMsg .= "Email, ";
			}
			if(empty($params['username'])){
				$errorMsg .= "Username, ";
			}
			if(empty($params['password'])){
				$errorMsg .= "Password, ";
			}
			if(empty($params['confirmation'])){
				$errorMsg .= "Confirmation password, ";
			}
			
			$errorMsg .= " are required entry.";
			Mage::getSingleton('core/session')->addError($errorMsg);
			return $this->_redirect('advancedpermissions/vendor/form/');			
		}
				
		/*
		** Email Validation
		*/
		if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
			unset($errorMsg);
			$errorMsg = "This (".$params['email'].") email address is not valid.";
			Mage::getSingleton('core/session')->addError($errorMsg);
			return $this->_redirect('advancedpermissions/vendor/form/');
		}
		
		/*
		** Email Validation from 'admin_user' table
		*/
		$emailResult	= $dbConnection->query("SELECT `email` FROM ".$adminUserTable." WHERE `email`='".$params['email']."'");
		$emailRow		= $emailResult->fetch(PDO::FETCH_ASSOC);	
		if($emailRow['email']){
			unset($errorMsg);
			$errorMsg = "'".$emailRow['email']."' email address already exists, please choose another email.";
			Mage::getSingleton('core/session')->addError($errorMsg);
			return $this->_redirect('advancedpermissions/vendor/form/');
		}
		
		/*
		** Username Validation from 'admin_user' table
		*/		
		$usernameResult	= $dbConnection->query("SELECT `username` FROM ".$adminUserTable." WHERE `username`='".$params['username']."'");
		$usernameRow	= $usernameResult->fetch(PDO::FETCH_ASSOC);	
		if($usernameRow['username']){
			unset($errorMsg);
			$errorMsg = "'".$usernameRow['username']."' username already exists, please choose another username.";
			Mage::getSingleton('core/session')->addError($errorMsg);
			return $this->_redirect('advancedpermissions/vendor/form/');
		}
		
		/*
		** Password Validation
		*/
		if($params['password'] != $params['confirmation']){
			unset($errorMsg);
			$errorMsg = "Confirmation password not match";
			Mage::getSingleton('core/session')->addError($errorMsg);
			return $this->_redirect('advancedpermissions/vendor/form/');	
		}
				
		/*
		** Save vendor details on 'admin_user' table
		*/
		$dbConnection->query("INSERT INTO ".$adminUserTable."(
															`firstname`, 
															`lastname`, 
															`email`, 
															`username`, 
															`password`, 
															`created`, 
															`is_active`, 
															`usertype`)
														VALUES(
														'".$params['firstname']."',
														'".$params['lastname']."',
														'".$params['email']."',
														'".$params['username']."',
														'".MD5($params['password'])."',
														'".date('Y-m-d H:i:s')."',
														0,
														'vender_user'
														)"
													);		
		
		/*
		** Get last admin user id from 'admin_user' table
		*/		
		$lastUserIdResult	= $dbConnection->query("SELECT MAX(`user_id`) FROM ".$adminUserTable);
		$lastUserIdRow		= $lastUserIdResult->fetch(PDO::FETCH_ASSOC);	
				
		/*
		** Get store/vendor role type id from 'admin_role' table.
		*/
		$adminRoleTable = Mage::getConfig()->getTablePrefix()."admin_role";
		$roleIdResult	= $dbConnection->query("SELECT `role_id` FROM ".$adminRoleTable." WHERE `user_role_type`='store_role'");
		$roleIdRow		= $roleIdResult->fetch(PDO::FETCH_ASSOC);
		
		/*
		** Save vendor details on 'admin_role' table
		*/
		$dbConnection->query("INSERT INTO ".$adminRoleTable."(
															`parent_id`, 
															`tree_level`, 
															`sort_order`, 
															`role_type`, 
															`user_id`, 
															`role_name` 
															)
														VALUES(
														'".$roleIdRow['role_id']."',
														2,
														0,
														'U',
														'".$lastUserIdRow["MAX(`user_id`)"]."',
														'".$params['firstname']."'
														)"
													);
														
		/*
		** Save vendor details on 'user_website' table
		*/
		$userWebsiteTable = Mage::getConfig()->getTablePrefix()."user_website";
		$dbConnection->query("INSERT INTO ".$userWebsiteTable."(
															`userid`, 
															`websiteid`, 
															`store_ids` 
															)
														VALUES(
														'".$lastUserIdRow["MAX(`user_id`)"]."',														
														'".$params['currentwebsite']."',
														'".$params['currentstore']."'
														)"
													);
													
		/*
		** Redirect on the same page with success message.
		*/		
		$sucessMsg = "Vendor account has been created. Untill super admin will not approve, you can not access this account.";
		Mage::getSingleton('core/session')->addSuccess($sucessMsg);		
		$this->_redirect('advancedpermissions/vendor/form/');		
    }
}
