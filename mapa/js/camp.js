var sucursales7 = new Array();
    sucursales7[0] = "Campeche - Fundadores";
    sucursales7[1] = "Campeche - Galerias";
    sucursales7[2] = "Cd del Carmen - Plaza Palmiras";
    sucursales7[3] = "Cd del Carmen - Plaza Zentralia";
   

var posx7 = new Array();
    posx7[0] = "19.851937";
    posx7[1] = "19.857043";
    posx7[2] = "18.657183";
    posx7[3] = "18.652574";
   

var posy7 = new Array();
    posy7[0] = "-90.532261";
    posy7[1] = "-90.522239";
    posy7[2] = "-91.791340";
    posy7[3] = "-91.808144";
   

var direccion7 = new Array();
    direccion7[0] = "Lote no. 4. Manzana e-2. Seccion fundadores. Area ah-kimpech. Campeche. Camp c.p. 24014";
    direccion7[1] = "Av. Pedro Sainz de Baranda SN Esq. Av. Darsena. Col. Sector Metropolitano del area AH-KIM-PECH. no.139 CP 24014. Local 240 Planta Alta";
    direccion7[2] = "Av isla de tris 7e   subancla 3 col. Fracc. Palmira cp 24100. Cd carmen. Campeche";
    direccion7[3] = "Av Corregidora #26 Local 56 Col. Aeropuerto, Ciudad del Carmen, Campeche C.P. 24199 ";
   
  
var telefonos7 = new Array();
    telefonos7[0] = "9818168943 &oacute; 9818167686";
    telefonos7[1] = "9816881324 &oacute; 9816881400";
    telefonos7[2] = "9381189687 &oacute; 9381189996";
    telefonos7[3] = "9386885146 &oacute; 9386885110";
   

var indice=0;
var map;
var i=0;

function initializeCamp() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.217803,-90.593262), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx7[number-1],posy7[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales7[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion7[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos7[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx7.length; i++) {
	    var point = new GLatLng(posx7[i],posy7[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover7(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.217803,-90.593262), 8);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx7[number-1],posy7[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales7[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion7[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos7[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx7[number-1],posy7[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }