var sucursales28 = new Array();
    sucursales28[0] = "Merida - Altabrisa";
	sucursales28[1] = "Merida - Americas";
	sucursales28[2] = "Merida - Macroplaza";
	sucursales28[3] = "Merida - Sendero";
	sucursales28[4] = "Merida - Gran Plaza";
    sucursales28[5] = "Merida - Plaza Kukulkan";
	sucursales28[6] = "Merida - Plaza Bella Almendros";
		
		
		
var posx28 = new Array();
	posx28[0] = "21.018286";
	posx28[1] = "20.991393";
	posx28[2] = "20.998381";
	posx28[3] = "20.975866";
	posx28[4] = "21.029872";
	posx28[5] = "20.9404313";
	posx28[6] = "20.994917";
	
var posy28 = new Array();
    posy28[0] = "-89.584581";
	posy28[1] = "-89.648418";
	posy28[2] = "-89.572761";
	posy28[3] = "-89.596767";
	posy28[4] = "-89.624516";
	posy28[5] = "-89.5923984";
	posy28[6] = "-89.698308";

var direccion28 = new Array();
    direccion28[0] = "Manzana 594, sección 26 número 451 Fracc. Altabrisa, Mérida, Yucatán, local 117 y 124 CP 97130";
	direccion28[1] = "Calle 21, num 327 entre 50 y 52, colonia Miguel Hidalgo CP 97229 Mérida Yucatan";
	direccion28[2] = "Calle 33 No. 200 x 44  Col. Itzimna Polígono 108 CP. 97158 Mérida, Yucatán";
	direccion28[3] = "Av. Circuito Colonias No. 70 x 20, Col. Chuminópolis, Sub-Ancla H, CP 97158, Mérida, Yuc.";
	direccion28[4] = "Calle 50 Diagonal, No. 460, Colonia Gonzalo Guerrero, Mérida Yucatán, C.P. 97115, Locales 131 Planta Alta, La Gran Plaza Mérida.";
       direccion28[5] = "Local 01 al SA-01 Calle 99 No.300 por 12 y 16, Colonia Ampliación  Salvador Alvarado Sur, CP 97195, Mérida, Yuc.";
	direccion28[6] = "Calle 70 Num. 661  Fracc Ciudad Caucel Los Almendros, Mz 106 Secc. 41, Merida, Yucatan C.P. 97314 Local del A1 al A5 Centro Comercial Plaza Bella";



var telefonos28 = new Array();
    telefonos28[0] = "9991679815";	
	telefonos28[1] = "9999879061";
	telefonos28[2] = "9991962743";
	telefonos28[3] = "9991881468 &oacute;	9991881481 ";
	telefonos28[4] = "9999416165";
       telefonos28[5] = "9991662436 &oacute;	9991660063 ";
	telefonos28[6] = "9994812567";
	

var indice=0;
var map;
var i=0;

function initializeYuc() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(20.5805196,-88.9702046), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx28[number-1],posy28[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales28[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion28[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos28[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx28.length; i++) {
	    var point = new GLatLng(posx28[i],posy28[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover28(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx28[number-1],posy28[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales28[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion28[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos28[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx28[number-1],posy28[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }