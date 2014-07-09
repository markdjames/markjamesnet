var disable_carousel = false;
var carousel_skip = 237;
var animate_carousel = true;
var animation_timer = null;

function carousel(id, dir) {
	"use strict";
	clearTimeout(animation_timer);
	
	if (!disable_carousel && animate_carousel) {
		disable_carousel = true;
		
		var carousel_slider_width = $('#carousel'+id+' .carousel_slider').width();
		var carousel_width = $('#carousel'+id+' .carousel_inner').width();
		var cur_left = parseInt($('#carousel'+id+' .carousel_slider').css('left'), 2);
		var new_left = "";
		
		if (dir==='last') {
			new_left = -((carousel_slider_width-carousel_width))+'px';
			
		} else if (dir==='right') {	
			if (Math.abs(cur_left) < (carousel_slider_width-carousel_width)) {
				new_left = (cur_left-carousel_skip)+'px';
			} else {
				new_left = 0;
			}
		} else {
			if (cur_left < 0) {
				new_left = (cur_left+carousel_skip)+'px';
			} else {
				new_left = -((carousel_slider_width-carousel_width))+'px';
			}
		}
		$('#carousel'+id+' .carousel_slider').animate({
			left:new_left
		}, function() {
			disable_carousel = false;
		});
		
	}
	animation_timer = setTimeout(function() { carousel(id, 'right'); }, 7000);
}