<?php

class Potato_Compressor_Helper_Data extends Mage_Core_Helper_Abstract
{
    const MAIN_FOLDER = 'po_compressor';

    public function getRootCachePath()
    {
        return Mage::getBaseDir('media') . DS. self::MAIN_FOLDER;
    }

    public function getRootCacheUrl()
    {
        return Mage::getBaseUrl('media') . self::MAIN_FOLDER;
    }

    public function clearCache()
    {
        $this->_removeFolder($this->getRootCachePath());
        return $this;
    }

    protected function _removeFolder($dirPath)
    {
        $findedFiles = array_diff(scandir($dirPath), array('..', '.'));
        foreach ($findedFiles as $file) {
            if (is_dir($dirPath . DS . $file)) {
                $this->_removeFolder($dirPath . DS . $file);
                rmdir($dirPath . DS . $file);
                continue;
            }
            unlink($dirPath . DS . $file);
        }
        return $this;
    }

    public function startEmulation($storeId, $area = Mage_Core_Model_App_Area::AREA_FRONTEND)
    {
        if (class_exists('Mage_Core_Model_App_Emulation')) {
            $appEmulation = Mage::getSingleton('core/app_emulation');
            $emulateInfo = $appEmulation->startEnvironmentEmulation($storeId);
        } else {
            $emulateInfo = new Varien_Object;
            $emulateInfo->setStoreId(Mage::app()->getStore()->getId());
            Mage::app()->setCurrentStore($storeId);
            $initialDesign = Mage::getDesign()->setAllGetOld(array(
                    'package' => Mage::getStoreConfig('design/package/name', $storeId),
                    'store'   => $storeId,
                    'area'    => $area
                ));
            $emulateInfo->setDesign($initialDesign);
            Mage::getDesign()->setTheme('');
            Mage::getDesign()->setPackageName('');
        }

        return $emulateInfo;
    }

    public function stopEmulation(Varien_Object $emulateInfo)
    {
        if (class_exists('Mage_Core_Model_App_Emulation')) {
            $appEmulation = Mage::getSingleton('core/app_emulation');
            $appEmulation->stopEnvironmentEmulation($emulateInfo);
        } else {
            Mage::app()->setCurrentStore($emulateInfo->getStoreId());
            Mage::getDesign()->setAllGetOld($emulateInfo->getDesign());
            Mage::getDesign()->setTheme('');
            Mage::getDesign()->setPackageName('');
        }
        return $this;
    }

    public function createIndex($storeId)
    {
        $storeModel = Mage::getModel('core/store')->load($storeId);
        if ($storeModel->getId()) {
            $this->_prepareJsCssFiles($storeModel->getId());
        }
        return $this;
    }

    protected function _prepareJsCssFiles($storeId)
    {
        if (!Mage::helper('po_compressor/config')->isEnabled($storeId)) {
            return $this;
        }
        $layout = Mage::getModel('po_compressor/layout')->loadByStoreId($storeId);
        $jsCompressorModel = Mage::getModel('po_compressor/compressor_js')->loadByStoreId($storeId);
        if ($jsCompressorModel->canMerge()) {
            if (!$jsCompressorModel->isLoaded()) {
                try {
                    $jsCompressorModel->setHandleCollection($layout->getHandlesHeadItems());
                } catch (Exception $e) {
                    Mage::logException($e->getMessage());
                }
            }
            try {
                $jsCompressorModel->process();
            } catch (Exception $e) {
                Mage::logException($e->getMessage());
            }
        }
        $cssCompressorModel = Mage::getModel('po_compressor/compressor_css')->loadByStoreId($storeId);
        if ($cssCompressorModel->canMerge()) {
            if (!$cssCompressorModel->isLoaded()) {
                try {
                    $cssCompressorModel->setHandleCollection($layout->getHandlesHeadItems());
                } catch (Exception $e) {
                    Mage::logException($e->getMessage());
                }
            }
            try {
                $cssCompressorModel->process();
            } catch (Exception $e) {
                Mage::logException($e->getMessage());
            }
        }
        return $this;
    }
}