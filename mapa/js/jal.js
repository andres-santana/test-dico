var sucursales14 = new Array();
    sucursales14[0] = "Guadalajara - Av. Vallarta ";
	  sucursales14[1] = "Federalismo";
    sucursales14[2] = "Mariano Otero";
	  sucursales14[3] = "López Mateos ";
<!--	  sucursales14[4] = "Rio Nilo"; -->
	  sucursales14[5] = "Rio Nilo Walmart";
	  sucursales14[6] = "Guadalajara Ciudadela";
	  sucursales14[7] = "Guadalajara Plazas Outlet";
	  sucursales14[8] = "Puerto Vallarta - Plaza Caracol";
	  sucursales14[9] = "Puerto Vallarta - Plaza Galerias";
	  sucursales14[10] = "Plaza Pabell&oacute;n";
   
    
	

var posx14 = new Array();
    posx14[0] = "20.667822";
	  posx14[1] = "20.667822";
	  posx14[2] = "20.637805";
	  posx14[3] = "20.657282";
<!--	  posx14[4] = "20.644029"; -->
	  posx14[5] = "20.640165";
	  posx14[6] = "20.649009";
	  posx14[7] = "20.521915";
	  posx14[8] = "20.63945";
	  posx14[9] = "20.657857";
	  posx14[9] = "27.4819897";
   

var posy14 = new Array();
    posy14[0] = "	-103.355341";
	  posy14[1] = "	-103.355341";
	  posy14[2] = "	-103.420787";
	  posy14[3] = "	-103.398428";
<!--	  posy14[4] = "	-103.28217"; -->
	  posy14[5] = "	-103.274862";
	  posy14[6] = "-103.420315";
	  posy14[7] = "-103.480911";
	  posy14[8] = "-105.2348";
	  posy14[9] = "-105.240216";
	  posy14[9] = ",-109.9310517";
	

var direccion14 = new Array();
      direccion14[0] = "Av. Vallarta Num. 5566 Interior J Col. Lomas Universidad Zapopan. Jalisco C.P. 45017";
	  direccion14[1] = "Federalismo Sur. No. 578 C.P. 44373";
	  direccion14[2] = "Mariano Otero No. 5108 C.P. 44454";
	  direccion14[3] = "Av. Lopez Mateos Sur No. 2113. Col . Chapalita Zapopan, Jalisco. C.P. 44328";
<!--	  direccion14[4] = "Av. Rio Nilo No. 1000. Centro Comercial Soriana, Loc. 18 y 19 C.P. 44825"; -->
	  direccion14[5] = "Centro Comercial Altea Rio Nilo Av. Rio Nilo #7377  local 1 Col. Lomas de la Soledad C.P.45403, Guadalajara, Jalisco";
	  direccion14[6] = "AV PATRIA 1437, COL CIUDAD DEL SOL ZAPOPAN. PLAZA CIUDADELA LOCAL 38 CP45050";
	  direccion14[7] = "Carretera Gdl-Morelia K.m. 12.5, C.P. 45640, Tlajomulco de Zuñiga, Jalisco";
	  direccion14[8] = "Av. Francisco Medina Ascencio Local 6 E PLAZA CARACOL, Col. Los Tules C.P 48380 Puerto Vallarta Jalisco";
	  direccion14[9] = "Av. Francisco Medina Ascencio No. 2920 Plaza Galerias Local 260, Col. Educación, C.P. 48338, Puerto Vallarta, Jalisco Plaza Galerias";
     direccion14[9] = "Av. Acueducto #2380 Locales 11,12,13 y local B Planta baja Col. Colinas De San Javier";
    
		
    
var telefonos14 = new Array();
    telefonos14[0] = "3331650740 &oacute;	3336829496";
	  telefonos14[1] = "3338250272 &oacute;	3338275604";
	  telefonos14[2] = "3336202684 &oacute;	3336202704";
	  telefonos14[3] = "3331211635 &oacute;	3331214679";
<!--	  telefonos14[4] = "3336801798 &oacute;	3336805981"; -->
	  telefonos14[5] = "3336066354";
	  telefonos14[6] = "3336323561";
	  telefonos14[7] = " ";
	  telefonos14[8] = "3222243205";
	  telefonos14[9] = " ";
	  telefonos14[10] = "3316531362 &oacute 3336111071";
    

var indice=0;
var map;
var i=0;

function initializeJal() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(20.8375717,-103.6022721),7);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx14[number-1],posy14[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales14[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion14[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos14[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx14.length; i++) {
	    var point = new GLatLng(posx14[i],posy14[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover14(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx14[number-1],posy14[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales14[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion14[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos14[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx14[number-1],posy14[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }