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

/** @var $this Mage_Core_Model_Resource_Setup */

//tables definition
$premiumStickerTable = $this->getTable('lanot_premiumsticker/sticker');

$this->startSetup();

$this->run("ALTER TABLE `{$premiumStickerTable}` ADD `type` SMALLINT DEFAULT 0 AFTER `is_active`");
$this->run("ALTER TABLE `{$premiumStickerTable}` ADD `backend_model` VARCHAR(64) DEFAULT NULL");

$this->endSetup();
