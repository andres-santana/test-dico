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

require_once('Lanot/EasySticker/controllers/Adminhtml/StickerController.php');

class Lanot_PremiumSticker_Adminhtml_StickerController
	extends Lanot_EasySticker_Adminhtml_StickerController
{
    protected $_msgTitle = 'Static Stickers';

    /**
     * Products Grid with serializer ajax action
     */
    public function productsgridAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Products Grid only ajax action
     */
    public function productsgridonlyAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Auto Products Grid only ajax action
     */
    public function autoproductsgridAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Prepare conditions html block for selected condition
     */
    public function newConditionHtmlAction()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule($this->_getRule())
            ->setPrefix('conditions');
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }
        if ($model instanceof Mage_Rule_Model_Condition_Abstract) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function _preparePostData($data)
    {
        $data = $this->_filterDates($data, array('from_date', 'to_date'));
        $data['conditions'] = isset($data['rule']['conditions']) ? $data['rule']['conditions'] : null;
        unset($data['rule']);
        return $data;
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     * @return Lanot_EasySticker_Adminhtml_StickerController
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        $model->getRule()->getConditions()->setJsFormObject('rule_conditions_fieldset');
        return parent::_registerItem($model);
    }

    /**
     * @return Lanot_Rule_Model_Rule
     */
    protected function _getRule()
    {
        return $this->_getItemModel()->getRule();
    }
}
