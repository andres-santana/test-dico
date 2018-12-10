(function($) {

	$.htmlMusic = function (data) {
		
		// ***********************
		// begin class constructor
		// ***********************
		
		var doc = $(document), 
		pauseOpacity = data.pauseOpacity, 
		html5 = document.createElement("audio").canPlayType, 
		vol;
		
		if(doc.find("#music").length > 0) {
							
			var aud = $("#music"), music = document.getElementById("music"), isPlaying, auto, looped, agent = navigator.userAgent;
			
			if(doc.find(".volume").length > 0) {
				vol = $(".volume");	
			}
			
			if(agent.search("iPhone") === -1 && agent.search("iPad") === -1 && agent.search("iPod") === -1) {
				auto = (aud.attr("autoplay")) ? true : false
			}
			else {
				
				auto = false;
				aud.css({"width": 0, "height": 0});
				
			}
			
			// loop attribute doesn't register for some bizarre reason
			looped = $("footer").html().search("loop") !== -1 ? true : false;
			
			if(html5) {
				music.volume = data.volume * .01;
				music.addEventListener("ended", audioEnded, false);
			}
			else {
				try {
					thisMovie("flash-audio").storeVars(data.volume, $("#mp3").attr("src"), (aud.attr("loop")) ? true : false, auto);
				}
				catch(event){};
			}
					
			if(auto) {
				isPlaying = true;
			}
			else {
				
				isPlaying = false;
				if(vol) vol.css("opacity", pauseOpacity);
							
			}
							
			if(vol) vol.click(handleMusic);
			
			aud = null;
			agent = null;
							
		}
		else if(doc.find(".volume").length > 0) {
						
			$(".volume").css("display", "none");
							
		}
		
		doc = null;
		data = null;
		isHtml5 = null;
						
		// *********************
		// end class constructor
		// *********************
		
		function audioEnded(event) {
			
			event.stopPropagation();
			
			isPlaying = false;
			
			if(looped) {
				handleMusic();
			}
			else {
				if(vol) vol.css("opacity", pauseOpacity);
			}
			
		}
		
		function handleMusic() {
					
			if(isPlaying) {
						
				$(this).css("opacity", pauseOpacity);
				
				if(html5) {
					music.pause();
				}
				else {
					try {
						thisMovie("flash-audio").togglePlayPause(false);
					}
					catch(event){};
				}
				
				isPlaying = false;
						
			}
			else {
						
				$(this).css("opacity", 1);
				
				if(html5) {
					music.play();
				}
				else {
					try {
						thisMovie("flash-audio").togglePlayPause(true);
					}
					catch(event){};
				}
				
				isPlaying = true;
						
			}
					
		}
		
		function thisMovie(swf) {
			
			return (navigator.appName.indexOf("Microsoft") != -1) ? window[swf] : document[swf];
			
		}
		
	}
	
})(jQuery);





