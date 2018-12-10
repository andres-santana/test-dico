<?php

set_include_path(BP . DS . 'lib' . DS . 'Minify' . PS . get_include_path());
require_once 'Minify' . DS . 'CSS.php';
class Potato_Compressor_Model_Compressor_Css extends Potato_Compressor_Model_Compressor_Abstract
{
    const FOLDER_NAME            = 'css';
    const FILE_EXTENSION         = 'css';

    const ELEMENT_HTML           = '<link rel="stylesheet" type="text/css" href="%s"/>';
    const ELEMENT_CONDITION_HTML = '<link rel="stylesheet" %s type="text/css" href="%s"/>';

    protected function _compression($content)
    {
        $cssCompressor = new Minify_CSS;
        return $cssCompressor->minify($content);
    }

    public function canGzip()
    {
        return Mage::helper('po_compressor/config')->canCssGzip($this->_storeId);
    }

    public function canCompression()
    {
        return Mage::helper('po_compressor/config')->canCssCompression($this->_storeId);
    }

    public function inSingleFile()
    {
        return Mage::helper('po_compressor/config')->inSingleCssFile($this->_storeId);
    }

    public function canMerge()
    {
        return Mage::helper('po_compressor/config')->canCssMerge($this->_storeId);
    }

    protected function _beforeMerge($file)
    {
        $content = file_get_contents($file);
        $content = Mage::getModel('po_compressor/design_package')->beforeMergeCss($file, $content);
        return $content;
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
        if (strpos($fileName, '.min.')) {
            return false;
        }
        return true;
    }
}