var sucursales10 = new Array();
    sucursales10[0] = "Paseo Durango";
	sucursales10[1] = "Durango II";


var posx10 = new Array();
    posx10[0] = "24.035794";
	posx10[1] = "24.019579";

var posy10 = new Array();
    posy10[0] = "-104.650365";
	posy10[1] = "-104.664614";

var direccion10 = new Array();
    direccion10[0] = "Local 15/16A Centro Comercial Paseo Durango. Av. Felipe Pescador # 1401 Col. Esperanza Durango. Durango C.P. 34080";
	direccion10[1] = "Blvd.  Dolores del Rio  No.1222  esq. Domingo Arrieta Col. Centro , C.P. 34139";
  
var telefonos10 = new Array();
    telefonos10[0] = "6181292356 &oacute; 6181292360";
	telefonos10[1] = "6188115741";

var indice=0;
var map;
var i=0;

function initializeDgo() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(24.5947193,-104.8405939), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx10[number-1],posy10[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales10[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion10[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos10[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx10.length; i++) {
	    var point = new GLatLng(posx10[i],posy10[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover10(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.530995,-99.028122), 7);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx10[number-1],posy10[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales10[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion10[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos10[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx10[number-1],posy10[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }