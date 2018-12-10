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
class Lanot_PremiumSticker_Block_Adminhtml_Sticker_Dynamic
	extends Lanot_EasySticker_Block_Adminhtml_Sticker
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'lanot_premiumsticker';
        $this->_controller = 'adminhtml_sticker_dynamic';
        $this->_headerText = Mage::helper('lanot_premiumsticker')->__('Manage Dynamic Stickers');

        $this->_removeButton('add');
    }
}

