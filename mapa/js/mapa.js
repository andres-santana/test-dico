var posx= new Array();
    var posy= new Array();
//var imag= new Array("images/thumbnail.png","images/csur.jpeg","images/tres.jpeg","images/cirapuato.jpeg","images/cmty.jpeg","images/cmer.jpg","images/cgdl.jpeg","images/ctam.jpeg","images/csal.jpeg","images/cqro.jpg");
var imagbanner= new Array("Dico Arag&oacute;n","Dico Arboledas","Abastos","Huajuapan de Le&oacute;n","Tuxtepec","Puerto Escondido","Xoxo","Salina Cruz","20 de Noviembre","Loma Bonita","Pinotepa Nacional","Ixtepec","M&oacute;dulo Azul","Juchit&aacute;n 1","Juchit&aacute;n 2","Mat&iacute;as Romero","Reforma","San Blas","Ciudad Judicial","Pochutla","Tlaxiaco","Tlacolula","MiahuatlÃ¡n","Morelos");
var ref= new Array("upiita.com","altavista.com","google.com","upiita.com","altavista.com","google.com","upiita.com","altavista.com","google.com");
var indice=0;
var message =["Av. Central 120 Loc. 240","Autpista Mex-Qro. No. 3063 Col., Atenco","Valerio Trujano No. 801","Cuauht&eacute;moc No. 16","Av. 20 de Noviembre No. 170","5Âª Norte Esq. 2Âª Poniente","1Âª Privada de Progreso No. 100<br> Esq. Blvd. Guadalupe Hinojosa de Murat<br>Local 03","Av. Manuel Ãvila Camacho No. 415","Av. 20 de Noviembre No. 707-A<br>Entre Zaragoza y Arista<br>frente al Hotel Posada de Carmen y Elektra","Michoac&aacute;n No. 25 Local 3","Av Ju&aacute;rez No. 217 Esq. Av. P&eacute;rez Gazga","Morelos No. 34, Col. Estaci&oacute ;n","Blvd. M&aacute;rtires de Chicago Esq. Av. Proletariado Mexicano","Efra&iacute;n R. G&oacute;mez No. 15 C","Calle ValentÃ­n Carrasco Num. 3","Ayuntamiento S/N entre Morelos y 5 de Mayo Sur","H. Colegio Militar No. 525, entre Sabinos y Palmeras","C. Francisco Cort&eacute;z entre Benito Ju&aacute;rez y Mateo Jim&eacute;nez","Avenida Gerardo Pandal Graff 1, Fracc. Reyes MantecÃ³n","Av. LÃ¡zaro CÃ¡rdenas esquina Calle Las Palmas de la SecciÃ³n Primera","Calle Aldama Num.6 entre las Calles Independencia e Hidalgo","Calle 2 de Abril n&umero 20 esquina Calle Vicente Guerrero","Calle Basilio Rojas esquina Oriente","Av. Jos&eacute; Mar&iacute;a Morelos No. 703"];
 var message1 =["Plaza Arag&oacute;n. Jardines de Arag&oacute;n","CP 54040 Tlalnepantla","Col. Centro C.P. 68000","Huajuapan de Le&oacute;n C.P. 69000","San Juan Bautista Tuxtepec C.P. 68300","Sector Ju&aacute;rez, Puerto Escondido C.P. 71980","Palestina Santa Cruz Xoxocotl&aacute;n C.P. 71980","Salina Cruz C.P. 70600","Col. Centro C.P. 68300","Loma Bonita C.P. 68400","Santiago Pinotepa Nacional C.P. 71600","Cd. Ixtepec C.P. 70110","Primero de Mayo Infonavit C.P. 68020", "Juchit&aacute;n de Zaragoza C.P. 70000","Juchit&aacute;n de Zaragoza C.P. 70000","Mat&iacute;as Romero C.P. 70300","Oaxaca, Oax. C.P. 68000","Col. Centro, San Blas Atempa C.P.","San Bartolo Coyotepec, Oaxaca C.P. 71257", "San Pedro Pochutla, Oaxaca C.P. 70900","Heroica Ciudad de Tlaxiaco","Tlacolula de Matamoros","MiahuatlÃ¡n de Porfirio DÃ­az, Oaxaca","Col. Centro"];
 var message2 =["8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:30 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","9:00 a.m A 4:30 p.m.","8:30 a.m A 4:30 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","9:00 a.m A 6:30 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 5:00 p.m.","8:30 a.m A 5:00 p.m.","9:00 a.m A 5:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","8:30 a.m A 4:00 p.m.","","8:30 a.m A 4:00 p.m."];
