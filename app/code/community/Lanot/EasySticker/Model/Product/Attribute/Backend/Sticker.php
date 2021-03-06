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

class Lanot_EasySticker_Model_Product_Attribute_Backend_Sticker
    extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    /**
     * Before Attribute Save Process
     *
     * @param Mage_Catalog_Model_Product $object
     * @return Lanot_EasySticker_Model_Product_Attribute_Backend_Sticker
     */
    public function beforeSave($object) {
        $attributeCode = $this->getAttribute()->getName();
        $data = $object->getData($attributeCode);

        if (!is_array($data)) {
            $data = array();
        }

        $object->setData($attributeCode, implode(',', $data));

        if (is_null($object->getData($attributeCode))) {
            $object->setData($attributeCode, null);
        }

        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Product $object
     * @return Lanot_EasySticker_Model_Product_Attribute_Backend_Sticker
     */
    public function afterLoad($object) {
        $attributeCode = $this->getAttribute()->getName();
        $data = $object->getData($attributeCode);
        if ($data) {
            $object->setData($attributeCode, explode(',', $data));
        }

        return $this;
    }
}
