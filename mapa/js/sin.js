var sucursales22 = new Array();
    sucursales22[0] = "Culiacan - Bolerama";
	sucursales22[1] = "Culiacan - San Miguel";
	sucursales22[2] = "Culiacan - Aeropuerto";
	sucursales22[3] = "Mochis - Paseo Mochis";
	sucursales22[4] = "Mochis - Varsovia";
	sucursales22[5] = "Guasave - Palaza Ley";
	sucursales22[6] = "Mazatlan - Galerias";
	sucursales22[7] = "Guamuchil - Boulevard";
	sucursales22[8] = "Mazatlan - Galerias Liverpool";
	
var posx22 = new Array();
	posx22[0] = "24.816751";
	posx22[1] = "24.787046";
	posx22[2] = "24.776156";
	posx22[3] = "25.782039";
	posx22[4] = "25.785285";
	posx22[5] = "25.580382";
	posx22[6] = "23.237876";
	posx22[7] = "25.456329";
	posx22[8] = "23.297188";
	

var posy22 = new Array();
    posy22[0] = "-107.402676";
	posy22[1] = "-107.393914";
	posy22[2] = "-107.445238";
	posy22[3] = "-109.004717";
	posy22[4] = "-108.99785";
	posy22[5] = "-108.46261";
	posy22[6] = "-106.438232";
	posy22[7] = "-108.074512";
	posy22[8] = "-106.461029";
	

var direccion22 = new Array();
    direccion22[0] = "Boulevard Enrique Sanchez Alonso #1515, Col. Desarrollo Urbano Tres Ríos, Culiacán, Sinaloa, CP. 80020";
	direccion22[1] = "Plaza Galerias San Miguel LOCAL #3000, Av. Alvaro Obregon #1880 Col. Colinas de San Miguel C.P. 80228 Local #3000 Culiacán, Sinaloa";
	direccion22[2] = "Calzada Aeropuerto No. 5769, Col. Las Flores, C.P. 80104, Culiacán, Sinaloa  Frente a Plaza Humaya";
	direccion22[3] = "Blvd. Centenario No.805 Col. Sector Centro Cp.81271 Los Mochis, Sinaloa";
	direccion22[4] = "Gabriel Leyva No. 700 Entre Callejon Varsovia y Cuauhtémoc, Col. Centro, C.P. 81200, Los Mochis, Sinaloa";
	direccion22[5] = "Centro Comercial Ley Las Fuentes Local F-3, Carretera Internacional Culiacán-Los Mochis Y Blvd. Colosio, Col. Revolución Mexicana, Guasave, Sinaloa C.P. 81016";
	direccion22[6] = "Av. Reforma 314-A, Col. Fraccionamiento Flamingos C.P. 82149. Mazatlan, Sinaloa.  FRENTE A LA GRAN PLAZA";
	direccion22[7] = "Blvd. Francisco Labastida Ochoa S/N Esq. Venustiano Carranza, Colonia Centro, C.P. 81400. Salvador Alvarado Guamuchil, Sinaloa";
	direccion22[8] = "Av. La Marina #6204 Plaza Galerías Mazatlán Local 142 Col. Desarrollo Marina Mazatlán Mazatlán, Sinaloa C.P. 82103";
	
    
var telefonos22 = new Array();
    telefonos22[0] = "6677126668 &oacute;	6677129625";
	telefonos22[1] = "6671460630";
	telefonos22[2] = " ";
	telefonos22[3] = "6688123076";
	telefonos22[4] = "6688186177";
	telefonos22[5] = "6877211936";
	telefonos22[6] = "6691121226";
	telefonos22[7] = "6737320182";
	telefonos22[8] = "6696880739 &oacute; 6696880766";
		

var indice=0;
var map;
var i=0;

function initializeSin() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(24.7541857,-107.4199703), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx22[number-1],posy22[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales22[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion22[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos22[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx22.length; i++) {
	    var point = new GLatLng(posx22[i],posy22[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover22(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx22[number-1],posy22[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales22[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion22[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos22[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx22[number-1],posy22[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }