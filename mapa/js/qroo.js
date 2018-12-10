var sucursales21 = new Array();
    sucursales21[0] = "Cancun - Gran Plaza";
  sucursales21[1] = "Cancun - Xcaret";
  sucursales21[2] = "Cancun - Malecon";
  sucursales21[3] = "Cancun - Mall II ";
  sucursales21[4] = "Playa del Carmen - Las Am&eacute;ricas";
  
  
var posx21 = new Array();
  posx21[0] = "21.140058";
  posx21[1] = "20.998381";
  posx21[2] = "21.145052";
  posx21[3] = "21.018286";
  posx21[4] = "20.648439";
  
  

var posy21 = new Array();
    posy21[0] = "-86.853973";
  posy21[1] = "-89.572761";
  posy21[2] = "-86.822989";
  posy21[3] = "-89.584581";
  posy21[4] = "-87.081775";
  
  

var direccion21 = new Array();
    direccion21[0] = "Av. Nichupte, Mz. 18 Lt. I A-4 S.M.Z. 51 Centro Comercial Gran Plaza Locales 45,45-A Y 47 Cp. 77533 Cancun, Quintana Roo";
  direccion21[1] = "Av. Xcaret, Súper Manzana 36, Manzana 02, Lote 5-01, Local B2.Entre Zafiro y Palenque C.P. 77507 ";
  direccion21[2] = "Av. Tulum No. 260, Super Manzana 7, Col. Centro, Cancún, Quintana Roo";
  direccion21[3] = "Av Circuito vial y Costa Maya Super-Manzana 228, manzana 22 lote 1 Cancún,  Q. Roo, CP. 77510 Plaza comercial Cancun Mall. Locales: 73 y 74";
  direccion21[4] = "Calle Paseo Central SM 52 Mza 1, Lote 1 Col. Nuevo Centro Urbano Playa del Carmen, municipio de Solidaridad; Quintana Roo CP 77710";
  
    
    
var telefonos21 = new Array();
    telefonos21[0] = "9982062058 &oacute; 9988480175";
  telefonos21[1] = "9988840425";
  telefonos21[2] = "9988982789 &oacute; 9988846138";
  telefonos21[3] = "9981326012";
  telefonos21[4] = "9841090655 &oacute; 9841097714";
  
    

var indice=0;
var map;
var i=0;

function initializeQroo() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(19.7401578,-88.0125033),7); //Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx21[number-1],posy21[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales21[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion21[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos21[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
  }
    for (var i = 0; i < posx21.length; i++) {
      var point = new GLatLng(posx21[i],posy21[i]);
      map.addOverlay(createMarker(point, i + 1));
    }
  
    var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map); 
  }
  
}
    function mover21(sucursal)
    {
      var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
      }
     else{
      map.setCenter(new GLatLng(posx21[number-1],posy21[number-1]), 16);
        i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales21[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion21[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos21[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx21[number-1],posy21[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
  
    }