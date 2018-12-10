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
 * @package     Mage_Reports
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Report Products Review collection
 *
 * @category    Mage
 * @package     Mage_Reports
 * @author      Magento Core Team <core@magentocommerce.com>
 */

//class Mage_Reports_Model_Resource_Review_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
//class Int_Advancedpermissions_Model_Reports_Resource_Review_Product_Collection extends Mage_Reports_Model_Resource_Review_Product_Collection

class Int_Advancedpermissions_Model_Reports_Resource_Review_Product_Collection extends Mage_Reports_Model_Resource_Review_Product_Collection
{

    public function joinReview()
    {
    	if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::joinReview();
		}
		
    	$helper    = Mage::getResourceHelper('core');

        $subSelect = clone $this->getSelect();
       	
        $userType	= '';
        $userModel	= Mage::getSingleton('admin/session')->getUser();
        if($userModel != NULL){
        	$userType	= $userModel->getUsertype();
        }
        
        if($userType != NULL){
        	
        	$userObj = new Int_Advancedpermissions_Block_Catalog_Product_Grid();
        	$subSelect->reset()
	            ->from(array('rev' => $this->getTable('review/review')), 'COUNT(rev.review_id)')
	            ->join(array('revd' => $this->getTable('review/review_detail')), 'rev.review_id=revd.review_id', array())
	            ->where('e.entity_id = rev.entity_pk_value')
	            ->where('revd.store_id IN (?)', $userObj->getCurrentUserStoreView());
        
        } else {
        	
        	$subSelect->reset()
	            ->from(array('rev' => $this->getTable('review/review')), 'COUNT(DISTINCT rev.review_id)')
	            ->where('e.entity_id = rev.entity_pk_value');
        
        }
         
        $this->addAttributeToSelect('name');

        $this->getSelect()
            ->join(
                array('r' => $this->getTable('review/review')),
                'e.entity_id = r.entity_pk_value',
                array(
                    'review_cnt'    => new Zend_Db_Expr(sprintf('(%s)', $subSelect)),
                    'last_created'  => 'MAX(r.created_at)',))
            ->group('e.entity_id');

        $joinCondition      = array(
            'e.entity_id = table_rating.entity_pk_value',
            $this->getConnection()->quoteInto('table_rating.store_id > ?', 0)
        );

        /**
         * @var $groupByCondition array of group by fields
         */
        $groupByCondition   = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
        $percentField       = $this->getConnection()->quoteIdentifier('table_rating.percent');
        $sumPercentField    = $helper->prepareColumn("SUM({$percentField})", $groupByCondition);
        $sumPercentApproved = $helper->prepareColumn('SUM(table_rating.percent_approved)', $groupByCondition);
        $countRatingId      = $helper->prepareColumn('COUNT(table_rating.rating_id)', $groupByCondition);

        $this->getSelect()
            ->joinLeft(
                array('table_rating' => $this->getTable('rating/rating_vote_aggregated')),
                implode(' AND ', $joinCondition),
                array(
                    'avg_rating'          => sprintf('%s/%s', $sumPercentField, $countRatingId),
                    'avg_rating_approved' => sprintf('%s/%s', $sumPercentApproved, $countRatingId),
            ));

        return $this;
    }

}