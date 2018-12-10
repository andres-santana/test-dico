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
 * @category    Lanot
 * @package     Lanot_Rule
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lanot_Rule_Model_Rule extends Mage_CatalogRule_Model_Rule
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'lanot_rule';

    /**
     * @var string
     */
    protected $_modelConditions = 'lanot_rule/rule_condition_combine';

    /**
     * Limitation for products collection
     *
     * @var int|array|null
     */
    protected $_productsFilter = null;

    /**
     * @return Lanot_Rule_Model_Rule_Condition_Combine
     */
    public function getConditionsInstance()
    {
        return Mage::getModel($this->_modelConditions);
    }

    /**
     * Prepare data before saving
     *
     * @return Mage_Rule_Model_Rule
     */
    public function prepareToSave()
    {
        if ($this->getConditions()) {
            $this->setConditionsSerialized(serialize($this->getConditions()->asArray()));
            $this->unsConditions();
        }

        $this->_prepareWebsiteIds();

        if (is_array($this->getCustomerGroupIds())) {
            $this->setCustomerGroupIds(join(',', $this->getCustomerGroupIds()));
        }

        Mage_Core_Model_Abstract::_beforeSave();
    }

    /**
     *
     */
    public function prepareToLoad()
    {
        Mage_Core_Model_Abstract::_afterLoad();
        $conditions = $this->getConditions()->getConditions(); //@fix
        $conditionsArr = unserialize($this->getConditionsSerialized());
        if (!empty($conditionsArr) && is_array($conditionsArr)) {
            $this->getConditions()->loadArray($conditionsArr);
        }

        $websiteIds = $this->_getData('website_ids');
        if (is_string($websiteIds)) {
            $this->setWebsiteIds(explode(',', $websiteIds));
        }

        $groupIds = $this->getCustomerGroupIds();
        if (is_string($groupIds)) {
            $this->setCustomerGroupIds(explode(',', $groupIds));
        }
    }

    /**
     * @fix: old versions
     * Filtering products that must be checked for matching with rule
     *
     * @param  int|array $productIds
     */
    public function setProductsFilter($productIds)
    {
        $this->_productsFilter = $productIds;
    }

    /**
     * @fix: old versions
     * Returns products filter
     *
     * @return array|int|null
     */
    public function getProductsFilter()
    {
        return $this->_productsFilter;
    }

    /**
     * @fix: old versions
     * Get array of product ids which are matched by rule
     *
     * @return array
     */
    public function getMatchingProductIds()
    {
        if (is_null($this->_productIds)) {
            $this->_productIds = array();
            $this->setCollectedAttributes(array());
            $websiteIds = $this->getWebsiteIds();
            if (!is_array($websiteIds)) {
                $websiteIds = explode(',', $websiteIds);
            }

            if ($websiteIds) {
                $productCollection = $this->_getProductCollection()->addWebsiteFilter($websiteIds);
                if ($this->_productsFilter) {
                    $productCollection->addIdFilter($this->_productsFilter);
                }

                Mage::dispatchEvent('lanot_rule_prepare_product_collection', array(
                    'collection' => $productCollection,
                    'rule' => $this,
                ));

                $this->_walkCollection($productCollection);
            }
        }

        return $this->_productIds;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getProductCollection()
    {
        return Mage::getResourceModel('catalog/product_collection');
    }

    /**
     * @param $collection
     * @return Lanot_Rule_Model_Rule
     */
    protected function _walkCollection($collection)
    {
        $this->getConditions()->collectValidatedAttributes($collection);
        Mage::getSingleton('core/resource_iterator')->walk($collection->getSelect(),
            array(array($this, 'callbackValidateProduct')),
            array(
                'attributes' => $this->getCollectedAttributes(),
                'product'    => Mage::getModel('catalog/product'),
            )
        );
        return $this;
    }

    //@fix: override parent method to change accessr type
    public function _resetConditions($conditions = null)
    {
        return parent::_resetConditions($conditions);
    }

    /**
     * @fix: CE 1.8.0.0
     * Callback function for product matching
     *
     * @param $args
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);

        if ($this->getConditions()->validate($product)) {
            $this->_productIds[] = $product->getId();
        }
    }
}
