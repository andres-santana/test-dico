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
 * @category    Mage
 * @package     Mage_CatalogRule
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lanot_Rule_Model_Rule_Condition_Combine
    extends Mage_CatalogRule_Model_Rule_Condition_Combine
{
    protected $_condCombine = 'lanot_rule/rule_condition_combine';
    protected $_condProduct = 'lanot_rule/rule_condition_product';

    public function __construct()
    {
        parent::__construct();
        $this->setType($this->_condCombine);
    }

    /**
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $productAttributes = $this->_getProductCondition()->loadAttributeOptions()->getAttributeOption();
        $attributes = array();
        foreach ($productAttributes as $code => $label) {
            $attributes[] = array('value'=> $this->_condProduct . '|' . $code, 'label' => $label);
        }

        $conditions = array(
            array('label' => $this->_getHelper()->__('Please choose a condition to add...'), 'value'=>''),
            array('label' => $this->_getHelper()->__('Conditions Combination'), 'value' => $this->_condCombine),
            array('label' => $this->_getHelper()->__('Product Attribute'), 'value' => $attributes),
        );
        return $conditions;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getProductCondition()
    {
        return Mage::getModel($this->_condProduct);
    }

    /**
     * @return Lanot_Rule_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_rule');
    }

    /**
     * fix fo CE 18
     * @param $arr
     * @param string $key
     * @return Mage_Rule_Model_Condition_Combine
     */
    public function loadArray($arr, $key='conditions')
    {
        $this->setConditions(array());
        return parent::loadArray($arr, $key);
    }
}
