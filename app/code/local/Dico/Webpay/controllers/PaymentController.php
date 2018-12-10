<?php

class Dico_Webpay_PaymentController extends Mage_Core_Controller_Front_Action {
	// The redirect action is triggered when someone places an order
	public function redirectAction() {
        
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','webpay',array('template' => 'webpay/redirect.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
	}
	public function ajaxAction() {
        
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','webpay',array('template' => 'webpay/ajax.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
	}

	public function successAction() { 
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','webpay',array('template' => 'webpay/success.phtml'));
		$this->getLayout()->getBlock('content')->append($block); 
        $this->renderLayout();
	}
	
	public function responseAction() {
		if($this->getRequest()->isPost()) {
			 
			$webpay = Mage::getSingleton("webpay/standard");
			$respuesta = $this->getRequest()->getParam('respuesta');
			$string = $webpay->Salaa($webpay->HexStringToString($respuesta),'MEX01017290');
			//$string = $webpay->Salaa($webpay->HexStringToString($respuesta),'MEX002B919');

			function getStringBetween($str,$from,$to)
				{
    				$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    				return substr($sub,0,strpos($sub,$to));
				}

			$_response = getStringBetween($string,"<response>","</response>");
			$_referencia = getStringBetween($string,"<referencia>","</referencia>");
			$_error_string = getStringBetween($string,"<error>","</error>");
			$_autorizacion = getStringBetween($string,"<aut>","</aut>");
			$_nombre_tarjeta = getStringBetween($string,"<ccName>","</ccName>");
			$_digitos_tarjeta = getStringBetween($string,"<ccNum>","</ccNum>");
			$_total = getStringBetween($string,"<amount>","</amount>");
			$_tipo_tarjeta_string = getStringBetween($string,"<type>","</type>");
			$_tipo_tarjeta = $webpay->StringToHexString($webpay->Salaa($_tipo_tarjeta_string,'MEX01017290'));
			//$_tipo_tarjeta = $webpay->StringToHexString($webpay->Salaa($_tipo_tarjeta_string,'MEX002B919'));
			$_error = $webpay->StringToHexString($webpay->Salaa($_error_string,'MEX01017290'));
			//$_error = $webpay->StringToHexString($webpay->Salaa($_error_string,'MEX002B919'));

			// Activamos la bandera para cancelar la consulta web 
			Mage::getSingleton('core/session')->setWebpaySuccess($_referencia);
							 

			if($_response =="approved") {
				$order = Mage::getModel('sales/order');
				$order->loadByIncrementId($_referencia);
				$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway ha autorizado el pago.');
				$order->sendNewOrderEmail();
				$order->setEmailSent(true);
				
				$order->save();
			
				Mage::getSingleton('checkout/session')->unsQuoteId();
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', 
					array('_secure'=>true, 
						  'ccname'=>$_nombre_tarjeta, 
						  'ccnum'=>$_digitos_tarjeta,
						  'amount'=>$_total,
						  'type'=>$_tipo_tarjeta,
						  'reference'=>$_referencia,
						  'response'=>$_response,
						  'aut'=>$_autorizacion
						  ));
			}
			elseif ($_response=="denied") {
				$this->cancelAction();
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', 
					array('_secure'=>true, 
						  'ccname'=>$_nombre_tarjeta, 
						  'ccnum'=>$_digitos_tarjeta,
						  'amount'=>$_total,
						  'type'=>$_tipo_tarjeta,
						  'reference'=>$_referencia,
						  'response'=>$_response,
						  'aut'=>$_autorizacion
						  ));
			}

			elseif ($_response=="error") {
				$this->cancelAction();
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', 
					array('_secure'=>true, 
						  'ccname'=>$_nombre_tarjeta, 
						  'ccnum'=>$_digitos_tarjeta,
						  'amount'=>$_total,
						  'type'=>$_tipo_tarjeta,
						  'response'=>$_response,
						  'reference'=>$_referencia,
						  'error'=>$_error,
						  'aut'=>$_autorizacion
						  ));				
			}

			elseif($_response=="unauthenticated") {
				$this->cancelAction();
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', 
					array('_secure'=>true, 
						  'ccname'=>$_nombre_tarjeta, 
						  'ccnum'=>$_digitos_tarjeta,
						  'amount'=>$_total,
						  'type'=>$_tipo_tarjeta,
						  'response'=>$_response,
						  'reference'=>$_referencia,
						  'error'=>$_error,
						  'aut'=>$_autorizacion
						  ));				
			}
		}
		else
			Mage_Core_Controller_Varien_Action::_redirect('');
	}
	
	// The cancel action is triggered when an order is to be cancelled
	public function cancelAction() {
        if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
            if($order->getId()) {
				// Flag the order as 'cancelled' and save it
				$order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
			}
        }
	}
}