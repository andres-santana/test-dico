<?php

class Potato_Compressor_Helper_Config extends Mage_Core_Helper_Abstract
{
    const GENERAL_ENABLED          = 'po_compressor/general/enabled';
    //const SAVE_IN_BACKGROUND       = 'po_compressor/general/background';

    const JS_SETTINGS_MERGE        = 'po_compressor/js_settings/merge';
    const JS_SETTINGS_COMPRESSION  = 'po_compressor/js_settings/compression';
    const JS_SETTINGS_GZIP         = 'po_compressor/js_settings/gzip';
    const JS_SETTINGS_SINGLE       = 'po_compressor/js_settings/single';

    const CSS_SETTINGS_MERGE       = 'po_compressor/css_settings/merge';
    const CSS_SETTINGS_COMPRESSION = 'po_compressor/css_settings/compression';
    const CSS_SETTINGS_GZIP        = 'po_compressor/css_settings/gzip';
    const CSS_SETTINGS_SINGLE      = 'po_compressor/css_settings/single';

    public function isEnabled($storeId = null)
    {
        return Mage::getStoreConfig(self::GENERAL_ENABLED, $storeId);
    }

    public function saveInBackground($storeId = null)
    {
        return Mage::getStoreConfig(self::SAVE_IN_BACKGROUND, $storeId);
    }

    public function canJsMerge($storeId = null)
    {
        return Mage::getStoreConfig(self::JS_SETTINGS_MERGE, $storeId);
    }

    public function canJsCompression($storeId = null)
    {
        return Mage::getStoreConfig(self::JS_SETTINGS_COMPRESSION, $storeId);
    }

    public function canJsGzip($storeId = null)
    {
        return Mage::getStoreConfig(self::JS_SETTINGS_GZIP, $storeId);
    }

    public function canCssMerge($storeId = null)
    {
        return Mage::getStoreConfig(self::CSS_SETTINGS_MERGE, $storeId);
    }

    public function canCssCompression($storeId = null)
    {
        return Mage::getStoreConfig(self::CSS_SETTINGS_COMPRESSION, $storeId);
    }

    public function canCssGzip($storeId = null)
    {
        return Mage::getStoreConfig(self::CSS_SETTINGS_GZIP, $storeId);
    }

    public function inSingleCssFile($storeId = null)
    {
        return Mage::getStoreConfig(self::CSS_SETTINGS_SINGLE, $storeId);
    }

    public function inSingleJsFile($storeId = null)
    {
        return Mage::getStoreConfig(self::JS_SETTINGS_SINGLE, $storeId);
    }
}
