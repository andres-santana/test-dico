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
function codigosPostales(){

if(http.readyState==4){

//document.getElementById('esperar').innerHTML = ";
xmlDoc=http.responseXML;
if(xmlDoc.getElementsByTagName('found')[0].childNodes[0].nodeValue > 0){
document.getElementById("estadoResidencia").innerHTML=xmlDoc.getElementsByTagName('estado')[0].childNodes[0].nodeValue;
document.getElementById("municipioResidencia").innerHTML=xmlDoc.getElementsByTagName('municipio')[0].childNodes[0].nodeValue;
document.getElementById("ciudadResidencia").innerHTML=xmlDoc.getElementsByTagName('ciudad')[0].childNodes[0].nodeValue;
document.getElementById("coloniaCampo").innerHTML = '<select id=\"colonia\" name="colonia" ></select>';

/**
Agregar o quitar colonias
*/
for(m=document.getElementById("colonia").length-1;m>=0;m--){
document.getElementById("colonia").remove(document.getElementById("colonia").options[m].index);
}
if(xmlDoc.getElementsByTagName('subcolonia').length > 1){
for(z=0;z<xmlDoc.getElementsByTagName('subcolonia').length;z++){
coloniaNueva = document.createElement('option');
coloniaNueva.text = xmlDoc.getElementsByTagName('subcolonia')[z].childNodes[0].nodeValue;
try{
document.getElementById("colonia").add(coloniaNueva, null);
}catch(ex){
document.getElementById("colonia").add(coloniaNueva);
}
}
}else{
coloniaNueva = document.createElement('option');
coloniaNueva.text = xmlDoc.getElementsByTagName('subcolonia')[0].childNodes[0].nodeValue;
try{
document.getElementById("colonia").add(coloniaNueva, null);
}catch(ex){
document.getElementById("colonia").add(coloniaNueva);
}

}
}
else{
document.getElementById("cp").value='';
document.getElementById("estadoResidencia").innerHTML='';
document.getElementById("colonia").value='';
document.getElementById("municipioResidencia").innerHTML='';
document.getElementById("ciudadResidencia").innerHTML='';
alert("El Código Postal Ingresado no existe.\r Por favor verificalo e intenta nuevamente.");
document.getElementById("cp").focus();
}

}else{
//document.getElementById('esperar').innerHTML = '<img src="img/ajax-loader.gif">';
}

}
/**************************************************************************************************/
function buscarCodigo(cp){
http.open("GET", 'codigosPostales.php?op=cp&cp='+cp, true);
http.onreadystatechange=codigosPostales;
http.send(null);

}