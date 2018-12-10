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
$coreStoreTable = $this->getTable('core/store');
$premiumStickerTable = $this->getTable('lanot_premiumsticker/sticker');
$premiumStickerImageTable = $this->getTable('lanot_premiumsticker/sticker_image');

$this->startSetup();

//create table for sticker images
$this->run("
    DROP TABLE IF EXISTS `{$premiumStickerImageTable}`;
    CREATE TABLE `{$premiumStickerImageTable}` (
        `sticker_id`   int(10) unsigned NOT NULL COMMENT 'Sticker ID',
        `store_id`     smallint(5) UNSIGNED NOT NULL COMMENT 'Store ID',
        `image`        varchar(255) DEFAULT NULL,

        UNIQUE KEY `UNQ_PRMSTKR_IMGSTORE_STCKR_TO_STORE` (`sticker_id`, `store_id`),
        CONSTRAINT `FK_PRMSTKR_IMGSTORE_STORE_ID` FOREIGN KEY (`store_id`) REFERENCES `{$coreStoreTable}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_PRMSTKR_IMGSTORE_STICKER_ID` FOREIGN KEY (`sticker_id`) REFERENCES `{$premiumStickerTable}` (`sticker_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Premium Sticker Images To Store Table';
");

$this->endSetup();