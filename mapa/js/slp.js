var sucursales23 = new Array();
    sucursales23[0] = "San Luis - Plaza Real";
	sucursales23[1] = "San Luis - Providencia ";
		
var posx23 = new Array();
	posx23[0] = "	22.147543";
	posx23[1] = "	22.13627";

var posy23 = new Array();
    posy23[0] = "	-101.013172";
	posy23[1] = "	-100.9337";

var direccion23 = new Array();
    direccion23[0] = "Plaza Real sub-ancla No. 40, AV. Himno Nacional No. 100 Fracc Virreyes, CP 78240 San Luis Potosi, SLP.";
	direccion23[1] = "Av. Benito Juarez Num. 1535, Fracc. La Providencia, San Luis Ptosi, SLP. CP 78390";
	
    
var telefonos23 = new Array();
    telefonos23[0] = " 4448116694	&oacute; 4448116306";
	telefonos23[1] = " 4445675561	&oacute; 444575560";

			

var indice=0;
var map;
var i=0;

function initializeSlp() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(22.8251348,-100.3165624), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx23[number-1],posy23[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales23[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion23[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos23[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx23.length; i++) {
	    var point = new GLatLng(posx23[i],posy23[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover23(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx23[number-1],posy23[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales23[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion23[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos23[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx23[number-1],posy23[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }