<?php

class Potato_Compressor_Model_Design_Package extends Mage_Core_Model_Design_Package
{
    /**
     * Prepare url for css replacement
     *
     * @param string $uri
     * @return string
     */
    protected function _prepareUrl($uri)
    {
        $uri = parent::_prepareUrl($uri);
        $uri = str_replace('http:','', $uri);
        $uri = str_replace('https:','', $uri);
        return $uri;
    }
}