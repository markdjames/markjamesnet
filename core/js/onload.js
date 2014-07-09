/****************
* called on page load
*****************/
$(document).ready(function() {
	"use strict";
	
	$('#screener').click(function() { exitModal(); });
	
	$(document).mousemove(function(e){
		mouseX = e.pageX;
		mouseY = e.pageY;
	});

	if (SUBDIR=='') {
		// position elements
		position();	
		
		// set up some content
		prepContent();
		
		$(".page_image").lazyload({
			effect : "fadeIn"
		});
	}
	
});
var mouseX = 0;
var mouseY = 0;

function prepContent() {
	"use strict";
	// fix any placeholders in IE
	placeholderIEfix();
	
	// add switch function to tabs
	$('.tab').click(function(e) { 
		e.stopImmediatePropagation();                   
		e.preventDefault();
		e.stopPropagation();
		switchTab(this); 
	});
	
	$('a').each(function() {
		
		// loops through a tags and add href where missing
		if ($(this).attr('href')==null) {
			$(this).attr('href','javascript:void(0)');
		}
		// if onclick is specified then stop href's working
		if ($(this).attr('onclick')!=null) {
			$(this).click(function(e){
				e.stopImmediatePropagation();                   
				e.preventDefault();
				e.stopPropagation();          
			});
		}
	});	
}


