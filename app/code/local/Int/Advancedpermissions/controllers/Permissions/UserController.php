<?php
include_once("Mage/Adminhtml/controllers/Permissions/UserController.php");
class Int_Advancedpermissions_Permissions_UserController extends Mage_Adminhtml_Permissions_UserController
{

	public function saveAction()
    {
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::saveAction();
		} else {	
			
			if ($data = $this->getRequest()->getPost()) {
					
				if($data['usertype']){
					if($data['websites_id'] == NULL){
						Mage::getSingleton('adminhtml/session')->addError($this->__('User website not selected.'));
						$this->_redirect('*/*/');
						return;	
					}
				}
				
				if($data['usertype']){
					if($data['store_id'] == NULL){
						Mage::getSingleton('adminhtml/session')->addError($this->__('User store not selected.'));
						$this->_redirect('*/*/');
						return;	
					}
				}

				if($data['usertype']){
					if($data['storeview_id'] == NULL){
						Mage::getSingleton('adminhtml/session')->addError($this->__('User store view not selected.'));
						$this->_redirect('*/*/');
						return;	
					}	
				}
				
				$id = $this->getRequest()->getParam('user_id');
				$model = Mage::getModel('admin/user')->load($id);
				if (!$model->getId() && $id) {
					Mage::getSingleton('adminhtml/session')->addError($this->__('This user no longer exists.'));
					$this->_redirect('*/*/');
					return;
				}
				$model->setData($data);
				
				/*
				 * Unsetting new password and password confirmation if they are blank
				 */
				if ($model->hasNewPassword() && $model->getNewPassword() === '') {
					$model->unsNewPassword();
				}
				if ($model->hasPasswordConfirmation() && $model->getPasswordConfirmation() === '') {
					$model->unsPasswordConfirmation();
				}

				$result = $model->validate();
				if (is_array($result)) {
					Mage::getSingleton('adminhtml/session')->setUserData($data);
					foreach ($result as $message) {
						Mage::getSingleton('adminhtml/session')->addError($message);
					}
					$this->_redirect('*/*/edit', array('_current' => true));
					return $this;
				}

				try {
					$model->save();
					 $uRoles = $this->getRequest()->getParam('roles', false);
					if ($uRoles) {
						/*parse_str($uRoles, $uRoles);
						$uRoles = array_keys($uRoles);*/
						if ( 1 == sizeof($uRoles) ) {
							$model->setRoleIds($uRoles)
								->setRoleUserId($model->getUserId())
								->saveRelations();
						} else if ( sizeof($uRoles) > 1 ) {
							//@FIXME: stupid fix of previous multi-roles logic.
							//@TODO:  make proper DB upgrade in the future revisions.
							$rs = array();
							$rs[0] = $uRoles[0];
							$model->setRoleIds( $rs )->setRoleUserId( $model->getUserId() )->saveRelations();
						}
					}
					
					$write 			= Mage::getSingleton('core/resource')->getConnection('core_write');
					$webids			=$this->getRequest()->getParam('websites_id');
					$storeids		=$this->getRequest()->getParam('store_id');
					
					$catid 			= $this->getRequest()->getParam('catid');
					$id 			= $model->getUserId();
					$userFirstName 	= $model->getFirstname();
					
					/****** METHOD TO SELECT DATA FROM user_category TABLE *****/
					$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
					$userCategorySelect = $write->select()->from($userCategoryTable) ->where("user_id = '".$id."'");
					$userCategoryResult = $write->fetchAll($userCategorySelect);

					if($userCategoryResult != NULL){
					/****** METHOD TO DELETE DATA FROM user_website TABLE *****/
						$write->query("DELETE FROM ".$userCategoryTable." WHERE user_id='".$id."'");
					}
					
					/****** METHOD TO INSERT DATA FROM user_category TABLE *****/
					$_categoryIds	= $this->getRequest()->getParam('category_id');
					$_storeviewIds	= $this->getRequest()->getParam('storeview_id');
					
					foreach($storeids as $key=>$_websites){
					$_websiteId = $key;
						foreach($_websites as $key=>$_stores){
							$storeViewIds = implode(",", $_storeviewIds[$_stores]);
							$categoryIds = implode(",", $_categoryIds[$_websiteId][$_stores]);
							/****** METHOD TO INSERT DATA FROM user_category TABLE *****/
							$write->query("INSERT INTO ".$userCategoryTable."(`user_id`, `website_id` , `store_id`, `storeview_id`, `category_id`, `type_id`) 
								VALUES('".$id."', '".$_websiteId."' , '".$_stores."', '".$storeViewIds."', '".$categoryIds."', '".$data['websiteoption']."')");					
						
						}
					}
										
					/****** METHOD TO SELECT DATA FROM user_website TABLE *****/
					$userWebsiteTable 	= Mage::getConfig()->getTablePrefix()."user_website";
					$select 			= $write->select()->from($userWebsiteTable) ->where("userid = '".$id."'");
					$sResult 			= $write->fetchAll($select);

					if($sResult != NULL){
					/****** METHOD TO DELETE DATA FROM user_website TABLE *****/
						$write->query("DELETE FROM ".$userWebsiteTable." WHERE userid='".$id."'");
					}

					foreach($webids as $webid)
					{
						$storeid = implode(',' , $storeids[$webid]);
						
					/****** METHOD TO INSERT DATA FROM user_website TABLE *****/
						if($storeid){
							$write->query("INSERT INTO ".$userWebsiteTable."(`userid`, `websiteid` , `store_ids`) VALUES('".$id."', '".$webid."' , '".$storeid."')");				
						}
					}
					
					/****** METHOD TO CREATE ROLE FOR STORE USER AND VENDER *****/
					$adminRoleTable	= Mage::getConfig()->getTablePrefix()."admin_role";
					$adminRuleTable	= Mage::getConfig()->getTablePrefix()."admin_rule";
					$userType		= $this->getRequest()->getParam('usertype');
					$userType		= ucwords(str_replace("_", " ", $userType));				
					$roleName		= $userType." Role ".$id;
					
					
					if($uRoles == NULL){					
						$adminRoleResult 	= $write->query("SELECT role_id FROM ".$adminRoleTable." WHERE `user_role_type`='store_role'");
						$adminRoleRow		= $adminRoleResult->fetch(PDO::FETCH_ASSOC);
						$adminRoleId		= $adminRoleRow['role_id'];
						$write->query("INSERT INTO ".$adminRoleTable."(`parent_id`, `tree_level` , `sort_order`, `role_type`, `user_id`, `role_name`, `user_role_type`) 
									VALUES('".$adminRoleId."', '2', '0', 'U', '".$id."', '".$userFirstName."', '')");
					}
					
					Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The user has been saved.'));
					Mage::getSingleton('adminhtml/session')->setUserData(false);
					$this->_redirect('*/*/');
					return;
				} catch (Mage_Core_Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setUserData($data);
					
					$this->_redirect('*/*/edit', array('user_id' => $model->getUserId()));
					return;
				}
			}
			$this->_redirect('*/*/');
		}	
    }
}