var sucursales17 = new Array();
    sucursales17[0] = "Tepic - Plaza Alicia";
	  sucursales17[1] = "Tepic - Plaza Forum";
   

var posx17 = new Array();
	  posx17[0] = "	21.493115";
	  posx17[1] = "	21.49239";

	


var posy17 = new Array();
    posy17[0] = "	-104.88243";
	  posy17[1] = "	-104.8665";




var direccion17 = new Array();
    direccion17[0] = "Av. Universidad No. 63170. Fraccionamiento Ciudad del Valle Tepic, Nayarit";
	  direccion17[1] = "Plaza Forum, Boulevard Luis Donaldo Colosio #680,Col. Benito Juarez Oriente, C.P. 63175, Tepic, Nayarit.";
	
    
var telefonos17 = new Array();
    telefonos17[0] = "3111335385";
	  telefonos17[1] = "3311810094";
	
	

var indice=0;
var map;
var i=0;

function initializeNay() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(21.8432765,-104.7403113), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx17[number-1],posy17[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales17[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion17[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos17[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx17.length; i++) {
	    var point = new GLatLng(posx17[i],posy17[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover17(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx17[number-1],posy17[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales17[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion17[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos17[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx17[number-1],posy17[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }