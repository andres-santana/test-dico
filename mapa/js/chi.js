var sucursales8 = new Array();
    sucursales8[0] = "Comitan - Plaza las Flores";
    sucursales8[1] = "San Cristobal - Barrio de Fatima";
    sucursales8[2] = "Tuxtla II - El Retiro"; 
   sucursales8[3] = "Tapachula - Galerias";
    sucursales8[4] = "Tuxtla - Centro";
    sucursales8[5] = "Tuxtla Gutierrez III";


var posx8 = new Array();
    posx8[0] = "16.216199";
    posx8[1] = "16.732094";
    posx8[2] = "16.747663";
    posx8[3] = "14.875432";
    posx8[4] = "16.759632";
    posx8[5] = "16.755671";


var posy8 = new Array();
    posy8[0] = "-92.11549";
    posy8[1] = "-92.653635";
    posy8[2] = "-93.085028"; 
    posy8[3] = "-92.283095"; 
    posy8[4] = "-93.123958";
    posy8[5] = "-93.149696";

var direccion8 = new Array();
    direccion8[0] = "Blvd. De las Federaciones Km. 1260.5 # 4021 Centro comercial PLAZA LAS FLORES Locales 46.47 y 48 Col. Chichima Acapetahua Comitán de Domínguez. Chiapas C.P. 30093";
    direccion8[1] = "Calzada México no. 1 barrio de Fatima San Cristobal de las Casas. Chiapas c.p. 29264";
    direccion8[2] = "Lote N. 9, Manzana Tres, del Fraccionamiento El Retiro, Boulevard Angel Albino Corzo 1431, Colonia El Retiro, Tuxtla Guti�rrez, Chiapas, C.P. 29040";
    direccion8[3] = "Carretera Tapachula Puerto Madero Km. 2.5 Galerias Tapachula. Subancla No. 7 Col. Las Hortensias  C.P. 30797  Tapachula Chis.";
    direccion8[4] = "5a Avenida Norte y esq. 10 Poniente Norte # 1051 Col. Centro Tuxtla Guti?rrez. Chis. CP 29000.";
    direccion8[5] = "Blvd. Belisario Dom&iacutenguez No. 1682, Col. Jardines de Tuxtla, CP 29020 Tuxtla Guti&eacuterrez, Chiapas.";

  
var telefonos8 = new Array();
    telefonos8[0] = "9631089065 &oacute; 9631089054";
    telefonos8[1] = "9676317567 &oacute; 9676747887";
    telefonos8[2] = "9611213474 &oacute; 9616042985"; 
    telefonos8[3] = "9626284449 &oacute; 9626284450";
    telefonos8[4] = "9616110885 &oacute; 9616134762";
    telefonos8[5] = "9616152115 &oacute; 9616152330";


var indice=0;
var map;
var i=0;

function initializeChi() {

  if (GBrowserIsCompatible()) {

    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(16.351768,-92.757568), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx[number-1],posy8[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales8[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion8[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos8[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx8.length; i++) {
	    var point = new GLatLng(posx8[i],posy8[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover8(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(16.351768,-92.757568), 8);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx8[number-1],posy8[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales8[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion8[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos8[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx8[number-1],posy8[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }