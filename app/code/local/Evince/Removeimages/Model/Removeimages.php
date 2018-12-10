<?php

class Evince_Removeimages_Model_Removeimages extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('removeimages/removeimages');
    }
}