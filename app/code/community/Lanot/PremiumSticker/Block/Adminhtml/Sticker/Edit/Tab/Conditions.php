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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Lanot
 * @package     Lanot_PremiumSticker
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lanot_PremiumSticker_Block_Adminhtml_Sticker_Edit_Tab_Conditions
    extends Lanot_Rule_Block_Adminhtml_Catalog_Edit_Tab_Conditions
{
    protected $_condUrl = 'lanot_premiumsticker/adminhtml_sticker/newConditionHtml/form/rule_conditions_fieldset';

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('Auto Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('Auto Conditions');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_getAclHelper()->isActionAllowed('manage_sticker/assign');
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
     * @return Lanot_EasySticker_Model_Sticker
     */
    protected function _getItem()
    {
        return $this->_getHelper()->getStickerItemInstance();
    }

    /**
     * @return Lanot_Rule_Model_Rule
     */
    protected function _getRule()
    {
        return $this->_getItem()->getRule();
    }
}
