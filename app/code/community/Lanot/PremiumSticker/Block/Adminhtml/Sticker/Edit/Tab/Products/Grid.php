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

class Lanot_PremiumSticker_Block_Adminhtml_Sticker_Edit_Tab_Products_Grid
    extends Lanot_EasySticker_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId = 'easystickerProductsGrid';
    protected $_entityIdField = 'entity_id';
    protected $_itemParam = 'entity_id';
    protected $_formFieldName = 'products';

    protected $_isTabGrid = true;
    protected $_columnPrefix = 'products_';
    protected $_checkboxFieldName = 'products_in_selected';

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgridonly', array('_current' => true, '_secure'=>true));
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return !$this->_getAclHelper()->isActionAllowed('manage_sticker/assign');
    }

    /**
     * Retrieve selected items
     *
     * @return array
     */
    public function getSelectedLinks()
    {
        if (null !== $this->_selectedLinks) {
            return $this->_selectedLinks;
        }

        $this->_selectedLinks = array();
        $stickerId = $this->getRequest()->getParam('sticker_id');
        if ($stickerId) {
            $sticker = $this->_getItemModel()->load($stickerId);
            $this->_selectedLinks = $sticker->getSelectedProducts();
        }

        return $this->_selectedLinks;
    }

    /**
     * @return Lanot_EasySticker_Model_Sticker
     */
    protected function _getItemModel()
    {
        return Mage::getSingleton('lanot_easysticker/sticker');
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getCollection()
    {
        return Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        if ($this->_isTabGrid) {
            $this->addColumn('in_selected', array(
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_selected',
                'values' => $this->_getSelectedLinks(),
                'align' => 'center',
                'index' => $this->_entityIdField,
            ));
        }

        $this->addColumn($this->_entityIdField, array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'entity_id'
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));

        $this->addColumn('type', array(
            'header'    => Mage::helper('catalog')->__('Type'),
            'width'     => 100,
            'index'     => 'type_id',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name', array(
            'header'    => Mage::helper('catalog')->__('Attrib. Set Name'),
            'width'     => 130,
            'index'     => 'attribute_set_id',
            'type'      => 'options',
            'options'   => $sets,
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => 90,
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));

        $this->addColumn('visibility', array(
            'header'    => Mage::helper('catalog')->__('Visibility'),
            'width'     => 90,
            'index'     => 'visibility',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => 80,
            'index'     => 'sku'
        ));

        $this->addColumn('price', array(
            'header'        => Mage::helper('catalog')->__('Price'),
            'type'          => 'currency',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'         => 'price'
        ));

        $this->addColumn('updated_at', array(
            'header'  => Mage::helper('catalog')->__('Updated'),
            'type'    => 'date',
            'index'   => 'updated_at',
            'width'   => '150px',
        ));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }
}
