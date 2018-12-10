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

class Lanot_Rule_Block_Adminhtml_Catalog_Edit_Tab_Conditions
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_condUrl = '*/*/newConditionHtml/form/rule_conditions_fieldset';

    protected function _prepareForm()
    {
        /* @var $model Mage_Core_Model_Abstract */
        $model = $this->_getItem();
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('conditions_fieldset', array(
                'legend' => $this->_getHelper()->__('Conditions (leave blank to skip)'))
        )->setRenderer($this->_getFieldSetRenderer());

        $fieldset->addField('conditions', 'text', array(
            'name' => 'conditions',
            'label' => $this->_getHelper()->__('Conditions'),
            'title' => $this->_getHelper()->__('Conditions'),
            'required' => true,
        ))->setRule($this->_getRule())->setRenderer($this->_getRuleRenderer());

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('Conditions');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return $this->_isAllowed();
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return !$this->_isAllowed();
    }

    /**
     * @return Mage_CatalogRule_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('catalogrule');
    }

    protected function _getRuleRenderer()
    {
        return Mage::getBlockSingleton('rule/conditions');
    }

    protected function _getFieldSetRenderer()
    {
        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl($this->_condUrl, array('_secure' => true)));
        return $renderer;
    }

    protected function _getItem()
    {
        return new Varien_Object();
    }

    protected function _getRule()
    {
        return $this->_getItem();
    }

    protected function _isAllowed()
    {
        return true;
    }
}
