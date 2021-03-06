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
 * Sticker admin edit tabs block
 *
 * @author Lanot
 */
class Lanot_EasySticker_Block_Adminhtml_Sticker_Edit_Tabs
	extends Mage_Adminhtml_Block_Widget_Tabs
{
	/**
	 * Initialize tabs and define tabs block settings
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->_getHelper()->__('Sticker Info'));
	}

    /**
     * @return Lanot_EasySticker_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_easysticker');
    }
}
