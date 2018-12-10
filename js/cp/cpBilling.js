var http=getXmlHttpObject();
function getXmlHttpObject(){
var xmlhttp;
if(!xmlhttp&&typeof XMLHttpRequest!='undefined'){
try{
xmlhttp=new XMLHttpRequest();
}
catch(e){
xmlhttp=false;
}
}
return xmlhttp;
}

/************************************************************************************************/
function codigosPostales2(){

if(http.readyState==4){

	//document.getElementById('esperar').innerHTML = ";
	xmlDoc=http.responseXML;
	
	if(xmlDoc.getElementsByTagName('found')[0].childNodes[0].nodeValue > 0){
		document.getElementById("billing:region").value=xmlDoc.getElementsByTagName('estado')[0].childNodes[0].nodeValue;
		document.getElementById("billing:city").value=xmlDoc.getElementsByTagName('municipio')[0].childNodes[0].nodeValue;
		document.getElementById("billing:suburbfield").innerHTML = '<select id=\"billing:suburb\" name="billing:suburb" onchange="insertValueBilling()" ></select>'; 

/**
Agregar o quitar colonias
*/
		for(m=document.getElementById("billing:suburb").length-1;m>=0;m--){
 			document.getElementById("billing:suburb").remove(document.getElementById("billing:suburb").options[m].index); 
		}

		if(xmlDoc.getElementsByTagName('subcolonia').length > 1){
			coloniaNueva0 = document.createElement('option');
			coloniaNueva0.text = "Seleccione una opción";
			coloniaNueva0.disabled = true;
			coloniaNueva0.selected = true;

			document.getElementById("billing:suburb").add(coloniaNueva0, null);

			for(z=0;z<xmlDoc.getElementsByTagName('subcolonia').length;z++){
				coloniaNueva = document.createElement('option');
				coloniaNueva.text = xmlDoc.getElementsByTagName('subcolonia')[z].childNodes[0].nodeValue;
				
				try{
					document.getElementById("billing:suburb").add(coloniaNueva, null); 
				}
				catch(ex){
				document.getElementById("billing:suburb").add(coloniaNueva); 
				}
			}
		}

		else{
			coloniaNueva0 = document.createElement('option');
			coloniaNueva0.text = "Seleccione una opción";
			coloniaNueva0.disabled = true;
			coloniaNueva0.selected = true;

			document.getElementById("billing:suburb").add(coloniaNueva0, null);
			coloniaNueva = document.createElement('option');
			coloniaNueva.text = xmlDoc.getElementsByTagName('subcolonia')[0].childNodes[0].nodeValue;
			try{
				document.getElementById("billing:suburb").add(coloniaNueva, null); 
			}
			catch(ex){
				document.getElementById("billing:suburb").add(coloniaNueva);
			}
		}
	}

	else{
		document.getElementById("billing:postcode").value='';
		document.getElementById("billing:region").innerHTML='';
		document.getElementById("billing:suburb").value=''; 
		document.getElementById("billing:city").innerHTML='';
		alert("El Código Postal Ingresado no existe.\r Por favor verificalo e intenta nuevamente.");
		document.getElementById("billing:postcode").focus();
	}

}
else{
//document.getElementById('esperar').innerHTML = '<img src="img/ajax-loader.gif">';
}

}
/**************************************************************************************************/
function buscarCodigo2(cp){
	http.open("GET", '/cp/codigosPostales.php?op=cp&cp='+cp, true);
	http.onreadystatechange=codigosPostales2;
	http.send(null);
}

function insertValueBilling(){
	document.getElementById("billing:street").value = document.getElementById("billing:suburb").value;
}

