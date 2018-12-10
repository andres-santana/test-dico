<?php

class Int_Advancedpermissions_Model_Advancedpermissions extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advancedpermissions/advancedpermissions');
    }
}