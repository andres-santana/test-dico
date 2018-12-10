var sucursales16 = new Array();
    sucursales16[0] = "Los Atrios (Antes Cuautla)";
	  sucursales16[1] = "Plan de Ayala";
         sucursales16[2] = "Cuautla II";
         sucursales16[3] = "Cuernavaca";
         sucursales16[4] = "Forum Cuernavaca";
         sucursales16[5] = "Jiutepec";
         sucursales16[6] = "R&iacute;o Mayo";
  


var posx16 = new Array();
    posx16[0] = "18.868205";
	  posx16[1] = "18.924165";
         posx16[2] = "18.86979";
         posx16[3] = "18.92421";
         posx16[4] = "18.924648";
         posx16[5] = "18.904376";
         posx16[6] = "18.935151";
	


var posy16 = new Array();
    posy16[0] = "-98.952581";
	  posy16[1] = "-99.220952";
         posy16[2] = "-98.95124";
         posy16[3] = "-99.220943";
         posy16[4] = "-99.199320";
         posy16[5] = "-99.176634";
	  posy16[6] = "-99.203425";




var direccion16 = new Array();
    direccion16[0] = "Calle Camino Real Tetelcingo Calderon # 23, Colonia Tierra Larga Centro Comercial Los Atrios, Sub- Ancla 1 Cuautla, Mor. CP 62751.";
    direccion16[1] = "Plan de Ayala No. 801 Col. Teopanzolco.";
    direccion16[2] = "Calle Camino Real Tetelcingo Calderon # 23. Colonia Tierra Larga Centro Comercial Los Atrios. Sub- Ancla 1 Cuautla. Mor. CP 62751.";
    direccion16[3] = "Plan de Ayala No. 801 Col. Teopanzolco Cuernavaca Morelos CP62350.";
    direccion16[4] = "Plaza Forum Cuernavaca Local: A-03 y PB-46 Jacarandas # 103 y Paseo de los Ficus, Nayarit y FF.CC. M&eacute;xico- Balsas Col. Flores Mag&oacute;n Cuernavaca , Morelos C.P. 62370.";
    direccion16[5] = "Paseo Cuauhnahuac 1932, KM 4.5, MZA 3, Colonia Tlahuapan, Municipio de Jiutepec, Estado de Morelos C.P.72480.";
    direccion16[6] = "calle Galatea #98, Colonia / Fraccionamiento las delicias, Sección Cinco Sur,Cuernavaca Morelos CP 62330.";
	
    
var telefonos16 = new Array();
    telefonos16[0] = "7351312015";
    telefonos16[1] = "7773155371 &oacute;	7353985979";
    telefonos16[2] = "7351312015 &oacute;	7351312028";
    telefonos16[3] = "7773155371 &oacute;	7773155669";
    telefonos16[5] = "7776885651 &oacute;	7776885643";
    telefonos16[6] = "7776885519 &oacute;	7776885506";
	

var indice=0;
var map;
var i=0;

function initializeMor() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(18.7318963,-99.0633631), 10);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx16[number-1],posy16[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales16[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion16[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos16[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx.length; i++) {
	    var point = new GLatLng(posx16[i],posy16[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover16(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx16[number-1],posy16[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales16[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion16[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos16[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx16[number-1],posy16[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }