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

require_once('Lanot/PremiumSticker/controllers/Adminhtml/StickerController.php');

class Lanot_PremiumSticker_Adminhtml_Sticker_DynamicController
	extends Lanot_PremiumSticker_Adminhtml_StickerController
{
    protected $_msgTitle = 'Dynamic Stickers';
    protected $_aclSection = 'dynamic_sticker';
}
