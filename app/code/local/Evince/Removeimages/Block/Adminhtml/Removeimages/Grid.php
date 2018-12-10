<?php

class Evince_Removeimages_Block_Adminhtml_Removeimages_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    
  public function __construct()
  {
      parent::__construct();
      $this->setId('removeimagesGrid');
      $this->setDefaultSort('removeimages_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
	 	  
  }

  
	// **** // trae las imagenes que no estan en base de datos...
  protected function _prepareCollection()
  {
	$collection = Mage::getModel('removeimages/removeimages')->getCollection();
	
	$this->setCollection($collection);

	return parent::_prepareCollection();
	
  }

  protected function _prepareColumns()
  {
    
	    $this->addColumn('filename', array(
          'header'    => Mage::helper('removeimages')->__('Filename'),
          'renderer'     =>'Evince_Removeimages_Block_Adminhtml_Renderer_Image',
          'align'     =>'left',
          'index'     => 'filename'

      ));
		  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('removeimages')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('removeimages')->__('delete'),
                        'url'       => array('base'=> '*/*/delete'),
                       'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
      return parent::_prepareColumns();
  } 

   	
	protected function _prepareMassaction()
    {
	
        $this->setMassactionIdField('removeimages_id');
        $this->getMassactionBlock()->setFormFieldName('removeimages');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('removeimages')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('removeimages')->__('Are you sure?')
        ));
        return $this;
    }
	

}
