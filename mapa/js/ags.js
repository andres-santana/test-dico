var sucursales4 = new Array();
    sucursales4[0] = "Altaria";
    sucursales4[1] = "Prados";
    sucursales4[2] = "Mega";

var posx4 = new Array();
    posx4[0] = "21.92537";
    posx4[1] = "21.855325";
    posx4[2] = "21.914462";

var posy4 = new Array();
    posy4[0] = "-102.288122";
    posy4[1] = "-102.293358";
    posy4[2] = "-102.288294";

var direccion4 = new Array();
    direccion4[0] = "Blvd. Zacatecas norte No.849 Plaza Altaria Local 2007 y 2008 Col. Las Trojes";
    direccion4[1] = "Blvd. Jose Ma. Chavez No. 1809 Col. Prados de Villa Asuncion CP 20280";
    direccion4[2] = "Av. Aguascalientes Norte No. 802 Local SA-1C, Col. Trojes de Alonso, C.P. 20120";
 
var telefonos4 = new Array();
    telefonos4[0] = "4499933801 &oacute; 449993802";
    telefonos4[1] = "4499784298 &oacute; 4499785753";
    telefonos4[2] = "4492517903 &oacute; 4492517900";

var indice=0;
var map;
var i=0;

function initializeAgs() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(21.882846,-102.294388), 12);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx4[number-1],posy4[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales4[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion4[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos4[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx4.length; i++) {
	    var point = new GLatLng(posx4[i],posy4[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover4(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(21.882846,-102.294388), 12);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx4[number-1],posy4[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales4[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion4[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos4[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx4[number-1],posy4[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }