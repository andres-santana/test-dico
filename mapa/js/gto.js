var sucursales12 = new Array();
    sucursales12[0] = "León Poliforum";
    sucursales12[1] = "León de la Piscina";
    sucursales12[2] = "León Centro Max";
    sucursales12[3] = "León Plaza Mayor";
    sucursales12[4] = "León Fashion Mall";
    sucursales12[5] = "Irapuato Campestre";
    sucursales12[6] = "Irapuato San Roque";
    sucursales12[7] = "Irapuato Cibeles";
    sucursales12[8] = "Celaya Galerias";
    sucursales12[9] = "Celaya Walmart";
	sucursales12[10] = "León - Las Torres";
	sucursales12[11] = "Salamanca - El Durazno";
       sucursales12[12] = "Celaya III";


var posx12 = new Array();
    posx12[0] = "21.113428";
    posx12[1] = "21.103309";
    posx12[2] = "21.0998";
    posx12[3] = "21.15792";
    posx12[4] = "21.09352";
    posx12[5] = "20.683019";
    posx12[6] = "20.656077 ";
    posx12[7] = "20.682297";
    posx12[8] = "20.532213";
    posx12[9] = "20.552895";
    posx12[10] = "21.157306";
    posx12[11] = "20.582710";
    posx12[12] = "20.519018";



var posy12 = new Array();
    posy12[0] = "-101.652506";
    posy12[1] = "-101.709368";
    posy12[2] = "-101.6363";
    posy12[3] = "-101.6954";
    posy12[4] = "-101.6266";
    posy12[5] = "-101.368238";
    posy12[6] = "-101.342786";
    posy12[7] = "-101.381493";
    posy12[8] = "-100.777438";
    posy12[9] = "-100.836178";
    posy12[10] = "-101.693687";
    posy12[11] = "-101.213163";
    posy12[12] = "-100.805675";


var direccion12 = new Array();
    direccion12[0] = "Blvd. Francisco villa no. 105. Col. Tlacuache. Cp 37500 león. Guanajuato";
    direccion12[1] = "Calle 1? de Mayo # 207; Col. La Piscina; C.P. 37440; Local 8 ? 9 y 10; León. Gto.";
    direccion12[2] = "Blvd. Adolfo López Mateos Oriente No. 2518 Local 73 Col. Jardines de Jeréz León. Guanajuato";
    direccion12[3] = "Blvd. Juan Alonso de Torres N? 2009 entre Blvd. Campestre  . Esq. Valle del Yaqui. Col. Valle del Campestre C.P. 37160 León. Guanajuato.";
    direccion12[4] = "Centro Comercial Altacia Boulevard Aeropuerto No. 104 Local L-1027 Col. Cerrito de Jerez C.P. 37530 Le?n. Guanajuato";
    direccion12[5] = "Paseo Irapuato Esq. Heroes de Nacozari COL. 1? San Gabriel C.P. 36500 IRAPUATO GTO.";
    direccion12[6] = "Av. Enrique Colunga N? 1184 Col. 18 de Agosto C.P. 36590 Irapuato. Guanajuato";
    direccion12[7] = "Blvd. A Villas de Irapuato #1443 Centro Comercial Plaza Cibeles Local 91 Col. Ejido Irapuato C.P. 36643 Irapuato, Guanajuato";
    direccion12[8] = "Centro comercial Galerais Celaya local 430 av. Nororiente no. 200. Colonia las Compuertas del Campestre. C.P. 38080 Celaya. Guanajuato";
    direccion12[9] = "Eje Norponiente 343 Entre Salvador Ortega y Villa Juarez Fracc. Residencial La Capilla C.P. 38020 Celaya. Guanajuato";
    direccion12[10] = "Boulevard Juan Alonso de Torres N. 1902 local 4 Col. San José Del Consuelo C.P. 37200 León, Guanajuato";	
    direccion12[11] = "Calle Faja de Oro #1308 Entre H. Colegio Militar y Calle Vicente Suarez (Local ubicado frente a Escuela de Educación Superior Libertad) Col. El Durazno C.P. 36748 Salamanca, Guanajuato";
    direccion12[12] = "Boulevard Adolfo Lopez Mateos # 705 Esq. Plan de Ayutla Col. Centro Celaya, Gto.C.P.38000";
  
var telefonos12 = new Array();
    telefonos12[0] = "4777713196 &oacute; 4777116676"; 
    telefonos12[1] = "4777781305 &oacute; 4777781327";
    telefonos12[2] = "4777719115 &oacute; 4777719115";
    telefonos12[3] = "4777794455 &oacute; 4777177572";
    telefonos12[4] = "4771675758 &oacute; 4771676164";
    telefonos12[5] = "4626352621 &oacute; 4626352670";
    telefonos12[6] = "4626227120 &oacute; 4626222845"; 
    telefonos12[7] = "4626932317 &oacute; 4626932393";
    telefonos12[8] = "4611593938 &oacute; 4611596569";
    telefonos12[9] = "4611576156 &oacute; 4611576656";
    telefonos12[10] = "4777761193 &oacute; 4777768550";
    telefonos12[11] = "4641130762 &oacute; 4641130603";
    telefonos12[12] = "4611590292 &oacute; 4611590239";

var indice=0;
var map;
var i=0;

function initializeGto() {

  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(20.8757536,-100.8839802), 8);	//Coloca el mapa para que se vea todo el Estado o region
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
      map.setCenter(new GLatLng(posx12[number-1],posy12[number-1]), 16);
      i=indice % 3;
      var imagen = "images/dico.png"
      var sucursal_= sucursales12[number -1];
      var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion12[number -1]+"</small></p><center><img height=52 width=80  src='" +imagen + "'></center><p><small>Tel&eacute;fono:<br>"+telefonos12[number -1]+"</small></p><br></div>";
        
      map.openInfoWindowHtml(latlng, myHtml);
      indice++;
    });
    return marker;
	}
    for (var i = 0; i < posx12.length; i++) {
	    var point = new GLatLng(posx12[i],posy12[i]);
      map.addOverlay(createMarker(point, i + 1));
	  }
	
	  var ctaLayer = new google.maps.KmlLayer({
      url: 'kml/mex.kml'
    });
    ctaLayer.setMap(map);	
  }
  
}
    function mover12(sucursal)
    {
    	var number= sucursal;
     if(number==0){
      map.setCenter(new GLatLng(24.559266,-104.658782), 13);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx12[number-1],posy12[number-1]), 16);
      	i=indice % 3;
        var imagen = "images/dico.png"
        var sucursal_= sucursales12[number -1];
        var myHtml = "<div style='width:300px; font-weight:bold; text-align:center;'><p><small>"+sucursal_+"<br>"+direccion12[number -1]+"</small></p>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small>Tel&eacute;fono:<br>"+telefonos12[number -1]+"</small></p><br></center> </div>";
        var latlng1 =new GLatLng(posx12[number-1],posy12[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }