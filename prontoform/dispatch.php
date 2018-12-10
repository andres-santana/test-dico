<?php
include('./auth.php');
$directorio = './csv/';
$ficheros  = glob($directorio.'*.csv');

/* Por cada archivo que exista en la carpeta realizar el despacho correspondiente */
foreach ($ficheros as $key => $nombre_archivo) {
	$archivo = fopen($nombre_archivo, "r") or die('No se puede abrir el archivo');
	$content = fread($archivo, filesize($nombre_archivo));
	$nom_archivo = split("/", $nombre_archivo);
  	$archivo_xml = split(".csv", $nom_archivo[2]);

  	$tsvFile = new SplFileObject($nombre_archivo);
  	$tsvFile->setFlags(SplFileObject::READ_CSV);

  	$n = 0;
  	$m = 0;
  	$id_cliente_tmp = "";
  	$articulos = array();
  	$clientes = array();
  	foreach ($tsvFile as $line => $row) {
    	if($line > 0) {
      		$id_cliente = split(" ", $row[7]);
      		if($id_cliente_tmp==$id_cliente[0]){
          		$n++;  
          		$articulos[$n] = $row[6];
          		$clientes[$id_cliente[0]][$n] =  $articulos[$n];
          
      		} 
      		else{
        		$id_cliente_tmp = $id_cliente[0];
        		$n = 0;
        		$m++;
        		$articulos[$n] = $row[6];
        		$clientes[$id_cliente[0]][$n] =  $articulos[$n];
        		$clientes[$id_cliente[0]]['remision'] =  $row[5];
        		$clientes[$id_cliente[0]]['guia'] =  $row[4];
        		$clientes[$id_cliente[0]]['datoscte'] =  $row[7];
        		$clientes[$id_cliente[0]]['ddc'] =  $row[8];
        		$clientes[$id_cliente[0]]['idform'] =  $row[0];
        		$clientes[$id_cliente[0]]['iduser'] =  $row[1];
      		}     
    	}
  	}

  	foreach ($clientes as $idCliente => $productos) {
		$xml_dispatch = '<dispatch><formId>'.$productos["idform"].'</formId><userId>'.$productos["iduser"].'</userId><data><answer label="remision">'.$productos["remision"].'</answer><answer label="guia">'.$productos["guia"].'</answer><answer label="datoscte">'.$productos["datoscte"].'</answer><answer label="ddc">'.$productos["ddc"].'</answer>';
    
    	$x=0;

    	foreach ($productos as $key => $value) {
      		if ($key == "0") {
        		$xml_dispatch .= '<answer label="articulosgral">'.$value;   
      		}
      		elseif ($key!='remision' && $key!='guia' && $key!='datoscte' && $key!='ddc' && $key!='idform' && $key!='iduser') {
        		$x++;
       			$xml_dispatch .= $value;   
      		}
    	}

    	$xml_dispatch .= '</answer></data></dispatch>';
    	$data = utf8_encode($xml_dispatch);
    	$metodo = "POST";
    	$url = "https://api.prontoforms.com/api/1/data/dispatch.xml";
    	$result = CallAPI($metodo,$url,$data);
    	$httpcode = $result[1];
    	$dataId = getStringBetween($result[0],"<dataId>","</dataId>");
    	$referenceNumber = getStringBetween($result[0],"<referencenumber>","</referencenumber>");
    	echo "<script>console.log('httpcode:".$httpcode."')</script>";
    	echo "<script>console.log('dataId:".$dataId."')</script>";
  	}
	
	fclose($archivo);
}
?>