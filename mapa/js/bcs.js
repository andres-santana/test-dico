var sucursales6 = new Array();
    sucursales6[0] = "La Paz";


var posx6 = new Array();
    posx6[0] = "24.117615";

var posy6 = new Array();
    posy6[0] = "-110.336058";

var direccion6 = new Array();
    direccion6[0] = "Blvd. General Agustin Olachea Esq. Daniel Roldan Col. San Antonio el Sacatal C.P 23090 Baja California Sur Plaza Paseo la Paz";
  
var telefonos6 = new Array();
    telefonos6[0] = "6121241766 &oacute; 6121665074";

var indice=0;
var map;
var i=0;

function initializeBcs() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(26.046913,-111.665039), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx6[number-1],posy6[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales6[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion6[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos6[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx6.length; i++) {
	    var point = new GLatLng(posx6[i],posy6[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover6(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(26.046913,-111.665039), 7);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx6[number-1],posy6[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales6[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion6[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos6[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx6[number-1],posy6[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }