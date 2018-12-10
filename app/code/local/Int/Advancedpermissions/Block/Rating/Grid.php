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
 * Adminhtml reviews grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
{
	class Int_Advancedpermissions_Block_Rating_Grid extends Mage_Adminhtml_Block_Rating_Grid
	{
	
	}
} else {	

	class Int_Advancedpermissions_Block_Rating_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{

		public function __construct()
		{
			parent::__construct();
			$this->setId('ratingsGrid');
			$this->setDefaultSort('rating_code');
			$this->setDefaultDir('ASC');
			$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
			$collection = Mage::getModel('rating/rating')
				->getResourceCollection()
				->addEntityFilter(Mage::registry('entityId'));
			
			$userModel 	= Mage::getSingleton('admin/session')->getUser();
			$userType 	= $userModel->getUsertype();
			
			if($userType != NULL){
				$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
				$ratingModel	= Mage::getModel('rating/rating');	
				$newCollection	= $ratingModel->getCollection();
				$userRatingIds	= array();
				foreach($newCollection as $newData){
					$ratingStores = array_intersect($ratingModel->load($newData->getId())->getStores(), $userObj->getCurrentUserStoreView());
					if(count($ratingStores) > 0){ $userRatingIds[] = $newData->getId(); }
				}
				$collection->addFieldToFilter('rating_id', $userRatingIds);
			}
				
			$this->setCollection($collection);
			return parent::_prepareCollection();
		}

		/**
		 * Prepare Rating Grid colunms
		 *
		 * @return Mage_Adminhtml_Block_Rating_Grid
		 */
		protected function _prepareColumns()
		{
			$this->addColumn('rating_id', array(
				'header'    => Mage::helper('rating')->__('ID'),
				'align'     =>'right',
				'width'     => '50px',
				'index'     => 'rating_id',
			));

			$this->addColumn('rating_code', array(
				'header'    => Mage::helper('rating')->__('Rating Name'),
				'index'     => 'rating_code',
			));

			$this->addColumn('position', array(
				'header' => Mage::helper('rating')->__('Sort Order'),
				'align' => 'left',
				'width' => '100px',
				'index' => 'position',
			));

			return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			return $this->getUrl('*/*/edit', array('id' => $row->getId()));
		}

	}

}