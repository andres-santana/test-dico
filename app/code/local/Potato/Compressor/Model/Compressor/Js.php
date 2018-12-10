<?php

set_include_path( BP.DS.'lib'.DS.'Minify'. PS . get_include_path());
require_once 'JSMin.php';

class Potato_Compressor_Model_Compressor_Js extends Potato_Compressor_Model_Compressor_Abstract
{
    const FOLDER_NAME            = 'js';
    const FILE_EXTENSION         = 'js';

    const ELEMENT_HTML           = '<script type="text/javascript" src="%s"></script>';
    const ELEMENT_CONDITION_HTML = '<script type="text/javascript" %s src="%s"></script>';

    protected function _compression($content)
    {
        $jsCompressor = new JSMin('');
        return $jsCompressor->minify($content);
    }

    public function canGzip()
    {
        return Mage::helper('po_compressor/config')->canJsGzip($this->_storeId);
    }

    public function canCompression()
    {
        return Mage::helper('po_compressor/config')->canJsCompression($this->_storeId);
    }

    public function inSingleFile()
    {
        return Mage::helper('po_compressor/config')->inSingleJsFile($this->_storeId);
    }

    public function canMerge()
    {
        return Mage::helper('po_compressor/config')->canJsMerge($this->_storeId);
    }

    protected function _getElementHtml()
    {
        return self::ELEMENT_HTML;
    }

    protected function _getElementConditionHtml()
    {
        return self::ELEMENT_CONDITION_HTML;
    }

    protected function _getFileExtension()
    {
        return self::FILE_EXTENSION;
    }

    protected function _getFolderName()
    {
        return self::FOLDER_NAME;
    }

    public function isAllowToCompress($fileName)
    {
        return true;
    }

    protected function _beforeMerge($file)
    {
        return parent::_beforeMerge($file) . ';';
    }
}