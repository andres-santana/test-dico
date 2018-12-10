var sucursales18 = new Array();
    sucursales18[0] = "Oaxaca - Simbolos Patrios";
	  sucursales18[1] = "Oaxaca - Heroes de Chapultepec";
   

var posx18 = new Array();
	  posx18[0] = "17.036023";
	 posx18[1] = "17.052785";

	


var posy18 = new Array();
    posy18[0] = "-96.715822";
	  posy18[1] = "-96.737805";




var direccion18 = new Array();
    direccion18[0] = "Simbolos Patrios #1405 Col. Ex Hacienda Xoxo Sta. Cruz Xoxocotlan, Oaxaca C.P. 71233";
	  direccion18[1] = "Calzada Héroes de Chapultepec No. 821 Col. Reforma, C.P. 68050 Municipio Oaxaca de Juárez, Oaxaca";
	
    
var telefonos18 = new Array();
    telefonos18[0] = "9515012900 &oacute;	9515012899";
	  telefonos18[1] = "9511326881 &oacute;	9511329154";
	
	

var indice=0;
var map;
var i=0;

function initializeOax() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(17.1577833,-96.2099321), 8);	//Coloca el mapa para que se vea todo el Estado o region 
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
      map.setCenter(new GLatLng(posx18[number-1],posy18[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales18[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion18[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos18[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx18.length; i++) {
	    var point = new GLatLng(posx18[i],posy18[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover18(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx18[number-1],posy18[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales18[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion18[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos18[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx18[number-1],posy18[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }