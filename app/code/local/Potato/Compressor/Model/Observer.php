<?php

class Potato_Compressor_Model_Observer
{
    public function attachCmsPageHandle($observer)
    {
        if (!Mage::helper('po_compressor/config')->isEnabled()) {
            return $this;
        }
        $cmsPage = $observer->getEvent()->getPage();
        $controller = $observer->getEvent()->getControllerAction();
        $cmsPageIdentifier = str_replace('/', '_', $cmsPage->getIdentifier());
        $controller->getLayout()->getUpdate()->addHandle('cms_page_' . $cmsPageIdentifier);
        if (Mage::getBlockSingleton('page/html_header')->getIsHomePage()) {
            $controller->getLayout()->getUpdate()->addHandle('cms_index_index');
        }
        return $this;
    }

    public function refreshCache()
    {
        Mage::helper('po_compressor')->clearCache();
        $storeCollection = Mage::getModel('core/store')->getCollection();
        foreach ($storeCollection as $storeModel) {
            Mage::helper('po_compressor')->createIndex($storeModel->getId());
        }
        return $this;
    }
}