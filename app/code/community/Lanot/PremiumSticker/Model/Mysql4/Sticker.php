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
 * Sticker item resource model
 *
 * @author Lanot
 */
class Lanot_PremiumSticker_Model_Mysql4_Sticker
    extends Lanot_EasySticker_Model_Mysql4_Sticker
{
    const SECONDS_IN_DAY = 86400;

    protected $_fields = array(
        'sticker_id',
        'product_id',
        'website_id',
        'customer_group_id',
        'from_time',
        'to_time',
        'auto'
    );

    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('lanot_premiumsticker/sticker', 'sticker_id');
    }

    /**
     * @return string
     */
    protected function _getLinkTable()
    {
        return $this->getTable('lanot_premiumsticker/sticker_product');
    }

    /**
     * @return string
     */
    protected function _getStoreImageTable()
    {
        return $this->getTable('lanot_premiumsticker/sticker_image');
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return array
     */
    public function getSelectedProducts(Lanot_EasySticker_Model_Sticker $object)
    {
        $select = $this->_getSelectedProductsSelect($object);
        $select->where("auto = 0");
        return $this->getReadConnection()->fetchCol($select);
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return array
     */
    public function getSelectedProductsAuto(Lanot_EasySticker_Model_Sticker $object)
    {
        $select = $this->_getSelectedProductsSelect($object);
        $select->where("auto = 1");
        return $this->getReadConnection()->fetchCol($select);
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker
     */
    public function assignSelectedProducts(Lanot_EasySticker_Model_Sticker $object)
    {
        if (null === $object->getProducts()) {
            return $this;
        }
        $oldProducts = $this->getSelectedProducts($object);
        $newProducts = $object->getProducts();

        if (!empty($oldProducts)) {
            $this->_deleteProductsToSticker($oldProducts, $object);
        }
        if (!empty($newProducts)) {
            $this->_insertProductsToSticker($newProducts, $object);
        }
        return $this;
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return Varien_Db_Select
     */
    protected function _getSelectedProductsSelect(Lanot_EasySticker_Model_Sticker $object)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->_getLinkTable(), array('product_id'))
            ->where("sticker_id = ?", $object->getId());
        return $select;
    }

    /**
     * @param Mage_Catalog_Model_Product $object
     * @return Varien_Db_Select
     */
    protected function _getSelectedStickersToProductSelect(Mage_Catalog_Model_Product $object)
    {
        $select = parent::_getSelectedStickersToProductSelect($object);
        $select->where("auto = 0");
        return $select;
    }

    /**
     * @param array $productIds
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return Lanot_EasySticker_Model_Mysql4_Sticker
     */
    protected function _insertProductsToSticker(array $productIds, Lanot_EasySticker_Model_Sticker $object)
    {
        $data = $this->_prepareInsertDataByProducts($productIds, $object);
        $this->_getWriteAdapter()->insertArray($this->_getLinkTable(), $this->_fields, $data);
        return $this;
    }

    /**
     * @param array $productIds
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return Lanot_EasySticker_Model_Mysql4_Sticker
     */
    protected function _deleteProductsToSticker(array $productIds, Lanot_EasySticker_Model_Sticker $object)
    {
        $this->_getWriteAdapter()->delete(
            $this->_getLinkTable(),
            $this->_deleteProductsToStickerWhere($productIds, $object)
        );
        return $this;
    }

    /**
     * @param array $stickerIds
     * @param Mage_Catalog_Model_Product $object
     * @return string
     */
    protected function _deleteStickersToProductWhere(array $stickerIds, Mage_Catalog_Model_Product $object)
    {
        $where = parent::_deleteStickersToProductWhere($stickerIds, $object);
        $where.= ' AND auto = 0';
        return $where;
    }

    /**
     * @param array $productIds
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return string
     */
    protected function _deleteProductsToStickerWhere(array $productIds, Lanot_EasySticker_Model_Sticker $object)
    {
        $where = $this->_getWriteAdapter()->quoteInto('sticker_id = ?', $object->getId());
        $where.= $this->_getWriteAdapter()->quoteInto(' AND product_id IN (?)', $productIds);
        $where.= ' AND auto = 0';
        return $where;
    }

    /**
     * @param array $stickerIds
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection
     */
    protected function _getStickerCollection(array $stickerIds = array())
    {
        $collection = Mage::getModel('lanot_premiumsticker/sticker')->getCollection();
        if (!empty($stickerIds)) {
            $collection->addFieldToFilter('sticker_id', array('in' => $stickerIds));
        }
        return $collection;
    }

    /**
     * @param $stickerId
     * @param $productId
     * @param $websiteId
     * @param $groupId
     * @param $fromTime
     * @param $toTime
     * @param $auto
     * @return array
     */
    protected function _getRowData($stickerId, $productId, $websiteId, $groupId, $fromTime, $toTime, $auto = 0)
    {
        return array(
            'sticker_id'        => $stickerId,
            'product_id'        => $productId,
            'website_id'        => $websiteId,
            'customer_group_id' => $groupId,
            'from_time'         => $fromTime,
            'to_time'           => $toTime,
            'auto'              => $auto,
        );
    }

    /**
     * @param $rule
     * @return bool
     */
    protected function _canApply($rule)
    {
        $conds = $rule->getConditions()->getConditions();
        return ($conds && !empty($conds));
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return mixed
     */
    protected function _deleteOldProductData(Lanot_EasySticker_Model_Sticker $object)
    {
        $write = $this->_getWriteAdapter();
        $rule = $object->getRule();
        $sqlCond = $write->quoteInto('auto=1 AND sticker_id=?', $object->getId());
        if ($rule->getProductsFilter()) {
            $sqlCond .= $write->quoteInto(' AND product_id in (?)', implode(',' , $rule->getProductsFilter()));
        }
        $write->delete($this->_getLinkTable(), $sqlCond);
        return $this;
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker
     */
    public function updateAssignedProductsDates(Lanot_EasySticker_Model_Sticker $object)
    {
        //prepare time range
        $fromTime = strtotime($object->getFromDate());
        $fromTime = $fromTime ? $fromTime : 0;
        $toTime = strtotime($object->getToDate());
        $toTime = $toTime ? ($toTime + self::SECONDS_IN_DAY - 1) : 0;
        //prepare times array
        $data = array(
            'from_time' => $fromTime,
            'to_time'   => $toTime,
        );
        //update relation table
        $where = $this->_getWriteAdapter()->quoteInto('sticker_id = ?', $object->getId());
        $where.= ' AND auto = 0';//update only manually assigned table
        $this->_getWriteAdapter()->update($this->_getLinkTable(), $data, $where);
        return $this;
    }

    /**
     * @param array $productIds
     * @param Lanot_EasySticker_Model_Sticker $object
     * @return array
     */
    protected function _prepareInsertDataByProducts(array $productIds, Lanot_EasySticker_Model_Sticker $object)
    {
        $realWebsites = Mage::getModel('core/website')->getCollection()->getAllIds();
        $realGroups  = Mage::getModel('customer/group')->getCollection()->getAllIds();

        //prepare sicker data with fix for the deleted websites and customer groups
        $websiteIds = array_intersect($realWebsites, $object->getWebsiteIdsArray());
        $groupIds = array_intersect($realGroups, $object->getCustomerGroupIdsArray());

        $fromTime = strtotime($object->getFromDate());
        $fromTime = $fromTime ? $fromTime : 0;
        $toTime = strtotime($object->getToDate());
        $toTime = $toTime ? ($toTime + self::SECONDS_IN_DAY - 1) : 0;

        $rows = array();
        foreach($productIds as $productId) {
            foreach ($websiteIds as $websiteId) {
                foreach ($groupIds as $customerGroupId) {
                    $rows[] = $this->_getRowData($object->getId(), $productId, $websiteId, $customerGroupId, $fromTime, $toTime);
                }
            }
        }
        return $rows;
    }

    /**
     * @param array $stickerIds
     * @param Mage_Catalog_Model_Product $object
     * @return array
     */
    protected function _prepareInsertDataByStickers(array $stickerIds, Mage_Catalog_Model_Product $object)
    {
        $rows = array();
        $realWebsites = Mage::getModel('core/website')->getCollection()->getAllIds();
        $realGroups  = Mage::getModel('customer/group')->getCollection()->getAllIds();

        /** @var $sticker Lanot_PremiumSticker_Model_Sticker */
        foreach($this->_getStickerCollection($stickerIds) as $sticker) {
            //prepare sicker data with fix for the deleted websites and customer groups
            $websiteIds = array_intersect($realWebsites, $sticker->getWebsiteIdsArray());
            $groupIds = array_intersect($realGroups, $sticker->getCustomerGroupIdsArray());

            $fromTime = strtotime($sticker->getFromDate());
            $fromTime = $fromTime ? $fromTime : 0;
            $toTime = strtotime($sticker->getToDate());
            $toTime = $toTime ? ($toTime + self::SECONDS_IN_DAY - 1) : 0;

            //prepare row
            foreach ($websiteIds as $websiteId) {
                foreach ($groupIds as $groupId) {
                    $rows[] = $this->_getRowData($sticker->getId(), $object->getId(), $websiteId, $groupId, $fromTime, $toTime);
                }
            }
        }
        return $rows;
    }

    /**
     * @param Lanot_EasySticker_Model_Sticker $object
     * @param array $websiteIds
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker
     * @throws Exception
     */
    public function updateStickerConditionsProductData(Lanot_EasySticker_Model_Sticker $object, array $websiteIds = null)
    {
        $realWebsites = Mage::getModel('core/website')->getCollection()->getAllIds();
        $realGroups  = Mage::getModel('customer/group')->getCollection()->getAllIds();

        /** @var $rule Lanot_Rule_Model_Rule */
        $rule = $object->getRule();
        $table = $this->_getLinkTable();
        $stickerId = $object->getId();

        $write = $this->_getWriteAdapter();
        $write->beginTransaction();

        $this->_deleteOldProductData($object);

        if (!$this->_canApply($rule)) {//!$object->getIsActive() ||
            $write->commit();
            return $this;
        }

        if (empty($websiteIds)) {
            $websiteIds = $object->getWebsiteIdsArray();
        }

        $groupIds = $object->getCustomerGroupIdsArray();

        //fix for the deleted websites and customer groups
        $websiteIds = array_intersect($realWebsites, $websiteIds);
        $groupIds = array_intersect($realGroups, $groupIds);

        $fromTime = strtotime($object->getFromDate());
        $fromTime = $fromTime ? $fromTime : 0;
        $toTime = strtotime($object->getToDate());
        $toTime = $toTime ? ($toTime + self::SECONDS_IN_DAY - 1) : 0;

        Varien_Profiler::start('__MATCH_PRODUCTS__');
        $productIds = $rule->getMatchingProductIds();
        Varien_Profiler::stop('__MATCH_PRODUCTS__');

        $rows = array();
        try {
            foreach ($productIds as $productId) {
                foreach ($websiteIds as $websiteId) {
                    foreach ($groupIds as $groupId) {
                        $rows[] = $this->_getRowData($stickerId, $productId, $websiteId, $groupId, $fromTime, $toTime, 1);

                        if (count($rows) == 1000) {
                            $write->insertMultiple($table, $rows);
                            $rows = array();
                        }
                    }
                }
            }
            //insert last data
            if (!empty($rows)) {
                $write->insertMultiple($table, $rows);
            }
            //save data to table
            $write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }
        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);
        $this->_saveStoreImages($object);
        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker
     */
    protected function _saveStoreImages(Mage_Core_Model_Abstract $object)
    {
        $images = $object->getStoreImages();
        if (empty($images) || !is_array($images)) {
            return $this;
        }

        $write = $this->_getWriteAdapter();

        //#1. Prepare images
        $rows = $deleteStoreValues = array();
        foreach($images as $storeId => $image) {
            $deleteStoreValues[] = $storeId;
            if (!empty($image)) {
                $rows[] = array(
                    'sticker_id' => $object->getId(),
                    'store_id' => $storeId,
                    'image' => $image,
                );
            }
        }

        $table = $this->_getStoreImageTable();

        //#2. Remove old images
        if (!empty($deleteStoreValues)) {
            $where = $write->quoteInto('sticker_id=?', $object->getId());
            $where.= $write->quoteInto('AND store_id IN (?)', $deleteStoreValues);
            $write->delete($table, $where);
        }

        //#3. Insert new images
        if (!empty($rows)) {
            $write->insertMultiple($table, $rows);
        }
        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker
     */
    public function getStoresImages(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getWriteAdapter()->select()
            ->from($this->_getStoreImageTable(), array('store_id', 'image'))
            ->where('sticker_id = ? ', $object->getId());

        return $this->_getWriteAdapter()->fetchAll($select);
    }

}
