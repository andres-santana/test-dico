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
 * Dynamic Stickers List admin grid container
 *
 * @author Lanot
 */
class Lanot_PremiumSticker_Block_Adminhtml_Grid_Renderer_Image
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @var int
     */
    protected $_width = 80;

    /**
     * @var int
     */
    protected $_height = 80;

    /**
     * @param Lanot_PremiumSticker_Model_Sticker $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $img =  $row->getData($this->getColumn()->getIndex());
        if (empty($img)) {
            return '';
        }

        $img = $row->getMediaPath() . $img;
        $img = $this->_getHelper()->resizeImg($img, $this->_width, $this->_height);

        return sprintf('<img src="%s" border="0" alt=""/>', $img);
    }

    /**
     * @return Lanot_PremiumSticker_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_premiumsticker');
    }
}
