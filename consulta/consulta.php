<?php
$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
require_once '../lib/aescrypt.php';
require_once './lib/nusoap.php';
umask(0);
Mage::app();

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fecha = strip_tags(trim($_POST["fecha"]));
        $referencia = trim($_POST["referencia"]);*/
     	$fecha = "06/04/2016";
     	$referencia = 'NOR1500000013';
     	echo "in0";
        // Check that data was sent to the mailer.
        if ( empty($fecha) OR empty($referencia)) {
            // Set a 400 (bad request) response code and exit.
            //http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

       

        // Send the email.
        if ( !empty($fecha) OR !empty($referencia)) {
			echo "in1";
            // Set a 200 (okay) response code.
            //http_response_code(200);
            function getStringBetween($str,$from,$to){
				$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
				return substr($sub,0,strpos($sub,$to));
			}

			
			
			try {
				$session_reference  =  Mage::getSingleton('core/session')->getWebpaySuccess();
			$date = $fecha;
			$reference = $referencia;
			$key = '18F5418A1D287B4A6176F4375A147712';
			$user = "7180COUS0";
			$pass_string = "7180COUS0";   
			$in0 = "9265654625";
			$xml = "<user>".$user."</user><pwd>".$pass_string."</pwd><id_company>7180</id_company><date>".$date."</date><id_branch>0016</id_branch><reference>".$reference."</reference>";
			
			$xmlcifrado = AESEncriptacion::encriptar($xml,$key); 
			$xmlcodificado64 = base64_encode($xmlcifrado);
			$xmlsoap = '<?xml version="1.0" encoding="UTF-8"?>
								<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
								xmlns:wst="http://wstrans.cpagos">
								<soapenv:Header/>
								<soapenv:Body>
								<wst:transacciones>
								<wst:in0>'.$in0.'</wst:in0>
								<wst:in1>'.$xmlcodificado64.'</wst:in1>
								<wst:in2></wst:in2>
								<wst:in3></wst:in3>
								<wst:in4></wst:in4>
								<wst:in5></wst:in5>
								</wst:transacciones>
								</soapenv:Body>
								</soapenv:Envelope>';
			echo "\n in2";
				$client2 = new SoapClient('https://qa3.mitec.com.mx/pgs/services/xmltransacciones?wsdl');
			
				/*$err = $client2->getError();
				echo "\n in3";
				if ($err) {
					echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
				}
				echo "\n in4";*/

				//$client = new SoapClient("https://qa3.mitec.com.mx/pgs/services/xmltransacciones?wsdl", array('encoding'=>'UTF-8'));
				//$client = new nusoap_client("https://qa3.mitec.com.mx/pgs/services/xmltransacciones?wsdl", "wsdl");

				//$client->soap_defencoding = 'UTF-8';

				/*$err = $client->getError();

				if($err){

                echo '<h2>Error</h2>'.$err;

				}*/

				/*$result =$client->transacciones($xmlsoap);
				$result2 = $client->__soapCall('transacciones', array($xmlsoap));
				echo htmlentities(var_dump($result2,true));*/
				/*var_dump($client2->__soapCall('transacciones', $xmlsoap));
				echo "\n in5";*/

				//$string['filedata'] = array("in0" => $in0, "in1" => $xmlcodificado64,"in2" => "", "in3" => "", "in4" => "", "in5" => "");
				//$string['filedata'] = array("in0" => $user, "in1" => $pass,"in2" => "7290", "in3" => $date, "in4" => "0101", "in5" => $reference);
				//$result2 = $client->transacciones($string['filedata']);
				//$result2 = $client->transacciones($xmlsoap);
				/*$result3 = $result->out;
				$result4 = base64_decode($result3);
				$result5 = AESEncriptacion::desencriptar($result4,$key);*/
				/*if($result):
				print_r($result);
				echo "result:".$result;
				echo "\n in6";
				endif;*/
				/*$_nombre = getStringBetween($result,"<cc_nombre>","</cc_nombre");
				$_importe = getStringBetween($result,"<nu_importe>","</nu_importe");
				$_num_tarjeta = getStringBetween($result,"<cc_num>","</cc_num");
				$_tipo_tarjeta = getStringBetween($result,"<cc_tp>","</cc_tp");
				$_response = getStringBetween($resul,"<nb_response>","</nb_response");
				$_banco = getStringBetween($result,"<fh_bank>","</fh_bank");
				$_tipo_operacion = getStringBetween($result,"<tp_operacion>","</tp_operacion");
				$_tipo_moneda = getStringBetween($result,"<nb_currency>","</nb_currency");
				$_fecha = getStringBetween($result,"<fh_registro>","</fh_registro");
				$_nu_operacion = getStringBetween($result,"<nu_operaion>","</nu_operaion");
				$_nu_auth = getStringBetween($result,"<nu_auth>","</nu_auth");
				$_cd_instrumento = getStringBetween($result,"<cd_instrumento>","</cd_instrumento");

				//echo "Nombre: " . $_nombre;
				if($_cd_instrumento):
				echo ":::::::::::::: [ Instrumento >> " . $_cd_instrumento;
				endif;
				if($_tipo_tarjeta):
				echo " ] :::::::::::::: [ Tipo Tarjeta >> " . $_tipo_tarjeta;
				endif;
				if($_num_tarjeta):
				echo " ] :::::::::::::: [ Dígitos Tarjeta >> " . $_num_tarjeta;
				endif;
				if($_nu_auth):
				echo " ] :::::::::::::: [ Autorización >> " . $_nu_auth;
				echo " ] ::::::::::::::";
				endif;*/
				//echo "<br>Banco: " . $_tipo_tarjeta;
				//echo "<br>Importe: " . $_importe;
				//echo "<br>Fecha y hora: " . $_fecha;
				//echo "<br>";

			} catch(SoapFault $e){
			var_dump($e);
			}        

        } else {
            // Set a 500 (internal server error) response code.
            //http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        } 

   /* } else {
        // Not a POST request, set a 403 (forbidden) response code.
        //http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }*/

?>
