<?php
$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
require_once '../lib/rc4crypt.php';
umask(0);
Mage::app();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fecha = strip_tags(trim($_POST["fecha"]));
        $referencia = trim($_POST["referencia"]);
     
        if ( empty($fecha) OR empty($referencia)) {
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        if ( !empty($fecha) OR !empty($referencia)) {

            function getStringBetween($str,$from,$to){
				$sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
				return substr($sub,0,strpos($sub,$to));
			}

			$session_reference  =  Mage::getSingleton('core/session')->getWebpaySuccess();


			$string  = array('filedata' => " " );
			$date = $fecha;
			$reference = $referencia;
			$rc4crypt = new rc4();
			$user = "7290CSUA0";
			$pass_string = "7290CSUA0";
			$pass = $rc4crypt->StringToHexString($rc4crypt->Salaa($pass_string,'RepGEmPgs'));       

			try {
				//$client = new SoapClient("https://certificacion.mitec.com.mx/pgs/WebPay ");
				//$client = new SoapClient("https://dev.mitec.com.mx/pgs/services/xmltransacciones?wsdl");
				$client = new SoapClient("https://ssl.e-pago.com.mx/pgs/services/xmltransacciones?wsdl");
				//$string['filedata'] = array("in0" => $user, "in1" => $pass,"in2" => "B919", "in3" => $date, "in4" => "002", "in5" => $reference);
				$string['filedata'] = array("in0" => $user, "in1" => $pass,"in2" => "7290", "in3" => $date, "in4" => "0101", "in5" => $reference);
				$result2 = $client->transacciones($string['filedata']);
				$result = $result2->out;
				//print_r($result);
				$_nombre = getStringBetween($result,"<cc_nombre>","</cc_nombre");
				$_importe = getStringBetween($result,"<nu_importe>","</nu_importe");
				$_num_tarjeta = getStringBetween($result,"<cc_num>","</cc_num");
				$_tipo_tarjeta = getStringBetween($result,"<cc_tp>","</cc_tp");
				$_response = getStringBetween($result,"<nb_response>","</nb_response");
				$_banco = getStringBetween($result,"<fh_bank>","</fh_bank");
				$_tipo_operacion = getStringBetween($result,"<tp_operacion>","</tp_operacion");
				$_tipo_moneda = getStringBetween($result,"<nb_currency>","</nb_currency");
				$_fecha = getStringBetween($result,"<fh_registro>","</fh_registro");
				$_nu_operacion = getStringBetween($result,"<nu_operaion>","</nu_operaion");
				$_nu_auth = getStringBetween($result,"<nu_auth>","</nu_auth");
				$_cd_instrumento = getStringBetween($result,"<cd_instrumento>","</cd_instrumento");

				
				if($_cd_instrumento):
				echo "Nombre: " . $_nombre;
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
				endif;
				//echo "<br>Banco: " . $_tipo_tarjeta;
				//echo "<br>Importe: " . $_importe;
				//echo "<br>Fecha y hora: " . $_fecha;
				//echo "<br>";
				/*if($_response):
					echo $_response;
				endif;*/
			} catch(SoapFault $e){
			var_dump($e);
			}        
		?>
<?php
        } else {
            echo "Oops! Something went wrong and we couldn't send your message.";
        } 

    } else {
        echo "There was a problem with your submission, please try again.";
    }

?>