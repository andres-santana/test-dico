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
 * @package     Lanot_EasySticker
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Stickers List admin grid container
 *
 * @author Lanot
 */

class Lanot_EasySticker_Block_Adminhtml_Sticker
	extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'lanot_easysticker';
        $this->_controller = 'adminhtml_sticker';
        $this->_headerText = Mage::helper('lanot_easysticker')->__('Manage Stickers');

        parent::__construct();

        if (Mage::helper('lanot_easysticker/admin')->isActionAllowed('manage_sticker/save')) {
            $this->_updateButton('add', 'label', Mage::helper('lanot_easysticker')->__('Add New Sticker'));
        } else {
            $this->_removeButton('add');
        }
    }
}

