var sucursales24 = new Array();
    sucursales24[0] = "Hermosillo - Plaza Reforma";
	sucursales24[1] = "Hermosillo - Bachoco";
	sucursales24[2] = "Cd. Obregon";
	sucursales24[3] = "Cd. Obregon - El Patio";
	sucursales24[4] = "Guaymas - MM Cinemas";

	sucursales24[6] = "San Luis Rio C. - Av. Hidalgo";
	sucursales24[7] = "Nogales - El Greco";
	
	
var posx24 = new Array();
	posx24[0] = "29.066382";
	posx24[1] = "29.120074";
	posx24[2] = "27.503723";
	posx24[3] = "27.498146";
	posx24[4] = "27.92955";

	posx24[6] = "32.480533";
	posx24[7] = "31.290614";
	
	

var posy24 = new Array();
    posy24[0] = "-110.966034";
	posy24[1] = "-110.950241";
	posy24[2] = "-109.92951";
	posy24[3] = "-109.927998";
	posy24[4] = "-110.9249";

	posy24[6] = "-114.781197";
	posy24[7] = "-110.940340";
	
	

var direccion24 = new Array();
    direccion24[0] = "Av. De La Cultura No.81 Col. Proyecto Rio Sonora Cp.76790 Hermosillo, Sonora";
	direccion24[1] = "Centro Comercial Soriana Bachoco,Blvd. Jose María Morelos y Pavón No. 432 Col. Bachoco, C.P.83148 Hermosillo, Sonora";
	direccion24[2] = "Calle Yaqui No. 1027 Col. Oriente Altos Cp. 85010 Cd. Obregon Sonora";
	direccion24[3] = "Sufragio Efectivo No. 901, Entre Fco. Villanueva y Calle Jalisco, C.P. 22100, Ciudad Obregon, Sonora";
	direccion24[4] = "Plaza Guaymas Local S-A01, Boulevard Luis Encinas s/n y Calzada García Lopez.  Col. Las Quintas C.P. 85448 Guaymas, Sonora";
	
	direccion24[6] = "Ave. Hidalgo y Calle Tercera # 209, Colonia Comercial. San Luis Rio Colorado, Sonora CP 83449";
	direccion24[7] = "Blvd. El Greco  s/n. Esquina con Carretera Internacional KM 4.5 (Av. Álvaro Obregón) Col el Greco, CP 84063 Nogales, Sonora";
	
	
    
var telefonos24 = new Array();
    telefonos24[0] = "6622122839";
	telefonos24[1] = "6622110974 &oacute; 6622110976";
	telefonos24[2] = "6444153165";
	telefonos24[3] = " ";
	telefonos24[4] = "6222210652 &oacute;	6222213057";

	telefonos24[6] = "6535367582 &oacute; 6535367401";
	telefonos24[7] = "6313134026 &oacute; 6313150332";
	
		

var indice=0;
var map;
var i=0;

function initializeSon() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(29.4067974,-111.7381017), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx24[number-1],posy24[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion24[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos24[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx24.length; i++) {
	    var point = new GLatLng(posx24[i],posy24[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover24(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx24[number-1],posy24[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales24[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion24[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos24[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx24[number-1],posy24[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }