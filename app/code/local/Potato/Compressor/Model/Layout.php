<?php

class Potato_Compressor_Model_Layout
{
    const LAYOUTS_FOLDER                = 'layouts';

    protected $_storeId                 = null;
    protected $_commonJsCssItems        = array();
    protected $_handleItems             = array();

    protected $_designPackageFolder     = 'base';
    protected $_designThemeFolder       = 'default';
    protected $_infoFilename            = '.info';
    protected $_filesList               = array();

    public function loadByStoreId($storeId)
    {
        $this->_storeId = $storeId;
        $initialEnvironmentInfo = Mage::helper('po_compressor')->startEmulation($storeId);

        $storeDesignModel = Mage::getModel('core/design_package');
        $storeDesignModel->setStore($storeId);
        $this->_designPackageFolder = $storeDesignModel->getPackageName();
        $this->_designLayoutThemeFolder = $storeDesignModel->getTheme('layout');

        Mage::helper('po_compressor')->stopEmulation($initialEnvironmentInfo);

        if (file_exists($this->_getFullPath() . DS . $this->_infoFilename)) {
            $_fileContent = file_get_contents($this->_getFullPath() . DS . $this->_infoFilename);
            $this->_handleItems = unserialize($_fileContent);
            if (!is_array($this->_handleItems)) {
                $this->_handleItems = array();
            }
        }
        return $this;
    }

    public function getHandlesFilesList($handles)
    {
        foreach ($this->_handleItems as $handleName => $handleData) {
            if (!in_array($handleName, $handles)) {
                continue;
            }
            array_walk_recursive($this->_handleItems, array(&$this, 'prepareFilesList'));
        }
        return array_unique($this->_filesList);
    }

    public function prepareFilesList($value)
    {
        if (pathinfo($value, PATHINFO_EXTENSION) == Potato_Compressor_Model_Compressor_Css::FILE_EXTENSION
            || pathinfo($value, PATHINFO_EXTENSION) == Potato_Compressor_Model_Compressor_Js::FILE_EXTENSION
        ) {
            $this->_filesList[] = $value;
        }
        return $this;
    }

    protected function _save()
    {
        $this->_checkAndPrepareFolders();
        if (file_exists($this->_getFullPath() . DS . $this->_infoFilename)) {
            unlink($this->_getFullPath() . DS . $this->_infoFilename);
        }

        file_put_contents($this->_getFullPath() . DS . $this->_infoFilename, serialize($this->getHandlesHeadItems()));
        return $this;
    }

    protected function _checkAndPrepareFolders()
    {
        $this
            ->_prepareFolder(Mage::helper('po_compressor')->getRootCachePath())
            ->_prepareFolder($this->_getLayoutsPath())
            ->_prepareFolder($this->_getLayoutsPath() . DS . $this->_designPackageFolder)
            ->_prepareFolder($this->_getFullPath())
        ;
        return $this;
    }

    protected function _prepareFolder($folderPath)
    {
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777);
        }
        return $this;
    }

    protected function _getFullPath()
    {
        return $this->_getLayoutsPath() . DS . $this->_designPackageFolder . DS . $this->_designLayoutThemeFolder;
    }

    protected function _getLayoutsPath()
    {
        return Mage::helper('po_compressor')->getRootCachePath() . DS . self::LAYOUTS_FOLDER;
    }

    public function getHandlesHeadItems()
    {
        if (count($this->_handleItems) == 0) {
            $this->_prepareThemeHeadItems();
        }
        return $this->_handleItems;
    }

    protected function _prepareThemeHeadItems()
    {
        if (null !== $this->_storeId) {
            $initialEnvironmentInfo = Mage::helper('po_compressor')->startEmulation($this->_storeId);

            $layoutModel = Mage::getModel('core/layout');
            $design = Mage::getSingleton('core/design_package');
            $design->setStore($this->_storeId);
            $layoutUpdateModel = Mage::getModel('core/layout_update');

            //theme layout all handles
            $handles = $layoutUpdateModel->getFileLayoutUpdatesXml(
                $design->getArea(),
                $design->getPackageName(),
                $design->getTheme('layout'),
                $this->_storeId
            );

            $this->_attachCmsPagesUpdates($handles);

            //array for store all removed js/css files
            $removedItems = array();

            //search head block actions in each handle
            foreach ($handles as $handleName => $updateXml) {
                $layoutUpdateModel = Mage::getModel('core/layout_update')
                    ->fetchRecursiveUpdates($updateXml)
                    ->addUpdate($updateXml->innerXml())
                ;
                $xml = $layoutUpdateModel->asSimplexml();
                $headBlock = $layoutModel->createBlock('po_compressor/page_html_head', 'head');

                if (array_key_exists($handleName, $this->_handleItems)) {
                    $headBlock->setItems($this->_handleItems[$handleName]['items']);
                }

                //xml node for block type page/html_head actions only
                $headBlockNodeActions = $xml->xpath("//block[@type='page/html_head']//action");

                //xml node for reference[@name='head'] actions only
                $referenceHeadBlockNodeActions = $xml->xpath("//reference[@name='head']//action");

                //process page/html_head node actions
                if ($headBlockNodeActions){
                    foreach ($headBlockNodeActions as $action) {
                        $method = (string)$action['method'];
                        if (in_array($method, $this->getAllowedActions())) {
                            $actionParams = $action->asArray();
                            unset($actionParams['@']);
                            call_user_func_array(array($headBlock, $method), $actionParams);
                        }
                    }
                }

                //process reference[@name='head'] node actions
                if ($referenceHeadBlockNodeActions){
                    foreach ($referenceHeadBlockNodeActions as $action) {
                        $method = (string)$action['method'];
                        if (in_array($method, $this->getAllowedActions())) {
                            $actionParams = $action->asArray();
                            unset($actionParams['@']);
                            call_user_func_array(array($headBlock, $method), $actionParams);
                        }
                    }
                }

                //store head items data
                $this->_handleItems[$handleName]['items'] = $headBlock->getItems();

                //get removed js/css files for current handle
                if (count($headBlock->getRemovedItems()) != 0) {
                    $handleRemovedFiles = $headBlock->getRemovedItems();

                    //store removed js/css files for current handle in common array collection
                    $removedItems = array_merge($removedItems, $handleRemovedFiles['items']);

                    //store removed js/css files for current handle in handle own array collection
                    if (array_key_exists('removed', $this->_handleItems[$handleName])) {
                        $this->_handleItems[$handleName]['removed'] = array_merge($this->_handleItems[$handleName]['removed'], $handleRemovedFiles['items']);
                    } else {
                        $this->_handleItems[$handleName]['removed'] = $handleRemovedFiles['items'];
                    }
                }
            }

            //if no default handle -> skip searching common files
            if (array_key_exists('default', $this->_handleItems)) {
                foreach ($this->_handleItems['default']['items'] as $key => $params) {
                    if (!array_key_exists($key, $removedItems)) {
                        $this->_handleItems['common']['items'][$key] = $params;
                        unset($this->_handleItems['default']['items'][$key]);
                    }
                }
                ksort($this->_handleItems);
                $this->_prepareDefaultHandles();
                $this->_prepareHandleItems();
            }
            Mage::helper('po_compressor')->stopEmulation($initialEnvironmentInfo);
            $this->_save();
        }
        return $this;
    }

    protected function _attachCmsPagesUpdates(Mage_Core_Model_Layout_Element $handles)
    {
        $cmsPagesCollection = Mage::getModel('cms/page')->getCollection();
        $_update = new Mage_Core_Model_Layout_Update;
        foreach ($cmsPagesCollection as $cmsPage) {
            $cmsPage->setStoreId($this->_storeId);
            if (!$cmsPage->load($cmsPage->getId()) || !$cmsPage->getId()
                || !strip_tags($cmsPage->getLayoutUpdateXml())
            ) {
                continue;
            }
            $cmsPageIdentifier = str_replace('/', '_', $cmsPage->getIdentifier());
            $updateXml = simplexml_load_string('<cms_page_' . $cmsPageIdentifier . '>'
                . $cmsPage->getLayoutUpdateXml() . '</cms_page_'
                . $cmsPageIdentifier . '>', $_update->getElementClass()
            );
            $handles->appendChild($updateXml);
        }
    }

    protected function _prepareHandleItems()
    {
        foreach ($this->_handleItems as $_handleName => $_handleItems) {
            $_ifArray     = array();
            $_paramsArray = array();
            $_commonArray = array();

            foreach ($_handleItems['items'] as $item) {
                if (!file_exists($item['file_path'])) {
                    continue;
                }

                if (array_key_exists('if', $item) && null !== $item['if'] && '' !== trim($item['if'])) {
                    $_ifArray = $this->_getMergedItemConditions($_ifArray, $item, 'if');
                    continue;
                }

                if (array_key_exists('params', $item) && null !== $item['params'] && '' !== trim($item['params'])) {
                    $_paramsArray = $this->_getMergedItemConditions($_paramsArray, $item, 'params');
                    continue;
                }
                $_commonArray[0]['files'][] = $item['file_path'];
            }
            $this->_handleItems[$_handleName] = array(
                'if' => $_ifArray,
                'params' => $_paramsArray,
                'common' => $_commonArray
            );
        }
        return $this;
    }

    protected function _getMergedItemConditions(array $container, array $item, $index)
    {
        $_existFlag = false;
        foreach ($container as &$_element) {
            if ($_element['condition'] == $item[$index]) {
                $_element['files'][] = $item['file_path'];
                $_existFlag = true;
                break;
            }
        }

        if (!$_existFlag) {
            $container[] = array('condition' => $item[$index], 'files' => array($item['file_path']));
        }
        return $container;
    }

    protected function _prepareDefaultHandles($preparedHandleName = 'default')
    {
        foreach ($this->_handleItems as $_handleName => $_handle) {

            if (array_key_exists('removed', $this->_handleItems[$_handleName])) {

                //skip if handle already exist
                if (strpos($preparedHandleName, $_handleName) !== FALSE
                    || array_key_exists($preparedHandleName . '&' . $_handleName, $this->_handleItems)
                ) {
                    continue;
                }

                $removedItems = $this->_handleItems[$_handleName]['removed'];

                //merge current handle removed files with previous handle removed handle
                if ($preparedHandleName != 'default' && array_key_exists($preparedHandleName, $this->_handleItems)) {
                    $removedItems = array_merge($this->_handleItems[$preparedHandleName]['_removed_'], $removedItems);
                }

                //create new merged handle
                $this->_handleItems[$preparedHandleName . '&' . $_handleName]['items'] = array();
                foreach ($this->_handleItems['default']['items'] as $key => $params) {
                    if (!array_key_exists($key, $removedItems)) {
                        $this->_handleItems[$preparedHandleName . '&' . $_handleName]['items'][$key] = $params;
                    }
                }
                $this->_handleItems[$preparedHandleName . '&' . $_handleName]['_removed_'] = $removedItems;

                //sorting by handle names
                if ($preparedHandleName != 'default') {
                    $preparedHandleName = explode('&', $preparedHandleName);
                    unset($preparedHandleName[0]);
                    sort($preparedHandleName);
                    $preparedHandleName = implode('&', $preparedHandleName);
                    $preparedHandleName = 'default' . '&' . $preparedHandleName;
                }
                $this->_prepareDefaultHandles($preparedHandleName . '&' . $_handleName);
            }
        }
        return $this;
    }

    public function getAllowedActions()
    {
        return array('addJs', 'addJsIe', 'addCss', 'addCssIe', 'addItem', 'removeItem');
    }
}