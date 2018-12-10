var sucursales13 = new Array();
    sucursales13[0] = "Pachuca - Galerias";
    sucursales13[1] = "Pachuca - Gran Patio";
	sucursales13[2] = "Plaza del Mueble Pachuca";
   
    
	

var posx13 = new Array();
    posx13[0] = "20.098647";
    posx13[1] = "20.093313";
	posx13[2] = "20.110032";
   

var posy13 = new Array();
    posy13[0] = "-98.766773";
    posy13[1] = "-98.758963";
	posy13[2] = "-98.753568";
   
	

var direccion13 = new Array();
    direccion13[0] = "Centro Comercial Galeria Pachuca Local 147 zona Plateada Blvd. Felipe Angeles y Blvd. Ramon G. Bonfil Pachuca Hidalgo";
    direccion13[1] = "Centro Comercial Gran Patio Pachuca. Blvd. Luis Donaldo Colosio # 2009. Carretera M?xico Tampico. Ex Hacienda de Coscotitlan Municipio de Pachuca de Soto Hidalgo C.P. 42064";
	direccion13[2] = "Blvd. Felipe Angeles #1610 Col.Santa Julia Pachuca Hgo. C.P.42080";
    
    
		
    
var telefonos13 = new Array();
    telefonos13[0] = "7711330016 &oacute; 7711330017";
    telefonos13[1] = "7717104671 &oacute;	7717104406";
	telefonos13[2] = "7711071553 &oacute;	7717148787";
    

var indice=0;
var map;
var i=0;

function initializeHgo() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(20.4972412,-98.9243422), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx13[number-1],posy13[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales13[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion13[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos13[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx13.length; i++) {
	    var point = new GLatLng(posx13[i],posy13[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover13(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx13[number-1],posy13[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales13[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion13[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos13[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx13[number-1],posy13[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }