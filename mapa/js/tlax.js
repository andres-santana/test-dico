var sucursales26 = new Array();
    sucursales26[0] = "Apisaco";
	sucursales26[1] = "Outlet Tlaxcala";
		
var posx26 = new Array();
	posx26[0] = "19.417912";
	posx26[1] = "19.320971";
	

		

var posy26 = new Array();
    posy26[0] = "-98.145902";
	posy26[1] = "-98.208728";
	

var direccion26 = new Array();
    direccion26[0] = "Calle Independencia # 102 A.Col. Centro, C.P. 90300 Apizaco Tlaxcala";
	direccion26[1] = "Boulevard Díaz Varela Numero: 144 Y 144B Col. Industrial Chiautempan C.P. 90802 Santa Ana Chiautempan, Tlaxcala";
	
	
	
var telefonos26 = new Array();
    telefonos26[0] = "2411131874 &oacute;	2414178937";
	 telefonos26[1] = "2466883706 &oacute;	2466883708";
				

var indice=0;
var map;
var i=0;

function initializeTlax() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.4161981,-98.1689874), 10);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx26[number-1],posy26[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion26[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos26[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx26.length; i++) {
	    var point = new GLatLng(posx26[i],posy26[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover26(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx26[number-1],posy26[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales26[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion26[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos26[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx26[number-1],posy26[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }