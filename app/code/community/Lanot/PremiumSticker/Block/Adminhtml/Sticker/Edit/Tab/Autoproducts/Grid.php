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

class Lanot_PremiumSticker_Block_Adminhtml_Sticker_Edit_Tab_Autoproducts_Grid
    extends Lanot_PremiumSticker_Block_Adminhtml_Sticker_Edit_Tab_Products_Grid
{
    protected $_gridId = 'premiumstickerAutoProductsGrid';
    protected $_entityIdField = 'entity_id';
    protected $_itemParam = 'entity_id';
    protected $_formFieldName = 'autoproducts';

    protected $_isTabGrid = true;
    protected $_columnPrefix = 'autoproducts_';

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/autoproductsgrid', array('_current' => true, '_secure'=>true));
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
            $this->_selectedLinks = $sticker->getSelectedProductsAuto();
        }

        return $this->_selectedLinks;
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->removeColumn('in_selected');
        return $this;
    }

    /**
     * Show only selected products
     *
     * @return bool
     */
    public function isReadonly()
    {
        return true;
    }
}