function codigosPostales(){

if(http.readyState==4){

	//document.getElementById('esperar').innerHTML = ";
	xmlDoc=http.responseXML;
	
	if(xmlDoc.getElementsByTagName('found')[0].childNodes[0].nodeValue > 0){
		document.getElementById("shipping:region").value=xmlDoc.getElementsByTagName('estado')[0].childNodes[0].nodeValue;
		document.getElementById("shipping:city").value=xmlDoc.getElementsByTagName('municipio')[0].childNodes[0].nodeValue;
		document.getElementById("shipping:suburbfield").innerHTML = '<select id=\"shipping:suburb\" name="shipping:suburb" onchange="insertValueShipping()" ></select>'; 

/**
Agregar o quitar colonias
*/
		for(m=document.getElementById("shipping:suburb").length-1;m>=0;m--){
 			document.getElementById("shipping:suburb").remove(document.getElementById("shipping:suburb").options[m].index); 
		}

		if(xmlDoc.getElementsByTagName('subcolonia').length > 1){
			coloniaNueva0 = document.createElement('option');
			coloniaNueva0.text = "Seleccione una opción";
			coloniaNueva0.disabled = true;
			coloniaNueva0.selected = true;

			document.getElementById("shipping:suburb").add(coloniaNueva0, null);

			for(z=0;z<xmlDoc.getElementsByTagName('subcolonia').length;z++){
				coloniaNueva = document.createElement('option');
				coloniaNueva.text = xmlDoc.getElementsByTagName('subcolonia')[z].childNodes[0].nodeValue;
				
				try{
					document.getElementById("shipping:suburb").add(coloniaNueva, null); 
				}
				catch(ex){
				document.getElementById("shipping:suburb").add(coloniaNueva); 
				}
			}
		}

		else{
			coloniaNueva0 = document.createElement('option');
			coloniaNueva0.text = "Seleccione una opción";
			coloniaNueva0.disabled = true;
			coloniaNueva0.selected = true;

			document.getElementById("shipping:suburb").add(coloniaNueva0, null);
			coloniaNueva = document.createElement('option');
			coloniaNueva.text = xmlDoc.getElementsByTagName('subcolonia')[0].childNodes[0].nodeValue;
			try{
				document.getElementById("shipping:suburb").add(coloniaNueva, null); 
			}
			catch(ex){
				document.getElementById("shipping:suburb").add(coloniaNueva);
			}
		}
	}

	else{
		document.getElementById("shipping:postcode").value='';
		document.getElementById("shipping:region").innerHTML='';
		document.getElementById("shipping:suburb").value=''; 
		document.getElementById("shipping:city").innerHTML='';
		alert("El Código Postal Ingresado no existe.\r Por favor verificalo e intenta nuevamente.");
		document.getElementById("shipping:postcode").focus();
	}

}
else{
//document.getElementById('esperar').innerHTML = '<img src="img/ajax-loader.gif">';
}

}
/**************************************************************************************************/
function buscarCodigo(cp){
	http.open("GET", '/cp/codigosPostales.php?op=cp&cp='+cp, true);
	http.onreadystatechange=codigosPostales;
	http.send(null);
}

function insertValueShipping(){
	document.getElementById("shipping:street").value = document.getElementById("shipping:suburb").value;
}

function check(id) {
    if(id.checked == true){
    	console.log("Checado");
    	document.getElementById("billing:autofill").style.display = "block";
    	var rfcFields = document.getElementsByClassName('rfc-fields');
		for(var i = 0; i < rfcFields.length; i++) { 
  			rfcFields[i].style.display='block';
		}


    }
    else{
    	console.log("No checado");
    	var rfcFields = document.getElementsByClassName('rfc-fields');
		for(var i = 0; i < rfcFields.length; i++) { 
  			rfcFields[i].style.display='none';
		}
    }
}

function autofill(id) {
    if(id.checked == true){
    	var razon_social_1 = document.getElementById("billing:firstname").value;
    	var razon_social_2 = document.getElementById("billing:lastname").value;
    	var colonia = document.getElementById("billing:suburb").value;
    	var municipio = document.getElementById("billing:city").value;
    	var estado = document.getElementById("billing:region").value;
    	var email = document.getElementById("billing:email").value;
    	var telefono = document.getElementById("billing:telephone").value;

    	if (razon_social_1 && razon_social_2) {
    		document.getElementById("billing:razon_social").value = razon_social_1 + " " + razon_social_2;	
    	}
    	if (colonia) {
    		document.getElementById("billing:rfc_colonia").value = colonia;
    	}
    	if (municipio) {
    		document.getElementById("billing:rfc_municipio").value = municipio;
    	}
    	if (estado) {
    		document.getElementById("billing:rfc_estado").value = estado;
    	}
    	if (email) {
    		document.getElementById("billing:rfc_email").value = email;
    	}
    	if (telefono) {
    		document.getElementById("billing:rfc_telefono").value = telefono;
    	}
    }
}
