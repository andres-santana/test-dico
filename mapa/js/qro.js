var sucursales20 = new Array();
/*    sucursales20[0] = "Queretaro - Bernardo Quintana";*/
	  sucursales20[1] = "Queretaro - Constituyentes";
    sucursales20[2] = "Queretaro - El Punto";
	
	sucursales20[3] = "San Juan del Rio";
	sucursales20[4] = "San Juan del Rio - Galerias";
	sucursales20[5] = "Queretaro - Galerias";
       sucursales20[6] = " Jurica";
    	
var posx20 = new Array();
/*	  posx20[0] = "20.612027"; */
	  posx20[1] = "20.588448";
    posx20[2] = "20.619811";
	
	posx20[3] = "20.393101"; 
	posx20[4] = "20.386062";
	posx20[5] = "20.578317";
       posx20[6] = "";
    
var posy20 = new Array();
/*    posy20[0] = "-100.382964";  */
	  posy20[1] = "-100.367891";
    posy20[2] = "-100.439243";
	
	posy20[3] = "-99.984010";
	posy20[4] = "-100.012562";
	posy20[5] = "-100.404500";
       posy20[6] = "-100.433394";
    

var direccion20 = new Array();
/*    direccion20[0] = "Bernardo Quintana # 518. Fracc. Arboledas CP 76140 Querétaro, Qro.";  */
	  direccion20[1] = "Av. Constituyentes No. 113 Ote. ( a 5 calles del Auditorio)";
    direccion20[2] = "Avenida Revolución 99, Col Puerta del Sol C.P. 76116";
	
	direccion20[3] = "Av. Central # 19 Col. San Cayetano Sn. Juan del Rio Qro. C.p. 76807";
	direccion20[4] = "Galerías San Juan del Rio Local 246 Carretera Panamericana No. 202 Col. La Venta San Juan del Río. Qro CP. 76807";
	direccion20[5] = "Local # 90 Av. 5 de Febrero # 99 Esq. Celaya Cuota Col. Los Virreyes C.P. 76175 Querétaro. Qro.";
       direccion20[6] = "Centro Comercial �CENTRO 55� Paseo de la Republica s/n Col. Jurica , Queretaro, QRO C.P. 76100.";
   	
    
var telefonos20 = new Array();
/*    telefonos20[0] = "4422237925 &oacute; 4422450542";  */
	  telefonos20[1] = "4422130902 &oacute;	4422139676";
    telefonos20[2] = "4422570491  &oacute;  4422572554";
	
	telefonos20[3] = "4272721563  &oacute;  4272749151";
	telefonos20[4] = "4272748957  &oacute;  4272720899";
	telefonos20[5] = "4422156903  &oacute;  4422150434";
       telefonos20[6] = "4426889886  &oacute;  4426889887";
   
var indice=0;
var map;
var i=0;

function initializeQro() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(20.8430849,-99.821474), 9);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx20[number-1],posy20[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales20[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion20[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos20[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx20.length; i++) {
	    var point = new GLatLng(posx20[i],posy20[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover20(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx20[number-1],posy20[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales20[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion20[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos20[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx20[number-1],posy20[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }