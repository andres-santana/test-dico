<?php

class Potato_Compressor_Block_Page_Html_Head extends Mage_Page_Block_Html_Head
{
    protected $_removedItems = array();

    public function getCssJsHtml()
    {
        if (!Mage::helper('po_compressor/config')->isEnabled()) {
            return parent::getCssJsHtml();
        }

        $loadedHandles = $this->getLayout()->getUpdate()->getHandles();
        $storeId = Mage::app()->getStore()->getId();
        $layoutModel = Mage::getModel('po_compressor/layout')->loadByStoreId($storeId);
        $_preparedMergedFiles = $layoutModel->getHandlesFilesList($loadedHandles);
        $jsCompressorModel = Mage::getModel('po_compressor/compressor_js')->setStoreId($storeId);

        if ($jsCompressorModel->canMerge()) {
            $jsPreparedFiles = $jsCompressorModel->loadPreparedHandles($loadedHandles);
            if ($jsPreparedFiles) {
                $_items = array();
                foreach ($this->getItems() as $item) {
                    if($item['type'] != 'js' && $item['type'] != 'skin_js'
                        || (($item['type'] == 'js' || $item['type'] == 'skin_js') && !in_array($item['file_path'], $_preparedMergedFiles))
                        || !$jsCompressorModel->isAllowToCompress($item['file_path'])
                    ) {
                        array_push($_items, $item);
                    }
                }
                $this->setItems($_items);
            }
        }

        $cssCompressorModel = Mage::getModel('po_compressor/compressor_css')->setStoreId($storeId);
        if ($cssCompressorModel->canMerge()) {
            $cssPreparedFiles = $cssCompressorModel->loadPreparedHandles($loadedHandles);
            if ($cssPreparedFiles) {
                $_items = array();
                foreach ($this->getItems() as $item) {
                    if($item['type'] != 'js_css' && $item['type'] != 'skin_css'
                        || (($item['type'] == 'js_css' || $item['type'] == 'skin_css') && !in_array($item['file_path'], $_preparedMergedFiles))
                        || !$cssCompressorModel->isAllowToCompress($item['file_path'])
                    ) {
                        array_push($_items, $item);
                    }
                }
                $this->setItems($_items);
            }
        }
        $_result = '';

        if (isset($cssPreparedFiles)) {
            $_result .= $cssPreparedFiles;
        }

        if (trim(parent::getCssJsHtml()) != '') {
            $_result = parent::getCssJsHtml() . $_result;
        }

        if (isset($jsPreparedFiles)) {
            $_result = $jsPreparedFiles . $_result;
        }
        return $_result;
    }

    public function getRemovedItems()
    {
        return $this->_removedItems;
    }

    public function getItems()
    {
        if (array_key_exists('items', $this->_data)) {
            return $this->_data['items'];
        }
        return array();
    }

    public function removeItem($type, $name)
    {
        $this->_removedItems['items'][$type.'/'.$name] = true;
        return parent::removeItem($type, $name);
    }

    public function addItem($type, $name, $params=null, $if=null, $cond=null)
    {
        $_result = parent::addItem($type, $name, $params, $if, $cond);
        $designPackage = Mage::getDesign();

        $this->_data['items'][$type . '/' . $name]['file_path'] = Mage::getBaseDir() . DS . 'js' . DS . $name;
        if ($type == 'skin_js' || $type == 'skin_css') {
            $this->_data['items'][$type . '/' . $name]['file_path'] = $designPackage->getFilename($name, array('_type' => 'skin'));
        }
        return $_result;
    }
}