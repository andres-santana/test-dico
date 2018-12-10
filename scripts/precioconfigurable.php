<?php

/***********************
 * Actualizar precios de productos configurables, donde se muestra la diferencia de precios. 
 * Debemos subir un archivo xls con el formato adecuado y seleccionar la tienda a donde se aplicará el cambio.
 ***********************/
$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
require_once 'reader.php';
require_once 'mysqlconnect.php';
umask(0);
Mage::app();

date_default_timezone_set('UTC-5');
$date = date(YmdHis);
$target_dir = "./uploads/";
$target_file = $target_dir . $date."_". basename($_FILES["xlsfile"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$website_id = $_POST["website"];
$value_index_array = array('42'=>'King','43'=>'Matrimonial','44'=>'Individual','74'=>'Queen');
$value_index = null;
// Verificamos que el archivo es un archivo de excel 
if(isset($_POST["submit"])) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
    $check = finfo_file($finfo, $_FILES["xlsfile"]["tmp_name"]);
    if($check == "application/vnd.ms-excel" || $check == "application/vnd.ms-office") {
        $uploadOk = 1;
        if (move_uploaded_file($_FILES["xlsfile"]["tmp_name"], $target_file)) {
        	// Una vez guardado, podemos trabajar con el
        	$data = new Spreadsheet_Excel_Reader();
			$data->read($target_file);
			// 
			//echo ":3:";
			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				
					$sku[$i] = $data->sheets[0]['cells'][$i][1];
					$size[$i] = $data->sheets[0]['cells'][$i][2];
					$price[$i] = $data->sheets[0]['cells'][$i][3];

					if ($price[$i]!="" && $price[$i]!=0) {
						foreach ($value_index_array as $key => $value) {
							if($size[$i] == $value){
								$value_index = $key;
							}
						}
						// Realizamos las consultas y ejecutamos el update para cada renglón.
						$product_id_query = "SELECT entity_id FROM catalog_product_entity WHERE sku ='".$sku[$i]."'"; 
						$product_id_result = mysqli_query($conn, $product_id_query);
						$product_id = mysqli_fetch_row($product_id_result);
						if ($product_id){
							
							$product_super_attribute_id_query = "SELECT product_super_attribute_id FROM catalog_product_super_attribute WHERE product_id=".$product_id[0];
							$product_super_attribute_id_result = mysqli_query($conn, $product_super_attribute_id_query);
							$product_super_attribute_id = mysqli_fetch_row($product_super_attribute_id_result);
			
							$product_query_update_select_query = "SELECT website_id FROM catalog_product_super_attribute_pricing  WHERE product_super_attribute_id = '".$product_super_attribute_id[0]."' AND website_id='".$website_id."' AND value_index='".$value_index."'";
							$product_query_update_select_result = mysqli_query($conn, $product_query_update_select_query);
							$product_query_update_select = mysqli_fetch_row($product_query_update_select_result);
			
							if ($product_query_update_select[0]!="") {
								$query_update = "UPDATE catalog_product_super_attribute_pricing SET pricing_value ='".$price[$i]."' WHERE product_super_attribute_id = '".$product_super_attribute_id[0]."' AND website_id='".$website_id."' AND value_index='".$value_index."'";

								if ($conn->query($query_update) === TRUE) {
									echo "SKU: ".$sku[$i]." - Tama&ntilde;o ".$size[$i]." --> <span style='color:#088A29'> Guardado Satisfactoriamente. (UPDATE)</span>";
									echo "<br>";
								}
								else{
									echo "SKU: ".$sku[$i]." - Tama&ntilde;o ".$size[$i]." --> <span style='color:#e42a18'>Error al guardar. Falla en el update</span>";
									echo "<br>";
								}
							}
							else{
								$query_insert = "INSERT INTO catalog_product_super_attribute_pricing(product_super_attribute_id,value_index, website_id, pricing_value) VALUES('".$product_super_attribute_id[0]."','".$value_index."','".$website_id."','".$price[$i]."')";
								if ($conn->query($query_insert)===TRUE) {
									echo "SKU: ".$sku[$i]." - Tama&ntilde;o ".$size[$i]." --> <span style='color:#088A29'>Guardado Satisfactoriamente. (INSERT)</span>";
									echo "<br>";
								}
								else{
									echo "SKU: ".$sku[$i]." - Tama&ntilde;o ".$size[$i]." --> <span style='color:#e42a18'>Error al guardar. Falla en el insert</span>";
									echo "<br>";
								}
							}
								mysqli_free_result($product_id_result);
								mysqli_free_result($product_super_attribute_id_result);
								mysqli_free_result($product_query_update_select_result);
						}
						else{
							echo "SKU: ".$sku[$i]." - Tama&ntilde;o ".$size[$i]." --> <span style='color:#e42a18'>Error al guardar. No existe SKU</span>";
							echo "<br>";
						}	
					}				
			}

    	} else {
        	echo "Lo sentimos, hubo un error al guardar tu archivo, verifica que tenga el formato y extensión correcta y vuelve a intentarlo.";
    	}
    } else {
        echo "El archivo no tiene el formato o extensión correcta, favor de verificar y volver a intentar.";
        $uploadOk = 0;
    }
}
mysqli_close($conn);
fclose($target_file);
if(!unlink($target_file)){
	echo "No se pudo eliminar el archivo";
}
?> 