//user clicks button
    if ("geolocation" in navigator){ //check geolocation available 
        //try to get user current location using getCurrentPosition() method
        geocoder = new google.maps.Geocoder();
        navigator.geolocation.getCurrentPosition(function(position){ 
                $("#result").html("Found your location <br />Lat : "+position.coords.latitude+" </br>Lang :"+ position.coords.longitude);
             console.log("Lat: "+position.coords.latitude+"<br>Long:"+ position.coords.longitude);

             var address = position.coords.latitude+","+position.coords.longitude;
              geocoder.geocode({
      'address': address
        // 'latLng': latlng si lo que queremos ejecutar en geocodificación inversa
    }, function(results, status) {
      		if (status == google.maps.GeocoderStatus.OK) {
    		        for(var j=0;j < results[0].address_components.length; j++){
    		            for(var k=0; k < results[0].address_components[j].types.length; k++){
    		                if(results[0].address_components[j].types[k] == "postal_code"){
     		                   zipcode = results[0].address_components[j].short_name;
								//document.getElementById('CP').innerHTML = zipcode;
								console.log("CP:"+zipcode);
								if (zipcode>="50009" && zipcode <= "57950") {
									parent.location.assign("https://dico.com.mx/df/");
								}
     		               }
     		           }
     		       }
     		 }
      // Se detallan los diferentes tipos de error
    		  else {
    		    alert('Geocode no tuvo éxito por la siguiente razón: ' + status)
     		 }
    	})
    });
    }else{
        console.log("Browser doesn't support geolocation!");
    }
