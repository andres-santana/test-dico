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
 * Adminhtml customer grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
{

	class Int_Advancedpermissions_Block_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
	{
	
	}
	
} else {

	class Int_Advancedpermissions_Block_Catalog_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{

		public function __construct()
		{
			parent::__construct();
			$this->setId('productGrid');
			$this->setDefaultSort('entity_id');
			$this->setDefaultDir('DESC');
			$this->setSaveParametersInSession(true);
			$this->setUseAjax(true);
			$this->setVarNameFilter('product_filter');

		}

		
		protected function _getStore()
		{
			
			$userModel	= Mage::getSingleton('admin/session')->getUser();
			$userId 	= $userModel->getUserId();	
			$userType	= $userModel->getUsertype();
			if($userType != NULL){	
			
				$dbConnection 		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userCategoryTable	= Mage::getConfig()->getTablePrefix()."user_category";
				$userQuery			= $dbConnection->query("
											SELECT `storeview_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				foreach($userRows as $userRow){
					$userStoreIds = explode(",", $userRow['storeview_id']);
					foreach($userStoreIds as $userStoreId){ $arrUserStoreIds[] = $userStoreId; }
				}
				$_arrUserStoreIds = array_unique($arrUserStoreIds);
				
				if(count($_arrUserStoreIds) == 1){
					$storeId = $_arrUserStoreIds[0]; 
				} else {
					$storeId = (int) $this->getRequest()->getParam('store', 0);
				}
				
			} else {
				$storeId = (int) $this->getRequest()->getParam('store', 0);
			}
			return Mage::app()->getStore($storeId);
		}
		
		
		protected function _prepareCollection()
		{
			
			$store = $this->_getStore();
			$collection = Mage::getModel('catalog/product')->getCollection()
				->addAttributeToSelect('sku')
				->addAttributeToSelect('name')
				->addAttributeToSelect('attribute_set_id')
				->addAttributeToSelect('type_id');
			$userModel	= Mage::getSingleton('admin/session')->getUser();
			$userId 	= $userModel->getUserId();	
			$userType	= $userModel->getUsertype();
			
			if($userType != NULL){
				$dbConnection 		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$tableName			= Mage::getConfig()->getTablePrefix()."catalog_product_entity";
				$userCategoryTable	= Mage::getConfig()->getTablePrefix()."user_category";
				$userQuery			= $dbConnection->query("
											SELECT `storeview_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				$arrUserStoreIds 	= array();
				$arrProductIds		= array();
				foreach($userRows as $userRow){
					$userStoreIds = explode(",", $userRow['storeview_id']);
					foreach($userStoreIds as $userStoreId){ $arrUserStoreIds[] = $userStoreId; }
				}
				$_arrUserStoreIds = array_unique($arrUserStoreIds);
				sort($_arrUserStoreIds);
				
				$websiteTable		= Mage::getConfig()->getTablePrefix()."user_website";
				$resultWebsite		= $dbConnection->query("SELECT store_ids FROM ".$websiteTable." WHERE userid='".$userId."'");
				$rowsWebsite		= $resultWebsite->fetchAll(PDO::FETCH_ASSOC);
				
				if($userType == 'store_user'){ 
					foreach($_arrUserStoreIds as $arrUserStoreId){
						$newCollection = Mage::getModel('catalog/product')->getCollection();
						$newStore = Mage::getModel('core/store')->load($arrUserStoreId);
						$newCollection->setStoreId($newStore->getId());
						$newAdminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
						$newCollection->addStoreFilter($newStore);
						$newCollection->joinAttribute(
							'name',
							'catalog_product/name',
							'entity_id',
							null,
							'inner',
							$newAdminStore
						);
						if(count($newCollection) > 0){					
							foreach($newCollection as $_newCollection){
								$arrProductIds[] = $_newCollection->getId();
								
							}
							
						}	
					}
					
					$_arrProductIds = array_unique($arrProductIds);
					sort($_arrProductIds);

					$collection->addAttributeToFilter('entity_id', array('in' => $_arrProductIds));

				} elseif($userType == 'vender_user') {
					$result			= $dbConnection->query("SELECT entity_id FROM ".$tableName." WHERE vender_id='".$userId."'");
					$rows			= $result->fetchAll(PDO::FETCH_ASSOC);	
					$userProductIds = array();				
					foreach($rows as $row){	$userProductIds[] = $row['entity_id'];	}
					sort($userProductIds);
					$collection->addAttributeToFilter('entity_id', array('in' => $userProductIds));	
				}
			}
						
			if(Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')){
				$collection->joinField('qty',
					'cataloginventory/stock_item',
					'qty',
					'product_id=entity_id',
					'{{table}}.stock_id=1',
					'left');
			}
			
			if($store->getId()){
				
				$collection->setStoreId($store->getId());
				$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
				$collection->addStoreFilter($store);
				$collection->joinAttribute(
					'name',
					'catalog_product/name',
					'entity_id',
					null,
					'inner',
					$adminStore
				);
				$collection->joinAttribute(
					'custom_name',
					'catalog_product/name',
					'entity_id',
					null,
					'inner',
					$store->getId()
				);
				$collection->joinAttribute(
					'status',
					'catalog_product/status',
					'entity_id',
					null,
					'inner',
					$store->getId()
				);
				$collection->joinAttribute(
					'visibility',
					'catalog_product/visibility',
					'entity_id',
					null,
					'inner',
					$store->getId()
				);
				$collection->joinAttribute(
					'price',
					'catalog_product/price',
					'entity_id',
					null,
					'left',
					$store->getId()
				);
			}
			else {
				$collection->addAttributeToSelect('price');
				$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
				$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
			}
		   
			$this->setCollection($collection);

			parent::_prepareCollection();
			$this->getCollection()->addWebsiteNamesToResult();
			return $this;
		}

		protected function _addColumnFilterToCollection($column)
		{
			if ($this->getCollection()) {
				if ($column->getId() == 'websites') {
					$this->getCollection()->joinField('websites',
						'catalog/product_website',
						'website_id',
						'product_id=entity_id',
						null,
						'left');
				}
			}
			return parent::_addColumnFilterToCollection($column);
		}
		
		public function getCurrentUserWebsite()
		{
			$userModel 			= Mage::getSingleton('admin/session')->getUser();
			$userId 			= $userModel->getUserId();
			$userType 			= $userModel->getUsertype();
			
			$arrUserDataIds 	= '';
			
			if($userType != NULL){
				$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
				$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userQuery			= $dbConnection->query("
											SELECT `website_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				if(count($userRows) > 0){
					foreach($userRows as $userRow){
						$arrUserDataIds[] = $userRow['website_id'];
					}
				}
			} else {
				foreach(Mage::getModel('core/website')->getCollection() as $collection){
					$arrUserDataIds[] = $collection->getId();
				}
			}
			
			return array_unique($arrUserDataIds);
		}
		
		public function getUserWebsite($userId)
		{
			$arrUserDataIds 	= '';
			if($userId){
				$userModel 			= Mage::getModel('admin/user')->load($userId);
				$userType 			= $userModel->getUsertype();			
				if($userType != NULL){
					$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
					$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
					$userQuery			= $dbConnection->query("
												SELECT `website_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
											");
					$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
					if(count($userRows) > 0){
						foreach($userRows as $userRow){
							$arrUserDataIds[] = $userRow['website_id'];
						}
					}
				}
			}
			return array_unique($arrUserDataIds);	
		}
		
		public function getCurrentUserStore()
		{
			$userModel 			= Mage::getSingleton('admin/session')->getUser();
			$userId 			= $userModel->getUserId();
			$userType 			= $userModel->getUsertype();
			
			$arrUserDataIds 	= '';
			
			if($userType != NULL){
				$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
				$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userQuery			= $dbConnection->query("
											SELECT `store_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				if(count($userRows) > 0){
					foreach($userRows as $userRow){
						$arrUserDataIds[] = $userRow['store_id'];
					}
				}
			} else {
				foreach(Mage::getModel('core/store_group')->getCollection() as $collection){
					$arrUserDataIds[] = $collection->getId();
				}
			}
			
			return array_unique($arrUserDataIds);
		}
		
		public function getUserStore($userId, $websiteId)
		{
			$arrUserDataIds 	= '';
			if($userId && $websiteId){
				$userModel 			= Mage::getModel('admin/user')->load($userId);
				$userType 			= $userModel->getUsertype();

				if($userType != NULL){
					$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
					$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
					$userQuery			= $dbConnection->query("
												SELECT `store_id` FROM ".$userCategoryTable." WHERE `website_id`='".$websiteId."' AND `user_id`='".$userId."'
											");
					$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
					if(count($userRows) > 0){
						foreach($userRows as $userRow){
							$arrUserDataIds[] = $userRow['store_id'];
						}
					}
				}
			}
			return array_unique($arrUserDataIds);
		}
		
		public function getCurrentUserStoreView()
		{
			$userModel 			= Mage::getSingleton('admin/session')->getUser();
			$userId 			= $userModel->getUserId();
			$userType 			= $userModel->getUsertype();
			
			$_arrUserDataIds = array();
			
			if($userType != NULL){
				$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
				$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userQuery			= $dbConnection->query("
											SELECT `storeview_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($userRows) > 0){
					foreach($userRows as $userRow){
						$userDataIds = explode(",", $userRow['storeview_id']);
						foreach($userDataIds as $userDataId){
							$_arrUserDataIds[] = $userDataId;
						}
					}
					sort($_arrUserDataIds);
				}
			} else {
				foreach(Mage::getModel('core/store')->getCollection() as $collection){
					$_arrUserDataIds[] = $collection->getId();
				}
				sort($_arrUserDataIds);
			}
			
			return array_unique($_arrUserDataIds);
		}
		
		public function getUserStoreView($userId, $groupId)
		{
			$_arrUserDataIds 	= array();
			if($userId && $groupId){
				$userModel 			= Mage::getModel('admin/user')->load($userId);
				$userType 			= $userModel->getUsertype();

				if($userType != NULL){
					$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
					$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
					$userQuery			= $dbConnection->query("
												SELECT `storeview_id` FROM ".$userCategoryTable." WHERE `store_id`='".$groupId."' AND `user_id`='".$userId."'
											");
					$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
					
					if(count($userRows) > 0){
						foreach($userRows as $userRow){
							$userDataIds = explode(",", $userRow['storeview_id']);
							foreach($userDataIds as $userDataId){
								$_arrUserDataIds[] = $userDataId;
							}
						}
						sort($_arrUserDataIds);
					}
				} 
			}
			return array_unique($_arrUserDataIds);
		}

		public function getCurrentUserCategory()
		{
			$userModel 			= Mage::getSingleton('admin/session')->getUser();
			$userId 			= $userModel->getUserId();
			$userType 			= $userModel->getUsertype();
			
			$_arrUserDataIds 	= array();
			if($userType != NULL){
				$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
				$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
				$userQuery			= $dbConnection->query("
											SELECT `category_id` FROM ".$userCategoryTable." WHERE `user_id`='".$userId."'
										");
				$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($userRows) > 0){
					foreach($userRows as $userRow){
						$userDataIds = explode(",", $userRow['category_id']);
						foreach($userDataIds as $userDataId){
							$_arrUserDataIds[] = $userDataId;
						}
					}
					sort($_arrUserDataIds);
				}
			} else {
				foreach(Mage::getModel('catalog/category')->getCollection() as $collection){
					$_arrUserDataIds[] = $collection->getId();
				}
				sort($_arrUserDataIds);
			}
			
			return array_unique($_arrUserDataIds);
		}
		
		public function getUserCategory($userId, $groupId)
		{
			$_arrUserDataIds 	= array();
			if($userId && $groupId){
				$userModel 			= Mage::getModel('admin/user')->load($userId);
				$userType 			= $userModel->getUsertype();

				if($userType != NULL){
					$userCategoryTable 	= Mage::getConfig()->getTablePrefix()."user_category";
					$dbConnection		= Mage::getSingleton('core/resource')->getConnection('core_write');
					$userQuery			= $dbConnection->query("
												SELECT `category_id` FROM ".$userCategoryTable." WHERE `store_id`='".$groupId."' AND `user_id`='".$userId."'
											");
					$userRows			= $userQuery->fetchAll(PDO::FETCH_ASSOC);
					
					if(count($userRows) > 0){
						foreach($userRows as $userRow){
							$userDataIds = explode(",", $userRow['category_id']);
							foreach($userDataIds as $userDataId){
								$_arrUserDataIds[] = $userDataId;
							}
						}
						sort($_arrUserDataIds);
					}
				} 
			}
			return array_unique($_arrUserDataIds);
		}
		
		protected function _prepareColumns()
		{
			$this->addColumn('entity_id',
				array(
					'header'=> Mage::helper('catalog')->__('ID'),
					'width' => '50px',
					'type'  => 'number',
					'index' => 'entity_id',
			));
			$this->addColumn('name',
				array(
					'header'=> Mage::helper('catalog')->__('Name'),
					'index' => 'name',
			));

			$store = $this->_getStore();
			if ($store->getId()) {
				$this->addColumn('custom_name',
					array(
						'header'=> Mage::helper('catalog')->__('Name in %s', $store->getName()),
						'index' => 'custom_name',
				));
			}

			$this->addColumn('type',
				array(
					'header'=> Mage::helper('catalog')->__('Type'),
					'width' => '60px',
					'index' => 'type_id',
					'type'  => 'options',
					'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
			));

			$sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
				->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
				->load()
				->toOptionHash();

			$this->addColumn('set_name',
				array(
					'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
					'width' => '100px',
					'index' => 'attribute_set_id',
					'type'  => 'options',
					'options' => $sets,
			));

			$this->addColumn('sku',
				array(
					'header'=> Mage::helper('catalog')->__('SKU'),
					'width' => '80px',
					'index' => 'sku',
			));

			$store = $this->_getStore();
			$this->addColumn('price',
				array(
					'header'=> Mage::helper('catalog')->__('Price'),
					'type'  => 'price',
					'currency_code' => $store->getBaseCurrency()->getCode(),
					'index' => 'price',
			));

			if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
				$this->addColumn('qty',
					array(
						'header'=> Mage::helper('catalog')->__('Qty'),
						'width' => '100px',
						'type'  => 'number',
						'index' => 'qty',
				));
			}

			$this->addColumn('visibility',
				array(
					'header'=> Mage::helper('catalog')->__('Visibility'),
					'width' => '70px',
					'index' => 'visibility',
					'type'  => 'options',
					'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
			));

			$this->addColumn('status',
				array(
					'header'=> Mage::helper('catalog')->__('Status'),
					'width' => '70px',
					'index' => 'status',
					'type'  => 'options',
					'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
			));

			if (!Mage::app()->isSingleStoreMode()) {
				
				$this->addColumn('websites',
					array(
						'header'=> Mage::helper('catalog')->__('Websites'),
						'width' => '100px',
						'sortable'  => false,
						'index'     => 'websites',
						'type'      => 'options',
						'options'   => Mage::getModel('core/website')->getCollection()
										->addFieldToFilter('website_id', $this->getCurrentUserWebsite())
										->toOptionHash(),
				));
			}

			$this->addColumn('action',
				array(
					'header'    => Mage::helper('catalog')->__('Action'),
					'width'     => '50px',
					'type'      => 'action',
					'getter'     => 'getId',
					'actions'   => array(
						array(
							'caption' => Mage::helper('catalog')->__('Edit'),
							'url'     => array(
								'base'=>'*/*/edit',
								'params'=>array('store'=>$this->getRequest()->getParam('store'))
							),
							'field'   => 'id'
						)
					),
					'filter'    => false,
					'sortable'  => false,
					'index'     => 'stores',
			));

			if (Mage::helper('catalog')->isModuleEnabled('Mage_Rss')) {
				$this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
			}

			return parent::_prepareColumns();
		}

		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('entity_id');
			$this->getMassactionBlock()->setFormFieldName('product');

			$this->getMassactionBlock()->addItem('delete', array(
				 'label'=> Mage::helper('catalog')->__('Delete'),
				 'url'  => $this->getUrl('*/*/massDelete'),
				 'confirm' => Mage::helper('catalog')->__('Are you sure?')
			));

			$statuses = Mage::getSingleton('catalog/product_status')->getOptionArray();

			array_unshift($statuses, array('label'=>'', 'value'=>''));
			$this->getMassactionBlock()->addItem('status', array(
				 'label'=> Mage::helper('catalog')->__('Change status'),
				 'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
				 'additional' => array(
						'visibility' => array(
							 'name' => 'status',
							 'type' => 'select',
							 'class' => 'required-entry',
							 'label' => Mage::helper('catalog')->__('Status'),
							 'values' => $statuses
						 )
				 )
			));

			if (Mage::getSingleton('admin/session')->isAllowed('catalog/update_attributes')){
				$this->getMassactionBlock()->addItem('attributes', array(
					'label' => Mage::helper('catalog')->__('Update Attributes'),
					'url'   => $this->getUrl('*/catalog_product_action_attribute/edit', array('_current'=>true))
				));
			}

			Mage::dispatchEvent('adminhtml_catalog_product_grid_prepare_massaction', array('block' => $this));
			return $this;
		}

		public function getGridUrl()
		{
			return $this->getUrl('*/*/grid', array('_current'=>true));
		}

		public function getRowUrl($row)
		{
			return $this->getUrl('*/*/edit', array(
				'store'=>$this->getRequest()->getParam('store'),
				'id'=>$row->getId())
			);
		}
	}
}