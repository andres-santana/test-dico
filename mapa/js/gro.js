var sucursales11 = new Array();
    sucursales11[0] = "Acapulco - Costera Centro";
    sucursales11[1] = "Acapulco - Diamante (frente a La Isla)";
//    sucursales11[2] = "Acapulco - Plaza Diana";
    sucursales11[3] = "Chilpancingo - Galerias";
    sucursales11[4] = "Taxco - Las Americas";
	
	
	sucursales11[6] = "Acapulco - Costera";
    
	

var posx11 = new Array();
    posx11[0] = "16.849193";
    posx11[1] = "16.775532";
//	posx11[2] = "16.859778";
	posx11[3] = "17.51495";
	posx11[4] = "18.547228";
	
	
	posx11[6] = "16.856519";


var posy11 = new Array();
    posy11[0] = "-99.905257";
    posy11[1] = "-99.777562";
//	posy11[2] = "-99.873139";
	posy11[3] = "-99.48421";
	posy11[4] = "-99.607201";
	
	
	posy11[6] = "-99.863181";
	

var direccion11 = new Array();
    direccion11[0] = "Av. Costera Miguel Alemán 221. Col. Centro. Acapulco Guerrero. C.P. 39300";
    direccion11[1] = "Blvd. De las Naciones #1124,Col. La Poza Zanja Acapulco de Juarez, Guerrero. C.P. 39906.";
//    direccion11[2] = "CENTRO COMERCIAL GALERIAS DIANA Local S17, 2° Piso Av. Costera Miguel Alemán # 1926 Esq. Vicente Yáñez Piñon. Fracc. Magallanes C.P. 39670 Acapulco, Gro.";
    direccion11[3] = "Galerías Chilpancingo Blvd. René Juárez Cisneros # 130 Local 405 Col.  Predio Tepango C.P.39010 Chilpancingo, Gro.";
    direccion11[4] = "Centro comercial las américas Taxco Libramiento capilintla #27, Locales 14,15,16 y 17 Col. Pedro Martín, Taxco Guerrero, CP 40290";
	
	
	direccion11[6] = "AV. COSTERA MIGUEL ALEMAN #2322, COL FRACC CLUB DEPORTIVO, ACAPULCO DE JUAREZ ";
    
		
    
var telefonos11 = new Array();
    telefonos11[0] = "7444820095 &oacute; 7444802307";
    telefonos11[1] = "7444332301 &oacute; 7444332302";
//    telefonos11[2] = "7444844938 &oacute;	7444846507";
    telefonos11[3] = "7471234076 &oacute;	7474949538";
    telefonos11[4] = "7626270221 &oacute;	7626270229";
	
	
	telefonos11[6] = "01 744 4846284 &oacute;	01 744 4846883";
    

var indice=0;
var map;
var i=0;

function initializeGro() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(17.6009455,-100.0949411), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx11[number-1],posy11[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales11[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion11[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos11[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx11.length; i++) {
	    var point = new GLatLng(posx11[i],posy11[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover11(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(19.432732,-99.133168), 11);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx11[number-1],posy11[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales11[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion11[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos11[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx11[number-1],posy11[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }