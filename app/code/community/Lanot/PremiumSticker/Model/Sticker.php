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
 * @package     Lanot_PremiumSticker
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sticker item model
 *
 * @author Lanot
 */
class Lanot_PremiumSticker_Model_Sticker
    extends Lanot_EasySticker_Model_Sticker
{
    const TYPE_STATIC = 0;
    const TYPE_DYNAMIC = 1;

    /**
     * @var Lanot_Rule_Model_Rule
     */
    protected $_rule = null;

    /**
     * @var array
     */
    protected $_ruleKeys = array(
        //'from_date',
        //'to_date',
        'website_ids',
        'customer_group_ids',
        'conditions_serialized',
        'conditions'
    );

    protected function _construct()
    {
        $this->_init('lanot_premiumsticker/sticker');
        $this->_rule = $this->_getRuleModel();
    }

    /**
     * @return array
     */
    public function getSelectedProducts()
    {
        return $this->getResource()->getSelectedProducts($this);
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $sticker
     * @return Lanot_PremiumSticker_Model_Sticker
     */
    public function assignSelectedProducts(Lanot_EasySticker_Model_Sticker $sticker)
    {
        $this->getResource()->assignSelectedProducts($sticker);
        return $this;
    }

    /**
     * @return Mage_Rule_Model_Rule
     */
    protected function _getRuleModel()
    {
        return Mage::getModel('lanot_rule/rule');
    }

    /**
     * @return Lanot_Rule_Model_Rule
     */
    public function getRule()
    {
        return $this->_rule;
    }

    /**
     * @return array
     */
    public function getWebsiteIdsArray()
    {
        $ids = $this->getWebsiteIds();
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        return $ids;
    }

    /**
     * @return array
     */
    public function getCustomerGroupIdsArray()
    {
        $ids = $this->getCustomerGroupIds();
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        return $ids;
    }

    /**
     * @return array
     */
    public function getSelectedProductsAuto()
    {
        return $this->getResource()->getSelectedProductsAuto($this);
    }

    /**
     * @return Lanot_EasySticker_Model_Sticker
     */
    protected function _beforeSave()
    {
        $this->prepareRuleSave();
        if (!$this->getData('from_date')) {
            $this->setData('from_date', null);
        }
        if (!$this->getData('to_date')) {
            $this->setData('to_date', null);
        }

        //quick fix for Magento CE >= 1.7
        if (is_array($this->getData('website_ids'))) {
            $ids = $this->getData('website_ids');
            $this->setData('website_ids', implode(',', $ids));
        }
        if (is_array($this->getData('customer_group_ids'))) {
            $ids = $this->getData('customer_group_ids');
            $this->setData('customer_group_ids', implode(',', $ids));
        }
        return parent::_beforeSave();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterLoad()
    {
        $this->prepareRuleLoad();
        parent::_afterLoad();

        $this->prepareStoresImages();
        return $this;
    }

    protected function _afterSave()
    {
        $this->getResource()->updateAssignedProductsDates($this);
        parent::_afterSave();
    }

    protected function _afterSaveCommit()
    {
        $this->getResource()->updateStickerConditionsProductData($this);
        parent::_afterSaveCommit();
    }

    /**
     * @return Lanot_EasySticker_Model_Sticker
     */
    public function prepareRuleSave()
    {
        $this->_rule->setData($this->getData());
        $this->_rule->loadPost($this->getData());
        $this->_rule->prepareToSave();
        $this->_copyDataFromRule();
        return $this;
    }

    /**
     * @return Lanot_PremiumSticker_Model_Sticker
     */
    public function prepareRuleLoad()
    {
        $this->_rule->_resetConditions();
        $this->_rule->setData($this->getData());
        $this->_rule->prepareToLoad();
        $this->_copyDataFromRule();
        return $this;
    }

    /**
     * @return Lanot_PremiumSticker_Model_Sticker
     */
    protected function _copyDataFromRule()
    {
        foreach($this->_ruleKeys as $k) {
            $this->setData($k, $this->_rule->getData($k));
        }
        return $this;
    }

    //------------------------ APPLY ACTIONS --------------------------//
    /**
     * Apply sticker to products
     *
     * @param array $productIds
     * @return Lanot_EasySticker_Model_Sticker
     */
    public function applyToProducts(array $productIds)
    {
        //#1. Prepare filter with current product ids
        $this->getRule()->setProductsFilter($productIds);
        //#2. Update auto stickers in table
        $this->getResource()->updateStickerConditionsProductData($this);
        //#3. Clear Cached information
        $this->_clearCache();
        return $this;
    }

    /**
     * @param $ids
     * @return string
     */
    public function getCacheKeyByProductIds($ids)
    {
        $string = parent::getCacheKeyByProductIds($ids);
        $string .= '_w' . $this->_getHelper()->getWebsiteId();
        $string .= '_st' . $this->_getHelper()->getStoreId();
        $string .= '_cgi' . $this->_getHelper()->getCustomerGroupId();
        $string .= '_d' . now(true);
        return $string;
    }

    /**
     * @return Lanot_PremiumSticker_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_premiumsticker');
    }

    /**
     * @return Lanot_PremiumSticker_Model_Sticker_Backend_Interface|null
     */
    public function getBackendModel()
    {
        if ($this->getData('backend_model')) {
            return Mage::getSingleton($this->getData('backend_model'));
        } else {
            return false;
        }
    }

    /**
     * Match dynamic stickers
     *
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public function isMatched($product)
    {
        if ($this->getBackendModel()) {
            return $this->getBackendModel()->isMatched($product, $this);
        }
        return false;
    }

    /**
     * @return Lanot_PremiumSticker_Model_Sticker
     */
    protected function _prepareImage()
    {
        parent::_prepareImage();

        $_imagesData = array();

        /** @var $store Mage_Core_Model_Store */
        if (!Mage::app()->isSingleStoreMode() && $this->getId()) {
            foreach ($this->_getStores() as $store) {
                $postName = sprintf('sticker_store_image_%d', $store->getId());
                $name = sprintf('store_image_%d', $store->getId());

                $imageData = $this->getData($postName);
                $image = $this->getData($name);

                $isImageDeleted = (is_array($imageData) && !empty($imageData['delete']));

                if ($isImageDeleted) {
                    $this->_deleteImage($image);
                    $this->setData($name, null);
                    $_imagesData[$store->getId()] = null;
                } elseif (!empty($_FILES[$postName]['name'])){
                    if (null !== $image) {
                        $this->_deleteImage($image);
                    }
                    $_imagesData[$store->getId()] = $this->_uploadImage($postName);
                }
            }
        }

        $this->setStoreImages($_imagesData);

        return $this;
    }

    /**
     * @return array
     */
    protected function _getStores()
    {
        return Mage::app()->getStores();
    }

    /**
     * @return Lanot_PremiumSticker_Model_Sticker
     */
    public function prepareStoresImages()
    {
        if (!Mage::app()->isSingleStoreMode() && $this->getId()) {
            $storeImages = $this->getResource()->getStoresImages($this);
            if (!empty($storeImages)) {
                foreach($storeImages as $row) {
                    $key = sprintf('store_image_%d', $row['store_id']);
                    $this->setData($key, $row['image']);
                }
            }
        }
        return $this;
    }

    //fix for CE 1.8.0.0
    public function __sleep()
    {
        $this->_rule = null;
        //remove rule from serialization
        $vars = array_keys(get_object_vars($this));
        $key = array_search('_rule', $vars);
        if ($key !== false) {
            unset($vars[$key]);
        }
        return $vars;
    }
}
