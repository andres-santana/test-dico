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
 * Dynamic Sticker admin edit form container
 *
 * @author Lanot
 */
class Lanot_PremiumSticker_Block_Adminhtml_Sticker_Dynamic_Edit
    extends Lanot_EasySticker_Block_Adminhtml_Sticker_Edit
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'lanot_premiumsticker';
        $this->_controller = 'adminhtml_sticker_dynamic';

        parent::__construct();

        $this->_removeButton('delete');
    }

    public function getHeaderText()
    {
        $model = Mage::helper('lanot_premiumsticker')->getStickerItemInstance();
        if ($model->getId()) {
            $title = $this->escapeHtml($model->getTitle());
            $header = Mage::helper('lanot_premiumsticker')->__("Edit Dynamic Sticker Item '%s'", $title);
        } else {
            throw new Exception('Could not edit dynamic sticker item');
        }
        return $header;
    }
}
