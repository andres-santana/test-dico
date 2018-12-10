(function($) {
	
	$.infoToggle = function() {
				
		if($(document).find("#info").length > 0) {
			
			var info = $("#info"), infoFound = info.find("#info-text");
			
			if(infoFound.length > 0 && info.find(".info-button").length > 0) {
				
				var infoText = $("#info-text"), infoButton = $(".info-button"), isOpen = false;
				infoButton.click(handleInfo);
						
			}
			else if(infoFound) {
				
				$("#info-text").css("display", "block");
					
			}
			
			info = null;
			infoFound = null;
					
		}
				
		function handleInfo() {
				
			if(!isOpen) {
					
				infoText.fadeIn(500);
				isOpen = true;
						
			}
			else {
						
				infoText.fadeOut(300);
				isOpen = false;
						
			}
					
		}
			
	}
	
})(jQuery);