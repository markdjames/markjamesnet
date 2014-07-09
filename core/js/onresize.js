/****************
* called each time the window is resized - primarily to reposition content
*****************/
$(window).resize(function() {
	"use strict";
	position();
	
	if (parseInt($('#modal').css('top'), 2)<0) {
		$('#modal').css('top', '35px');
		$('#modal').css('height', ($(window).height()-100)+'px');
	}
	
	setScreenSize();
});

$(window).bind('orientationchange', function() {
	"use strict";
	position();
	
	if (parseInt($('#modal').css('top'), 2)<0) {
		$('#modal').css('top', '35px');
		$('#modal').css('height', ($(window).height()-100)+'px');
	}
	
	setScreenSize();
});

function setScreenSize() {
	$.ajax({
		type:'POST',
		url:BASE+'/core/lib/ajax/screen_size.php',
		data:{
			width:$(window).innerWidth(),
			height:$(window).innerHeight()
		},
		success:function() {
			
		}
	});
}