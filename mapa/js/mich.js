var sucursales15 = new Array();
    sucursales15[0] = "Morelia - Camelias";
	sucursales15[1] = "La Piedad - Col. México";
    sucursales15[2] = "Uruapan - Magdalena";
	sucursales15[3] = "Zamora - Centro";
	
	sucursales15[4] = "Lazaro Cardenas - Las Americas";
		

var posx15 = new Array();
    posx15[0] = "19.683445";
	  posx15[1] = "20.354425";
	  posx15[2] = "19.40818";
	  posx15[3] = "19.98131";
	  
	  posx15[4] = "19.688147";


var posy15 = new Array();
    posy15[0] = "-101.166658";
	  posy15[1] = "-102.028399";
	  posy15[2] = "-102.054";
	  posy15[3] = "-102.2895";
	  
	  posy15[4] = "-101.157914";



var direccion15 = new Array();
    direccion15[0] = "Av. Camelinas No. 2650, Loc 4, Col. Prado del Campestre, Esq. Lluvia, Morelia, Michoacan";
	  direccion15[1] = "Av. Juan Pablo II No. 901, Col. México, C.P. 59340, La Piedad, Michoacán Entre Calle Michoacán y Av. Malecón del Río";
	  direccion15[2] = "Paseo General Lázaro Cárdenas esq. con Juan N. López, Col. La Magdalena    C.P 60080 Uruapan, Michoacán";
	  direccion15[3] = "AV. Francisco I Madero Sur # 500, esquina con Purepero y La Piedad, Col. Centro, Zamora, Michoacan C.P 59600";
	  
	  direccion15[4] = "Avenida Las Palmas No. 1559 Centro Comercial Plaza las Américas Local SA09 Col. El Palmar C.P. 60950 Lázaro Cárdenas, Michoacán";
			
    
var telefonos15 = new Array();
    telefonos15[0] = "4433145199 &oacute;	443314713";
	  telefonos15[1] = " ";
	  telefonos15[2] = "4525230646";
	  telefonos15[3] = "3511420021";

	  telefonos15[4] = "7535313486 &oacute; 7535313531";

	

var indice=0;
var map;
var i=0;

function initializeMich() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.1535204,-101.9006079),8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx15[number-1],posy15[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales15[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion15[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos15[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx15.length; i++) {
	    var point = new GLatLng(posx15[i],posy15[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover15(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx15[number-1],posy15[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales15[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion15[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos15[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx15[number-1],posy15[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }