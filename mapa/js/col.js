var sucursales1 = new Array();
    sucursales1[0] = "Colima - Zentralia";


var posx1 = new Array();
    posx1[0] = "19.267034";

var posy1 = new Array();
    posy1[0] = "-103.698264";

var direccion1 = new Array();
    direccion1[0] = "Centro Comercial Zentralia 3er Anillo Periferico #301 Esq. Ignacio Sandoval Col. Valle Dorado C.P. 28018 Local 159. Colima. Colima";
  
var telefonos1 = new Array();
    telefonos1[0] = "3123237768 &oacute; 3123234574";

var indice=0;
var map;
var i=0;

function initializeCol() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.243736,-103.73291), 13);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx1[number-1],posy1[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales1[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion1[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos1[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx1.length; i++) {
	    var point = new GLatLng(posx1[i],posy1[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover1(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.243736,-103.73291), 13);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx1[number-1],posy1[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales1[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion1[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos1[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx1[number-1],posy1[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }