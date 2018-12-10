<?php if (($fichero = fopen("sureste.csv", "r")) !== FALSE) {
	$datos = fgetcsv($fichero, 0);
	$datos_tmp = array();
    $n=0;
    while (($datos = fgetcsv($fichero, 0)) !== FALSE) {
        // Procesar los datos.
        // En $datos[0] está el valor del primer campo,
        // en $datos[1] está el valor del segundo campo, etc...
        //echo strlen($datos[2]);
        //echo "<br>";
        
        $datos_tmp[$n][0] = $datos[0];
        $datos_tmp[$n][1] = $datos[1];
        $datos_tmp[$n][2] = $datos[2];
        $datos_tmp[$n][3] = 0;
        $datos_tmp[$n][4] = $datos[4];
       	$n++;
       	if ($n>0 && strlen($datos[2])==4) {
        	$datos_tmp[$n-1][2] = "0".$datos[2];
        	echo $datos_tmp[$n-1][2];
        	echo "<br>";
        	//$datos_tmp = $temp;
        }
    }

    //echo "datos:".$datos_tmp[2];
    //print_r($datos_tmp);

    $fp = fopen('sureste2.csv', 'w');
    echo "Salida:<br>";
        foreach ($datos_tmp as $campos) {
        	echo $campos[2];
        	echo "<br>";
        	fputcsv($fp, $campos);
        }
    fclose($fp);
}
fclose($fichero);
