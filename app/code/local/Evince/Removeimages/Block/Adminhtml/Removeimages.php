<?php
class Evince_Removeimages_Block_Adminhtml_Removeimages extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_removeimages';
    $this->_blockGroup = 'removeimages';
    $this->_headerText = Mage::helper('removeimages')->__('Items Manager. These files are not in database.');
   $this->_addButtonLabel = Mage::helper('removeimages')->__('Refresh');
    parent::__construct();
	
  }
}