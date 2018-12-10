<?php

class Evince_Removeimages_Model_Mysql4_Removeimages extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the removeimages_id refers to the key field in your database table.
        $this->_init('removeimages/removeimages', 'removeimages_id');
    }
	
}