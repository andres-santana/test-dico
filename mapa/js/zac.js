var sucursales29 = new Array();
    sucursales29[0] = "Zacatecas - Guadalupe";
  sucursales29[1] = "Zacatecas - Plaza Galerias";
    
var posx29 = new Array();
  posx29[0] = "22.756309";
  posx29[1] = "22.778061";

    
var posy29 = new Array();
    posy29[0] = "-102.532364";
  posy29[1] = "-102.599369";

  
var direccion29 = new Array();
    direccion29[0] = "Blvd. José Lopez Portillo No.58, Col. Arroyo de la Plata, Fte a Nextel Guadalupe Zacatecas";
  direccion29[1] = "Centro Comercial Galerías Zacatecas, Locales: 448, 440, 442. Boulevard del Bote #202 Col. Ciudad Argentum, Zacatecas, Zacatecas C.P. 98040";

      
var telefonos29 = new Array();
    telefonos29[0] = "4924913304";
  telefonos29[1] = "4921563195 &oacute; 4921565464";

var indice=0;
var map;
var i=0;

function initializeZac() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(23.0831271,-102.5352127), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx29[number-1],posy29[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales29[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion29[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos29[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx29.length; i++) {
	    var point = new GLatLng(posx29[i],posy29[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover29(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx29[number-1],posy29[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales29[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion29[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos29[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx29[number-1],posy29[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }