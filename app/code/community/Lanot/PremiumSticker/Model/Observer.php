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

class Lanot_PremiumSticker_Model_Observer extends Lanot_EasySticker_Model_Observer
{
    /**
     * @return Lanot_PremiumSticker_Model_Sticker
     */
    protected function _getItemModel()
    {
        return Mage::getSingleton('lanot_easysticker/sticker');
    }

    /**
     * @param Mage_Core_Model_Abstract|Varien_Data_Collection $products
     * @return Lanot_PremiumSticker_Model_Observer
     */
    public function prepareStickers($products)
    {
        parent::prepareStickers($products);
        $this->prepareStickersDynamic($products);
        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract|Varien_Data_Collection $products
     * @return Lanot_PremiumSticker_Model_Observer
     */
    public function prepareStickersDynamic($products)
    {
        if ($products instanceof Mage_Core_Model_Abstract) {
            $catProducts = array($products);
        } elseif ($products instanceof Varien_Data_Collection) {
            $catProducts = $products->getItems();
        } else {
            return array();
        }

        //load from cache stickers by products ids
        $stickers = $this->_getItemModel()->loadCacheByProductIds('dynamic');
        if ($stickers === false) {//performance trick - check once
            $stickers = $this->_getStickersCollectionDynamic()->getItems();
            $this->_getItemModel()->saveCacheByProductIds($stickers, 'dynamic');
        }

        //assign stickers to products
        if ($stickers && is_array($stickers)) {
            /** @var $p Mage_Catalog_Model_Product */
            foreach($catProducts as $p) {
                /** @var $s Lanot_PremiumSticker_Model_Sticker */
                foreach($stickers as $s) {
                    //check if sticker is suitable for the product
                    if ($s->isMatched($p)) {
                        $this->addStickerToProduct($p->getId(), $s->getId(), $s->getData());
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param array $sticker
     * @param $attribute
     * @param $key
     * @return Lanot_PremiumSticker_Model_Observer
     */
    protected function _addStickerWatermarkToImage(array $sticker, $attribute, $key = 'image')
    {
        if(!empty($sticker['store_image'])) {
            $key = 'store_image';
        }
        return parent::_addStickerWatermarkToImage($sticker, $attribute, $key);
    }

    /**
     * @param array $productIds
     * @return Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection
     */
    protected function _getStickersCollection(array $productIds)
    {
        /** @var $collection Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection */
        $collection = $this->_getItemModel()->getCollection()
            ->useActive()
            ->setCollectAll(true)
            ->addFilters($productIds,
                $this->_getHelper()->getWebsiteId(),
                $this->_getHelper()->getCustomerGroupId(),
                time()
            )
            ->addFilter('type', Lanot_PremiumSticker_Model_Sticker::TYPE_STATIC)
            ->addStoreImage($this->_getHelper()->getStoreId());
        return $collection;
    }

    /**
     * @return array
     */
    protected function _getStickersCollectionDynamic()
    {
        /** @var $collection Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection */
        $collection = $this->_getItemModel()->getCollection()
            ->useActive()
            ->setCollectAll(true)
            ->addFilter('type', Lanot_PremiumSticker_Model_Sticker::TYPE_DYNAMIC)
            ->addStoreImage($this->_getHelper()->getStoreId());
        return $collection;
    }

    /**
     * @param $string
     * @return string
     */
    protected function _getNewAttributeHash($string)
    {
        $string .= '_w' . $this->_getHelper()->getWebsiteId();
        $string .= '_st' . $this->_getHelper()->getStoreId();
        $string .= '_cgi' . $this->_getHelper()->getCustomerGroupId();
        $string .= '_d' . now(true);
        return parent::_getNewAttributeHash($string);
    }

    /**
     * Apply all stickers for specific product
     *
     * @param   Varien_Event_Observer $observer
     * @return  Lanot_PremiumSticker_Model_Observer
     */
    public function assignAllStickersToProduct($observer)
    {
        $product = $observer->getEvent()->getProduct();
        if (!$product->getIsMassupdate() && $product->getId()) {
            $this->applyToProducts($product->getId());
        }
        return $this;
    }

    /**
     * Prepare sticker relations for imported products
     *
     * @param   Varien_Event_Observer $observer
     * @return  Lanot_PremiumSticker_Model_Observer
     */
    public function assignAllStickersToImportedProducts($observer)
    {
        $affectedEntityIds = $observer->getEvent()->getAdapter()->getAffectedEntityIds();
        if (!empty($affectedEntityIds)) {
            $this->applyToProducts($affectedEntityIds);
        }
        return $this;
    }

    /**
     * @param $productIds
     * @return Lanot_PremiumSticker_Model_Observer
     */
    public function applyToProducts($productIds)
    {
        if (!is_array($productIds)) {
            $productIds = array($productIds);
        }
        /** @var $stickerCollection Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection */
        $stickerCollection = $this->_getItemModel()->getCollection()
            ->useActive()
            ->addFilter('type', Lanot_PremiumSticker_Model_Sticker::TYPE_STATIC)
            ->addOrder('sticker_id', 'ASC');

        /** @var $sticker Lanot_PremiumSticker_Model_Sticker */
        foreach ($stickerCollection as $sticker) {
            $sticker->prepareRuleLoad()->applyToProducts($productIds);
        }
        return $this;
    }

    /**
     * Apply collection filters -> static type
     *
     * @param   Varien_Event_Observer $observer
     * @return  Lanot_PremiumSticker_Model_Observer
     */
    public function prepareCollectionStatic($observer)
    {
        /** @var $stickerCollection Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection */
        $stickerCollection = $observer->getEvent()->getCollection();
        $stickerCollection->addFilter('type', Lanot_PremiumSticker_Model_Sticker::TYPE_STATIC);
        return $this;
    }

    /**
     * Apply collection filters -> dynamic type
     *
     * @param   Varien_Event_Observer $observer
     * @return  Lanot_PremiumSticker_Model_Observer
     */
    public function prepareCollectionDynamic($observer)
    {
        /** @var $stickerCollection Lanot_PremiumSticker_Model_Mysql4_Sticker_Collection */
        $stickerCollection = $observer->getEvent()->getCollection();
        $stickerCollection->addFilter('type', Lanot_PremiumSticker_Model_Sticker::TYPE_DYNAMIC);
        return $this;
    }

    /**
     * Add custom fields to a form
     *
     * @param   Varien_Event_Observer $observer
     * @return  Lanot_PremiumSticker_Model_Observer
     */
    public function prepareForm($observer)
    {
        $this->_addBackendField($observer->getForm(), $observer->getSticker());
        $this->_addStoreImagesFields($observer->getForm(), $observer->getSticker());
        return $this;
    }

    /**
     * Add image column to grin
     *
     * @param   Varien_Event_Observer $observer
     * @return  Lanot_PremiumSticker_Model_Observer
     */
    public function prepareImageColumn($observer)
    {
        /** @var $grid Lanot_EasySticker_Block_Adminhtml_Sticker_Grid */
        $grid = $observer->getGrid();
        if ($grid) {
            if (method_exists($grid, 'getColumnPrefix')) {
                $after = $grid->getColumnPrefix() . $grid->getEntityIdField();
            } else {
                $after = 'sticker_id';
            }

            $grid->addColumnAfter('image', array(
                'header' => $this->_getHelper()->__('Image'),
                'index'  => 'image',
                'type'   => 'image',
                'filter' => false,
                'width'  => 80,
                'renderer' => 'lanot_premiumsticker/adminhtml_grid_renderer_image',
            ), $after);
        }
        return $this;
    }

    /**
     * Adds additional field to a form for dynamic sticker type
     *
     * @param Varien_Data_Form $form
     * @param Lanot_PremiumSticker_Model_Sticker $sticker
     * @return Lanot_PremiumSticker_Model_Observer
     */
    protected function _addBackendField($form, $sticker)
    {
        if ($sticker->getType() == Lanot_PremiumSticker_Model_Sticker::TYPE_DYNAMIC) {
            /** @var $fieldset Varien_Data_Form_Element_Fieldset */
            $fieldset = $form->getElement('base_fieldset');
            $fieldset->addField('backend_model', 'text', array(
                'name'     => 'backend_model_text',
                'label'    => $this->_getHelper()->__('Backend Model'),
                'title'    => $this->_getHelper()->__('Backend Model'),
                'disabled' => true,
            ), 'title');
        }
        return $this;
    }

    /**
     * Adds additional fields
     *
     * @param Varien_Data_Form $form
     * @param Lanot_PremiumSticker_Model_Sticker $sticker
     * @return Lanot_PremiumSticker_Model_Observer
     */
    protected function _addStoreImagesFields($form, $sticker)
    {
        $isElementDisabled = !$this->_getAclHelper()->isActionAllowed('manage_sticker/save');

        $element = $form->getElement('image');
        if ($element) {
            $element->setLabel($this->_getHelper()->__('Default Image (*.png)'));
        }

        /** @var $store Mage_Core_Model_Store */
        if (!Mage::app()->isSingleStoreMode()) {

            /** @var $fieldset Varien_Data_Form_Element_Fieldset */
            $fieldset = $form->addFieldset('store_fieldset', array(
                'legend' => $this->_getHelper()->__('Multistore Images')
            ));

            foreach ($this->_getStores() as $store) {
                $elementId = sprintf('store_image_%d', $store->getId());
                $name = sprintf('sticker_store_image_%d', $store->getId());

                $element = $fieldset->addField($elementId, 'image', array(
                    'name'     => $name,
                    'label'    => $this->_getHelper()->__('%s Image (*.png)', $store->getName()),
                    'comment'  => $this->_getHelper()->__('Upload image with transparent background'),
                    'disabled' => $isElementDisabled,
                    'style'    => 'width: 200px;',
                    'fieldset_html_class' => 'image-row-css',
                ), 'image');

                $element->setValue($sticker->getData($elementId));
                $this->_prepareImage($element);
            }
        }

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
     * @return Lanot_PremiumSticker_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_premiumsticker');
    }

    /**
     * @return Lanot_EasySticker_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_easysticker/admin');
    }

    /**
     * @param Varien_Data_Form_Element_Image $element
     * @return Lanot_EasySticker_Block_Adminhtml_Sticker_Edit_Tab_Main
     */
    protected function _prepareImage($element)
    {
        if ($element->getValue()) {
            $path = $this->_getItemModel()->getMediaPath() . $element->getValue();
            $element->setValue($path);
        }
        return $this;
    }
}
