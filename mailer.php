<html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cotización enviada</title>
<meta name="description" content="Muebles Dico, Muebleria lider en Mexico. En Muebles DICO, encontraras el encanto en sus diferentes lineas de muebles,Salas, Recamaras, Comedores, Cocinas, Colchones, Antecomedores, Sofa Cama, Libreros, Cantinas, Centros de Entretenimiento, Mesas Ocasionales. Muebles con variedad de estilos Contemporaneo, Clasico y Rustico." />
<meta name="keywords" content="Muebles, muebles dico, muebles en df, muebles en Tijuana, muebles en Guadalajara, muebles en Guanajuato, muebles en león,  muebles en Celaya, muebles en san luis potosí, muebles en sonora, muebles en puebla, muebles en Veracruz, muebles en Cuernavaca, muebles en Morelia, muebles en baja california, muebles en cancun, muebles en tabasco, muebles en merida, muebles en yucatan, muebles en hidalgo, muebles en queretaro, muebles en michoacan, muebles en morelos, muebles en quintana roo, muebles en Aguascalientes, muebles en Campeche, muebleria en df, muebleria en Tijuana, muebleria en Guadalajara, muebleria en Guanajuato, muebleria en león,  muebleria en Celaya, muebleria en san luis potosí, muebleria en sonora, muebleria en puebla, muebleria en Veracruz, muebleria en Cuernavaca, muebleria en Morelia, muebleria en baja california, muebleria en cancun, muebleria en tabasco, muebleria en merida, muebleria en yucatan, muebleria en hidalgo, muebleria en queretaro, muebleria en michoacan, muebleria en morelos, muebleria en quintana roo, muebleria en Aguascalientes, muebleria en Campeche,dico, mueblerías, muebleria, mueblería en el df, muebleria en df, muebleria en Guadalajara, muebleria en Jalisco,comedores, salas, cocinas, recamaras, colchones, amueblado, camas, diconomia, accesorios, decoración, equipamiento, casa, hogar, cantinas, tapicería, antecomedores, libreros, clásico, rustico, contemporáneo, tapicería" />
<meta name="robots" content="INDEX,FOLLOW" />
<link rel="icon" href="/media/favicon/default/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="media/favicon/default/favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="/skin/frontend/shopper/dico/apple-touch-icon.png" />
<link rel="apple-touch-icon" sizes="72x72" href="/skin/frontend/shopper/dico/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="114x114" href="/skin/frontend/shopper/dico/apple-touch-icon-114x114.png" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/shopper/dico/css/cotizacion.css" media="all" />
</head>
<body>

<?php

  require "./includes/class.phpmailer.php";

  $email = $_GET['email'];
  $texto2 = $_GET['texto'];
  $texto = utf8_decode($texto2);
  $nombre =$_GET['nombre'];
  $storeid =$_GET['store'];
  $cp =$_GET['cp'];

if ($email!="") {

      echo "<div id='cuerpoform'>";

      $cuerpo = '<div>'.$nombre.' te acaba de mandar una solicitud de cotización</div><div>Con el mensaje:</div><div>'.$texto.'</div><p></p><p></p><div>Contestar al Email:  '.$email.'<p>Código Postal: '.$cp.'</p></div>';
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true;
      $mail->Host = "mail.dico.com.mx";
      $mail->Port = 225; 
      $mail->Username = "infoweb@dico.com.mx";
      $mail->Password = "GDM3x1c0D1c0**";
      $mail->SetFrom('infodicocentro@dico.com.mx',"Muebles Dico Centro");
      $mail->Timeout=10;
      if($storeid == "https://dico.com.mx/df/"):
          $mail->AddAddress("infodicocentro@dico.com.mx");
          $mail->Subject = utf8_decode("Cotización portal Dico Centro");
      elseif ($storeid == "https://dico.com.mx/oriente/"):
          $mail->AddAddress("infodicooriente@dico.com.mx");
          $mail->Subject =  utf8_decode("Cotización portal Dico Oriente");
      elseif ($storeid == "https://dico.com.mx/suroeste/"):
          $mail->AddAddress("infodicosuroeste@dico.com.mx");
          $mail->Subject =  utf8_decode("Cotización portal Dico Suroeste");
      endif;
      $mail->IsHTML(true);
      $mail->MsgHTML($cuerpo);

      if($mail->Send()){
          echo '
          <div class="grid_12 cotizacion-enviada">
          <div class="grid_4"><div class="page-title"><h1 class="titulo">Tu solicitud fue enviada correctamente</h1></div>
          <div class="grid_8"></div>
          <div class="grid_4"><h3>En breve recibirás una respuesta a tu solicitud al correo que registraste. Mantente al pendiente</h3></div>
          <div class="grid_8"></div>
          <div class="grid_4"><ul>
          <li><h3><a href="/">Regresar a la tienda</a> <span class="separator">|</span> <a href="/customer/account/">Mi Cuenta</a></h3></li></ul></div>
          <div class="grid_4"></div>
          </div></div>';        
      }  
      else{
          echo "Problemas enviando correo electrónico a ".$valor;
          echo "<br/>".$mail->ErrorInfo;    
      }
    }

else{
    echo "No hay datos que mostrar";
  }
?>
  
</body>
</html>