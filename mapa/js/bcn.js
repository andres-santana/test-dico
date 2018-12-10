var sucursales5 = new Array();
//    sucursales5[0] = "Agua Caliente";
    sucursales5[1] = "Galerias del Valle";
    sucursales5[2] = "Pabellon Rosarito";
    sucursales5[3] = "La Gloria";
    sucursales5[4] = "Loma Bonita";
    sucursales5[5] = "Macroplaza";
//    sucursales5[6] = "Nuevo Mexicalli";
    sucursales5[7] = "Reforma ";
	sucursales5[8] = "Tijuana-Paseo 2000";


var posx5 = new Array();
//    posx5[0] = "32.515118";
    posx5[1] = "32.622593";
    posx5[2] = "32.376802";
    posx5[3] = "32.464874";
    posx5[4] = "32.485656";
    posx5[5] = "32.494728";
 //   posx5[6] = "32.613099";
    posx5[7] = "31.834427";
	posx5[8] = "32.492759";

var posy5 = new Array();
//    posy5[0] = "-117.008343";
    posy5[1] = "-115.508021"; 
    posy5[2] = "-117.059159";
    posy5[3] = "-117.019465";
    posy5[4] = "-117.05418";
    posy5[5] = "-116.933065";
//    posy5[6] = "-115.384859";
    posy5[7] = "-116.600906";
	posy5[8] = "-116.850709";

var direccion5 = new Array();
//    direccion5[0] = "Blvd. General Gustavo Salinas No. 10724, Col. Aviacion. Tijuana B.C. C.P 22014";
    direccion5[1] = "Blvd. Lazaro Cardena plaza galerias del valle local C2 #2200 Col. El porvenir Mexicali B.C.CP 21220";
    direccion5[2] = "Carretera libre Tijuana- Ensenada No. 300 Col. Reforma. Playas de Rosarito CP. 22710";
    direccion5[3] = "KM. 9.5 Carretera Libre Tijuana-Enseanada Fraccionamiento Panamericano S/N. Delegación San Antonio de los buenos. Tijuana. B.C. C.P. 22670";
    direccion5[4] = "Blvd. Agua azul # 7200Col. Loma bonita Norte Centro comercial Loma bonita local LA3  Tijuana B.C.";
    direccion5[5] = "Av. Boulevard Insurgentes 18.015  LOCAL 8A Fraccionamiento Rio Tijuana Tercera Etapa (La Mesa)  C.P. 22226 Ciudad de Tijuana. Estado de Baja California";
//    direccion5[6] = "Plaza nuevo Mexicali Local 94, av. Lazaro Cardenas Esq Prolongacion Calle Novena";
    direccion5[7] = "Av. Reforma 555. Col. Valle Dorado Baja California. Ensenada";
	direccion5[8] = "Plaza comercial  paseo 2000 , Blvd. Tijuana Rosarito 2000 #26135 local A-PB-05 Y A-PB-06 Col. Ejido Francisco Villa. Delegación la presa Tijuana B.C. C.P. 22205";
  
var telefonos5 = new Array();
  //  telefonos5[0] = "6646866688 &oacute; 6646866741";
    telefonos5[1] = "6865558077 &oacute; 6865680877";
    telefonos5[2] = "6616139905 &oacute; 6616139594";
    telefonos5[3] = "6646372900 &oacute; 6646372901";
    telefonos5[4] = "6649002870 &oacute; 6649002898";
    telefonos5[5] = "6646250030 &oacute; 6649695525";
//    telefonos5[6] = "6865806649";
    telefonos5[7] = "6461776767 &oacute; 6461776780";
	telefonos5[8] = "6642118998 &oacute; 6642117109";

var indice=0;
var map;
var i=0;

function initializeBcn() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(30.836215,-115.279541), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx5[number-1],posy5[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales5[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion5[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos5[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx5.length; i++) {
	    var point = new GLatLng(posx5[i],posy5[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover5(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(30.836215,-115.279541), 7);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx5[number-1],posy5[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales5[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion5[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos5[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx5[number-1],posy5[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }