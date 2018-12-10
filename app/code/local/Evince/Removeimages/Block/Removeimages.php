<?php
class Evince_Removeimages_Block_Removeimages extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getRemoveimages()     
     { 
        if (!$this->hasData('removeimages')) {
            $this->setData('removeimages', Mage::registry('removeimages'));
        }
        return $this->getData('removeimages');
        
    }
}