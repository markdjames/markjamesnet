/****************
* positions all nessecary elements according to screen/window size
*****************/
var scr = {};
function position() {
	"use strict";
	var sw = $(window).innerWidth();
	var sh = $(window).innerHeight();
	var sc = {};
	sc.left = sw/2;
	sc.top = sh/2;
	
	scr.width = $(window).innerWidth();
	scr.height = $(window).innerHeight();
}

$(window).load(function() {
	"use strict";
	position();
});
