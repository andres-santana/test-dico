<?php

class Dico_AjaxCart_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','ajaxcart',array('template' => 'ajaxcart/view.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
	}
}