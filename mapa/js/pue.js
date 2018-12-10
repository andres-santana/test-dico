var sucursales19 = new Array();
    sucursales19[0] = "Puebla - 5 de Mayo";
	  sucursales19[1] = "Puebla - Angelopolis";
	  sucursales19[2] = "Puebla - Forjadores";
	  sucursales19[3] = "Puebla - Los Volcanes";
	  sucursales19[4] = "San Martin - Palza Cristal";
	  sucursales19[5] = "Tehuacan - Paseo Tehuacan";
	  
	  sucursales19[6] = "Teziutlan";
	  sucursales19[7] = "Puebla - San Pedro";
         sucursales19[8] = "Plaza Parque Puebla";



var posx19 = new Array();
	  posx19[0] = "19.03142";
	  posx19[1] = "19.025841";
	  posx19[2] = "19.074507";
	  posx19[3] = "19.03999";
	  posx19[4] = "19.289184";
	  posx19[5] = "18.469677";
	  
	  posx19[6] = "19.808016";
	  posx19[7] = "19.065689";
         posx19[8] = "19.070683";



var posy19 = new Array();
    posy19[0] = "-98.200508";
	  posy19[1] = "-98.236645";
	  posy19[2] = "-98.259102";
	  posy19[3] = "-98.21944";
	  posy19[4] = "-98.423469";
	  posy19[5] = "-97.417145";
	  
	  posy19[6] = "-97.366865";  
	  posy19[7] = "-98.213283"; 
         posy19[8] = "-98.173662"; 

var direccion19 = new Array();
    direccion19[0] = "Blvd. 5 de Mayo Esq. 29 Ote. Puebla. Puebla";
	  direccion19[1] = "Autopista Via Atlixcayotl No. 5320 Col. Reserva Territorial Atlixcayotl C.P. 72193 Puebla, Puebla (Frente a Wal-Mart Plaza Via San Angel) Entre Calle Kepler y Boulevard Del Ni?o Poblano.";
	  direccion19[2] = "Blvd. Forjadores # 8503 Col. Rancho Sta. Cruz Puebla. Puebla";
	  direccion19[3] = "Calle Citlaltepetl Volcanes # 2725 Col. Los Volcanes Puebla, Puebla C.P.  72410";
	  direccion19[4] = "Plaza Crystal San Martin Sub-Ancla 1 A Locales 13 y 16 Blvd. Xicot?ncatl #614 Col. San Dami?n C.P. 74000 San Martin Texmelucan . Puebla";
	  direccion19[5] = "Calzada A. Lopez Mateos 3800 <br>Local S-A Num 4 San Lorenzo Teotipilco Tehuac?n Puebla. CP 75855";
	  
	  direccion19[6] = "Plaza Maravillas Local 1-6. Prolongacion de Francisco Javier Mina #214 Col. Centro Teziutlan Puebla. Puebla C.P. 73890";
	  direccion19[7] = "Plaza Paseo Norte Blvd Norte Heroes de Cinco de Mayo #1870, Col Las Hadas Mundial 86, Puebla";
         direccion19[8] = "Centro Comercial Parque Puebla Calle: Calzada Ignacio Zaragoza Numero: 410 Local: L234B, 235, 236, 236B, 237 (segundo Nivel)Corredor industrial, La Ciénega C.P. 72220 Puebla, Puebla";
	  
    
var telefonos19 = new Array();
    telefonos19[0] = "2222430409 &oacute;	2222433901";
	  telefonos19[1] = "2222251303 &oacute;	2222251304";
	  telefonos19[2] = "2222268699 &oacute; 2222268872";
	  telefonos19[3] = "2222110297";
	  telefonos19[4] = "2481122675 &oacute; 2481170338";
	  telefonos19[5] = "2383923938 &oacute;	2383823956";
	  
	  telefonos19[6] = "2313133711 &oacute;	2313136925";
	  telefonos19[7] = "2222300986 &oacute;	2222265967"; 
	  telefonos19[8] = "2222278544 &oacute;	2222278545";
	

var indice=0;
var map;
var i=0;

function initializePue() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.0412893,-98.192966), 12);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx19[number-1],posy19[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales19[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion19[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos19[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx19.length; i++) {
	    var point = new GLatLng(posx19[i],posy19[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover19(sucursal){
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx19[number-1],posy19[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales19[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion19[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos19[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx19[number-1],posy19[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }