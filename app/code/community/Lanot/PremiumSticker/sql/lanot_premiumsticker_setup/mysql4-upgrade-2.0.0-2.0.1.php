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

/** @var $helper Lanot_PremiumSticker_Helper_Data */
$helper = Mage::helper('lanot_premiumsticker');

//prepare standard data for dynamic sticker
$item = array(
    'is_active'          => Lanot_PremiumSticker_Model_Sticker::STATUS_ENABLED,
    'type'               => Lanot_PremiumSticker_Model_Sticker::TYPE_DYNAMIC,
);

//prepare sizes
foreach ($helper->getAttributes() as $attributeCode) {
    $item['scale_' . $attributeCode] = '45';//45%
}

// Set up data rows
$dataRows = array(
    array(
        'title'         => 'Sticker For New Products',
        'image'         => '/d/y/dynamic_label_new.png',
        'position'      => 'top-left',
        'backend_model' => 'lanot_premiumsticker/sticker_backend_new',
    ),
    array(
        'title'         => 'Sticker For Products on Sale (with special price)',
        'image'         => '/d/y/dynamic_label_sale.png',
        'position'      => 'top-right',
        'backend_model' => 'lanot_premiumsticker/sticker_backend_sale',
    ),
    array(
        'title'         => 'Sticker For Sold Products (is not salable)',
        'image'         => '/d/y/dynamic_label_sold.png',
        'position'      => 'bottom-right',
        'backend_model' => 'lanot_premiumsticker/sticker_backend_sold',
    ),
);

/** @var $model Lanot_PremiumSticker_Model_Sticker */
$model = Mage::getModel('lanot_easysticker/sticker');

//save dynamic stickers to a database
foreach ($dataRows as $data) {
    $data = array_merge($data, $item);
    $model->setData($data)->setOrigData()->save();
}
