<?php

class Evince_Removeimages_Adminhtml_RemoveimagesController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('removeimages/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   

	public function indexAction() {
		
		$this->_initAction()
			->renderLayout();
	}
	
	public function newAction(){
	
		Mage::helper('removeimages')->compareList();
		$this->_redirect('*/*/');
	}
	
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('removeimages/removeimages');
				$model->load($this->getRequest()->getParam('id'));
				unlink('media/catalog/product'. $model->getFilename());
				$model->setId($this->getRequest()->getParam('id'))->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $removeimagesIds = $this->getRequest()->getParam('removeimages');
        if(!is_array($removeimagesIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
				$model = Mage::getModel('removeimages/removeimages');
                foreach ($removeimagesIds as $removeimagesId) {
					$model->load($removeimagesId);
					unlink('media/catalog/product'. $model->getFilename());
					$model->setId($removeimagesId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($removeimagesIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
}