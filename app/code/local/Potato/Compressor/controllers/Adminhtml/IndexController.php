<?php

require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'System/ConfigController.php';
class Potato_Compressor_Adminhtml_IndexController extends Mage_Adminhtml_System_ConfigController
{
    public function progressAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        if (null === Mage::getSingleton('adminhtml/session')->getPoCompressorData()) {
            try {
                $this->_saveConfigData();
            } catch (Exception $e) {
                $session->addException($e,
                    Mage::helper('adminhtml')->__('An error occurred while saving this configuration:') . ' '
                    . $e->getMessage())
                ;
                $this->_redirect('*/*/edit', array('_current' => array('section', 'website', 'store')));
                return $this;
            }
            $_storeIds = $this->_getStoreIds();
            if (count($_storeIds) == 0) {
                return parent::saveAction();
            }
            $compressorData = new Varien_Object;
            $compressorData
                ->setStoresIds($_storeIds)
                ->setProgress(0)
                ->setStep(100 / count($_storeIds))
            ;
            $response = array(
                'progress' => 0
            );
            Mage::getSingleton('adminhtml/session')->setPoCompressorData($compressorData);
        } else {
            $compressorData = Mage::getSingleton('adminhtml/session')->getPoCompressorData();
            $_storeIds = $compressorData->getStoresIds();
            try {
                Mage::helper('po_compressor')->createIndex($_storeIds[key($_storeIds)]);
            } catch (Exception $e) {
                $session->addException($e,
                    Mage::helper('adminhtml')->__('An error occurred while saving this configuration:') . ' '
                    . $e->getMessage())
                ;
                $this->_redirect('*/*/edit', array('_current' => array('section', 'website', 'store')));
                return $this;
            }
            unset($_storeIds[key($_storeIds)]);
            $compressorData
                ->setStoresIds($_storeIds)
                ->setProgress(round($compressorData->getProgress() + $compressorData->getStep()))
            ;
            $response = array(
                'progress' => $compressorData->getProgress()
            );

            Mage::getSingleton('adminhtml/session')->setPoCompressorData($compressorData);
            if (count($compressorData->getStoresIds()) == 0) {
                $response['complete'] = 1;
                Mage::getSingleton('adminhtml/session')->setPoCompressorData(null);
            }
        }
        $response = Mage::helper('core')->jsonEncode($response);
        $this->getResponse()->setBody($response);
        return $this;
    }

    protected function _getStoreIds()
    {
        $website = $this->getRequest()->getParam('website', null);
        $store   = $this->getRequest()->getParam('store', null);
        $_storeIds = array();

        //store config
        if (null !== $store) {
            $storeModel = Mage::getModel('core/store')->load($store);
            if ($storeModel->getId()) {
                array_push($_storeIds, $storeModel->getId());
            }
        }

        //website config
        if (null === $store && null !== $website) {
            $websiteModel = Mage::getModel('core/website')->load($website);
            foreach ($websiteModel->getStoreCollection() as $storeModel) {
                array_push($_storeIds, $storeModel->getId());
            }
        }

        //default config
        if (null === $store && null === $website) {
            $storeCollection = Mage::getModel('core/store')->getCollection();
            foreach ($storeCollection as $storeModel) {
                array_push($_storeIds, $storeModel->getId());
            }
        }
        return $_storeIds;
    }

    protected function _saveConfigData()
    {
        $groups = $this->getRequest()->getPost('groups');
        if (isset($_FILES['groups']['name']) && is_array($_FILES['groups']['name'])) {
            /**
             * Carefully merge $_FILES and $_POST information
             * None of '+=' or 'array_merge_recursive' can do this correct
             */
            foreach($_FILES['groups']['name'] as $groupName => $group) {
                if (is_array($group)) {
                    foreach ($group['fields'] as $fieldName => $field) {
                        if (!empty($field['value'])) {
                            $groups[$groupName]['fields'][$fieldName] = array('value' => $field['value']);
                        }
                    }
                }
            }
        }

        // custom save logic
        $this->_saveSection();
        $section = $this->getRequest()->getParam('section');
        $website = $this->getRequest()->getParam('website');
        $store   = $this->getRequest()->getParam('store');
        Mage::getSingleton('adminhtml/config_data')
            ->setSection($section)
            ->setWebsite($website)
            ->setStore($store)
            ->setGroups($groups)
            ->save();

        // reinit configuration
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
        return $this;
    }
}