var message3 =["9:00 a.m A 1:00 p.m.","9:00 a.m A 1:00 p.m.","9:00 a.m A 1:00 p.m.","9:00 a.m A 1:30 p.m.","9:00 a.m A 1:30 p.m.","9:00 a.m A 1:30 p.m.","9:00 a.m A 1:45 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 1:00 p.m.","9:00 a.m A 1:30 p.m.","9:00 a.m A 1:30 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 1:45 p.m.","9:00 a.m A 3:00 p.m.","9:00 a.m A 3:30 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 2:00 p.m.","9:00 a.m A 2:00 p.m.","","9:00 a.m A 1:00 p.m."];

var message4 = ["","","","","","","","","","","","","","","","","","","","","","9:00 a.m A 5:00 p.m.","",""];

var map;
var i=0;
function initialize() {
	
//var unidad =["Unidad Zacatenco, Ciudad de MÃ©xico","Unidad Sur, Ciudad de MÃ©xico","Unidad San Borja, Ciudad de MÃ©xico","Unidad Irapuato","Unidad Monterrey","Unidad MÃ©rida","Cinvestav-Guadalajara","Unidad Tamaulipas","Unidad Saltillo","Unidad Queretaro"]  
  if (GBrowserIsCompatible()) {
   
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(24.346784, -101.393607), 5);	//Coloca el mapa para que se vea todo el estado de Oaxaca
    map.setUIToDefault();
 

  
    // Creates a marker at the given point
    // Clicking the marker will hide it
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
      //map.setMapType(G_HYBRID_MAP);
      map.setCenter(new GLatLng(posx[number-1],posy[number-1]), 16);
       //location.href = "www.google.com";
       //return false;
      	i=indice % 3;
      	//var imagen= imag[number -1];
        var imagen = "images/dico.png"
        var banner= imagbanner[number -1];
        //var myHtml1=" <script>GB_show('Google','http://www.google.com.mx');</script>";
//se agrego esta condiciÃ³n para el dÃ­a domingo de Tlacolula
if(message1[number -1] == 'Tlacolula de Matamoros'){
       var myHtml = "<div style='width:300px'><center><p>"+banner+"</p></center><b><p>"+ "<small><center>"+message[number -1]+"<br>"+message1[number -1]+"</center></p></small></b>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small><strong>Horario<br>Lunes a Viernes:"+message2[number -1]+"<br>S&aacute;bados: "+message4[number -1]+ "</strong></small></p></center>  </div><!--small><p align=rigth  onclick=GB_show('Panoramica','Panoramica/panoramica1.html',518,690)> Mas info</p></small-->" ;
    //var myHtml =' <html><head><script type="text/javascript"> var GB_ROOT_DIR = "./greybox/"; </script> <script type="text/javascript" src="./greybox/mapa.js"></script> <script type="text/javascript" src="./greybox/AJS.js"></script> <script type="text/javascript" src="./greybox/AJS_fx.js"></script> <script type="text/javascript" src="./greybox/gb_scripts.js"></script> <link href="./greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" /> <script type="text/javascript" src="./static_files/help.js"></script> <link href="./static_files/help.css" rel="stylesheet" type="text/css" media="all" /></head><body><a href="panoramica.html" title="Panoramica" rel="gb_page_center[630,530]">Click me</a></body></html>';
}
else{
var myHtml = "<div style='width:300px;'><center><p>"+banner+"</p></center><b><p>"+ "<small><center>"+message[number -1]+"<br>"+message1[number -1]+"</center></p></small></b>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small><strong>Horario<br>Lunes a Viernes:"+message2[number -1]+"<br>S&aacute;bados: "+message3[number -1]+"</strong></small></p></center> </div> <!--small><p align=rigth  onclick=GB_show('Panoramica','Panoramica/panoramica1.html',518,690)> Mas info</p></small-->" ;
    //var myHtml =' <html><head><script type="text/javascript"> var GB_ROOT_DIR = "./greybox/"; </script> <script type="text/javascript" src="./greybox/mapa.js"></script> <script type="text/javascript" src="./greybox/AJS.js"></script> <script type="text/javascript" src="./greybox/AJS_fx.js"></script> <script type="text/javascript" src="./greybox/gb_scripts.js"></script> <link href="./greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" /> <script type="text/javascript" src="./static_files/help.js"></script> <link href="./static_files/help.css" rel="stylesheet" type="text/css" media="all" /></head><body><a href="panoramica.html" title="Panoramica" rel="gb_page_center[630,530]">Click me</a></body></html>';
}
        
        map.openInfoWindowHtml(latlng, myHtml);
        indice++;
       
        //alert(i);
      });
      return marker;
	}
        for (var i = 0; i < posx.length; i++) 
	{
	         var point = new GLatLng(posx[i],posy[i]);
      	        map.addOverlay(createMarker(point, i + 1));
}

  }
}
    function mover(sucursal)
    {
    	/*var number=document.getElementById("sucursal").value;*/
    	var number= sucursal;
    //map.setMapType(G_HYBRID_MAP);
     if(number==0){
      map.setCenter(new GLatLng(24.346784, -101.393607), 5);
      map.openInfoWindowHtml();
     	}
     else{
      map.setCenter(new GLatLng(posx[number-1],posy[number-1]), 16);
       //location.href = "www.google.com";
       //return false;
      	i=indice % 3;
      	//var imagen= imag[number -1];
        var imagen = "images/dico.png"
        var banner= imagbanner[number -1];
        //var myHtml1=" <script>GB_show('Google','http://www.google.com.mx');</script>";
	//esta condicion es por Tacolula ya que tiene diferente horario a los demas
	if(message1[number -1] == 'Tlacolula de Matamoros'){
		var myHtml = "<div style='width:300px;'><center><p>"+banner+"</p></center><b><p>"+ "<small><center>"+message[number -1]+"<br>"+message1[number -1]+"</center></p></small></b>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small><strong>Horario<br>Martes a Viernes:"+message2[number -1]+"<br>S&aacute;bados: "+message3[number -1]+"<br>Domingos: "+message4[number -1]+"</strong></small></p></center>  </div><!--small><p align=rigth  onclick=GB_show('Panoramica','Panoramica/panoramica1.html',518,690)> Mas info</p></small-->" ;
	}
else{	

       var myHtml = "<div style='width:300px;'><center><p>"+banner+"</p></center><b><p>"+ "<small><center>"+message[number -1]+"<br>"+message1[number -1]+"</center></p></small></b>"+"<center><img height=52 width=80  src='" +imagen + "'>" + "<p><small><strong>Horario<br>Lunes a Viernes:"+message2[number -1]+"<br>S&aacute;bados: "+message3[number -1]+"</strong></small></p></center>  </div><!--small><p align=rigth  onclick=GB_show('Panoramica','Panoramica/panoramica1.html',518,690)> Mas info</p></small-->" ;
    //var myHtml =' <html><head><script type="text/javascript"> var GB_ROOT_DIR = "./greybox/"; </script> <script type="text/javascript" src="./greybox/mapa.js"></script> <script type="text/javascript" src="./greybox/AJS.js"></script> <script type="text/javascript" src="./greybox/AJS_fx.js"></script> <script type="text/javascript" src="./greybox/gb_scripts.js"></script> <link href="./greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" /> <script type="text/javascript" src="./static_files/help.js"></script> <link href="./static_files/help.css" rel="stylesheet" type="text/css" media="all" /></head><body><a href="panoramica.html" title="Panoramica" rel="gb_page_center[630,530]">Click me</a></body></html>';
	}
        var latlng1 =new GLatLng(posx[number-1],posy[number-1]);
        map.openInfoWindowHtml(latlng1, myHtml);
        indice++;
     }
	
    }