var sucursales27 = new Array();
    sucursales27[0] = "Jalapa - Ávila Camacho";
	sucursales27[1] = "Orizaba Centro";
	sucursales27[2] = "Poza Rica - Ruiz Cortinez";
	sucursales27[3] = "Tuxpan";
  sucursales27[4] = "Veracruz - Boca del Rio";
  sucursales27[5] = "Veracruz - Rafael Cuervo";
  sucursales27[6] = "Veracruz - Carso";
  sucursales27[7] = "Coatzacoalcos - Palmas";

  sucursales27[9] = "Córdoba - Veracruz";
	sucursales27[8] = "Xalapa II - Plaza Crystal";
	 
		
var posx27 = new Array();
	posx27[0] = "19.527475";
	posx27[1] = "18.847444";
	posx27[2] = "20.533618";
	posx27[3] = "20.951095";
  posx27[4] = "19.141134";
  posx27[5] = "19.215598";
  posx27[6] = "19.531150";
  posx27[7] = "18.141782";

	posx27[9] = "18.903318";
	posx27[8] = "19.537189";
	
var posy27 = new Array();
    posy27[0] = "-96.925972";
	posy27[1] = "-97.095068";
	posy27[2] = "-97.459752";
	posy27[3] = "-97.417126";
  posy27[4] = "-96.106986";
  posy27[5] = "-96.178001";
  posy27[6] = "-96.909656";
  posy27[7] = "-94.482511"; 

	posy27[9] = "-96.961853";
	posy27[8] = "-96.907028";
	
var direccion27 = new Array();
    direccion27[0] = "Av. Ávila Camacho 27-A Col. Centro C.P. 91000 Jalapa, Ver.";
	direccion27[1] = "Av. Oriente 6, No. 1025 Col. Centro, C.P. 94300, Orizaba, Veracruz";
	direccion27[2] = "Blvd. Adolfo Ruiz Cortínez. No 800 Esquina República Costa Rica Col. 27 Septiembre C.P. 93320, Poza Rica, Veracruz";
	direccion27[3] = "Demetrio Ruiz #43 Esq. Matamoros Tuxpan de Rodríguez Cano Veracruz";
  direccion27[4] = "Av. Americas #  551 Col. Petrolera Boca del Rio. Vereacruz C.P. 94298";
  direccion27[5] = "Calle Playa Sacrificios No. 433, Bodega 1 Esq. Dr. Rafael Cuervo.Col. Playa Linda Veracruz, Ver C.P. 91810";
  direccion27[6] = "Centro Comercial Nuevo Veracruz LOCAL A-25-C Carretera México- Xalapa KM. 435.3 Col. El Laureal de Buenavista Veracruz, Ver. C.P. 91697";
  direccion27[7] = "Av. Universidad s/n Fraccion D,E, F Locales 6,7 y 8, Predio El Encanto  C.P. 96536 Coatzacoalcos Ver.";

	direccion27[9] = "Avenida de los pinos #4022, Col.Santa Leticia Fortín de la Flores Veracruz. C.P.94476";
	direccion27[8] = "Antonio Chedraui Caram 109, Col. Encinal, 91150 Xalapa Enríquez, Veracruz. C.p.91180";	
    
var telefonos27 = new Array();
    telefonos27[0] = "2288123329 &oacute;	2288172490";
	telefonos27[1] = "2727248229 &oacute;	2727248984";
	telefonos27[2] = "7828270335 &oacute;	7828226749";
	telefonos27[3] = "7838344336 &oacute;	7838353567";
  telefonos27[4] = "2299272644 &oacute; 2299273227"; 
  telefonos27[5] = "2291006839 &oacute; 2291006741";
  telefonos27[6] = "2296882459 &oacute; 2296882462";
  telefonos27[7] = "9212105920 &oacute; 9212106781";

	telefonos27[9] = "2717161549 &oacute; 2717161558"; 
	telefonos27[8] = "2288147509 &oacute; 2288147582"; 
				

var indice=0;
var map;
var i=0;

function initializeVer() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.803626,-96.1439169), 7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx27[number-1],posy27[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales27[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion27[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos27[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx27.length; i++) {
	    var point = new GLatLng(posx27[i],posy27[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover27(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx27[number-1],posy27[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales27[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion27[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos27[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx27[number-1],posy27[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }