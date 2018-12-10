var sucursales25 = new Array();
    sucursales25[0] = "Tabasco";
	sucursales25[1] = "Tabasco II";
	sucursales25[2] = "Comalcalco";
  sucursales25[3] = "Villahermosa - Deportivo";
  sucursales25[4] = "Villahermosa - Altabrisa";
  sucursales25[6] = "Lazaro Cardenas"; 
  sucursales25[7] = "Comalcalco Chedraui";
		



var posx25 = new Array();
	posx25[0] = "	18.014467";
	posx25[1] = "	17.995836";
	posx25[2] = "	18.25608611";
  posx25[3] = "17.976083";
  posx25[4] = "17.967745";
posx25[6] = "17.759941";
posx25[7] = "18.266995";

		

var posy25 = new Array();
    posy25[0] = "	-92.917117";
	posy25[1] = "	-92.9497";
	posy25[2] = "	-93.21731111";
  posy25[3] = "-92.946163";
  posy25[4] = "-92.942217";

	posy25[6] = "-92.597137";
posy25[7] = "-93.219609";

		

var direccion25 = new Array();
    direccion25[0] = "Prolongaci&oacute;n Av. Universidad No. 498 Col. El Recreo C.P. 86020 Villahermosa, Tabasco";
	direccion25[1] = "Av paseo USUMACINTA No. 1168 y 1170 Col. Tabasco 2000Â  C.P. 86107, Villahermosa, Tab";
	direccion25[2] = "Adolfo L&oacute;pez Mateos No.360 entre Ignacio L&oacute;pez Ray&oacute;n y Delicias. Col. Centro. CP. 86300; Comalcalco, Tabasco";
  direccion25[3] = "Av. Circuito Deportivo No.102, Col. Atesta De Serra, Villahermosa, Tabasco";
  direccion25[4] = "Perif&eacute;rico Carlos Pellicer c&aacute;mara 505 1er  y 2do nivel (Altos) Col. Plutarco El&iacute;as Calles Villahermosa, Tab. C.P. 86190";
 	direccion25[6] = "AV. Lazaro Cardenas B2 (Frente a Infonavit Deportiva) H. Cardenas. Tabasco C.P. 86553";
direccion25[7] = "Av. Boulevard Adolfo López Mateos Norte, N°102, Local 29,Col. Centro, C.P. 86300 Comalcalco, Tabasco.";
	
    
var telefonos25 = new Array();
    telefonos25[0] = "9999266726 &oacute;	9999275755";
	telefonos25[1] = "9933177611 &oacute; 9933177331";
	telefonos25[2] = "9333177331";
   telefonos25[3] = "9933155183 &oacute; 9933525665";
   telefonos25[4] = "9931860282 &oacute; 9933516988";
   telefonos25[6] = "9373721466 &oacute; 9373725682";
   telefonos25[7] = "9333340405";

			

var indice=0;
var map;
var i=0;

function initializeTab() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(17.94917,-92.5533447), 9);	//Coloca el mapa para que se vea todo el Estado o region
    map.setUIToDefault();
 
    function createMarker(latlng, number) {
      var icon = new GIcon();
      icon.image = "images/dico.png";
      icon.shadow = "images/dico.png";
      icon.iconSize = new GSize(32, 21);
      icon.shadowSize = new GSize(32, 21);
      icon.iconAnchor = new GPoint(5, 15);
      icon.infoWindowAnchor = new GPoint(31, 8);
   	
      var marker = new GMarker(latlng,icon);
      marker.value = number;
      GEvent.addListener(marker,"click", function() {
      map.setCenter(new GLatLng(posx25[number-1],posy25[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales25[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion25[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos25[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx25.length; i++) {
	    var point = new GLatLng(posx25[i],posy25[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover25(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx25[number-1],posy25[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales25[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion25[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos25[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx25[number-1],posy25[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }